<?php

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Helper;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/views/login.php", "admin login");

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $admin_login = "SELECT user_id, password, admin_role FROM user_accounts WHERE username = ?";
        $stmt = $conn->prepare($admin_login);

        if ($stmt) {

            $stmt->bind_param("s", $username);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {

                    // Check if the user is an admin
                    if ($row['admin_role'] == 1) {
                        $_SESSION['user'] = $username;
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['admin_role'] = $row['admin_role'];
                        
                        Helper::redirect_to(ADMIN_URL . "index.php");
                        
                    } else {
                        Alert::error("Oops...", "You are not an admin!",
                        ADMIN_URL . "views/login.php");
                        exit();
                    }
                } else {
                    Alert::error("Oops...", "Incorrect password!", ADMIN_URL . "views/login.php");
                    exit();
                }
            } else {
                Alert::error("Oops...", "User not found!", ADMIN_URL . "views/login.php");
                exit();
            }
        }
    }
}

$conn->close();