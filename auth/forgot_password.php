<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Services\Mail;
use App\Utils\Helper;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "views/forgot_password.php", "forgot password");

    //32 length token
    $token = bin2hex(random_bytes(32));

    // 30 minutes
    $token_expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    //Clean expiry token
    $cleanup_expiry_token = "UPDATE user_accounts SET token = NULL, 
    token_expiry = NULL WHERE token_expiry <= NOW()";
    $conn->query($cleanup_expiry_token);

    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = htmlspecialchars(trim($_POST['email']));
        $sql = "SELECT email,username FROM user_accounts WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $reset_password = "UPDATE user_accounts SET token = ?, 
                token_expiry = ? WHERE email = ?";
                $stmt = $conn->prepare($reset_password);

                if ($stmt) {
                    $username = $row['username'];
                    $stmt->bind_param("sss", $token, $token_expiry, $email);
                    $stmt->execute();

                    //Send mail
                    $reset_url = WEBSITE_URL . "views/reset_password.php?token={$token}";
                    Mail::send($email, "Reset password", "<p>Hello {$username}</p> Click the link: <a href='$reset_url'>reset password</a>");
                    Alert::success("Success","An email has been sent to $email,with instructions to reset your password.");
                    Helper::redirect_to(WEBSITE_URL . "views/forgot_password.php");
                }
            } 
            else {
                Alert::error("Oops...", "Email not found or invalid.");
                Helper::redirect_to(WEBSITE_URL . "views/forgot_password.php");
            }
        }
    }
}
?>