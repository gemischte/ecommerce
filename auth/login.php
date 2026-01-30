<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "views/login.php", "login");

    // Ensure that the form fields exist
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_account = "SELECT user_id,password FROM user_accounts WHERE username = ? ";
        $stmt = $conn->prepare($login_account);

        if ($stmt) {

            $stmt->bind_param("s", $username);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $row['password'])) {

                    // Update last login time                    
                    $set_time = date('Y-m-d H:i:s');
                    $login_time = "UPDATE user_accounts SET last_login_time = ? WHERE username = ?";
                    $update_stmt = $conn->prepare($login_time);
                    if ($update_stmt) {

                        $update_stmt->bind_param("ss", $set_time, $username);
                        $update_stmt->execute();

                        // Store the session data
                        $_SESSION['user'] = $username;
                        $_SESSION['user_id'] = $row['user_id'];
                        redirect_to(WEBSITE_URL . "index.php");
                    }
                } else {
                    Alert::error("Oops...", "Incorrect password!",
                    WEBSITE_URL . "views/login.php");
                    exit();
                }
            } else {
                Alert::error("Oops...", "User not found!",
                WEBSITE_URL . "views/login.php",
                ['footer'=> '<a href="' . WEBSITE_URL . 'views/register.php">Register a new account now?</a>']);
                exit();
            }

            $stmt->close();
        } else {
            write_log("Failed to prepare statement: " . $conn->error,'ERROR');
            redirect_to(WEBSITE_URL . "views/404.php");
        }
    } else {
        write_log("Username or password not set in POST request",'WARNING');
        redirect_to(WEBSITE_URL . "views/login.php");
    }
}

$conn->close();
?>