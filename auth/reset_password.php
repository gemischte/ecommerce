<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Helper;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['token'])) {

    $token = $_GET['token'];

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "views/reset_password.php", "reset password");

    if (!empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password === $confirmPassword) {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "SELECT token, token_expiry FROM user_accounts WHERE token = ?";

            if (isset($_GET['token'])) {

                $stmt = $conn->prepare($sql);

                if ($stmt) {

                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $db_token = $row['token'];
                        $token_expiry = $row['token_expiry'];

                        if ($token === $db_token && strtotime($token_expiry) > time()) {
                            $update_password = "UPDATE user_accounts SET password = ?,
                            token = NULL, token_expiry = NULL WHERE token = ?";
                            $stmt = $conn->prepare($update_password);

                            if ($stmt) {
                                $stmt->bind_param("ss", $password, $token);
                                if ($stmt->execute()) {
                                    Alert::success("Success",
                                    "Your password has been successfully reset!",
                                    WEBSITE_URL . "views/login.php");                                     
                                    Helper::redirect_to(WEBSITE_URL . "views/login.php");
                                }
                            }
                        }
                    } 
                    else {
                        Alert::error("Oops...", "Token not found or has expired.",
                        WEBSITE_URL . "views/forgot_password.php");                    
                        Helper::redirect_to(WEBSITE_URL . "views/forgot_password.php");
                    }
                }
            }
        } 
        else {
            Alert::error("Oops...", "Passwords do not match.",
            WEBSITE_URL . "views/reset_password.php?token=" . $_GET['token']);
            Helper::redirect_to(WEBSITE_URL . "views/reset_password.php?token=" . urlencode($token));
        }
    } 
    else {
        Alert::error("Oops...", "Password and confirm password are required.",
        WEBSITE_URL . "views/reset_password.php");
        Helper::redirect_to(WEBSITE_URL . "views/reset_password.php");
    }
}
?>