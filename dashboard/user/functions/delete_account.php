<?php

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Helper;
use App\Utils\Logger;

if (!isset($_SESSION['user_id'])) {
    Alert::error(
        "Error",
        "Unauthorized.",
        WEBSITE_URL . "views/login.php"
    );
    Helper::redirect_to(WEBSITE_URL . "views/login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$username = $_SESSION['user'];

if ($uid === '' || $username === '') {
    Alert::error(
        "Error",
        "Unauthorized.",
        WEBSITE_URL . "views/login.php"
    );
    Helper::redirect_to(WEBSITE_URL . "views/login.php");
    exit();
}

$get_name = $_GET['username'] ?? '';

if ($get_name === '' || !hash_equals($username, $get_name)) {
    Alert::error(
        "Error",
        "Invalid request.",
        WEBSITE_URL . "dashboard/user/views/profile.php"
    );
    Helper::redirect_to(WEBSITE_URL . "dashboard/user/views/profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_POST['confirm'])) {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "delete account");

    if (isset($_POST['username']) && !hash_equals($username, $_POST['username'])) {
        Alert::error(
            "Error",
            "Invalid request.",
            WEBSITE_URL . "dashboard/user/views/profile.php"
        );
        Helper::redirect_to(WEBSITE_URL . "dashboard/user/views/profile.php");
        exit();
    }

    Alert::warning(
        "Warning",
        "This cannot be undone.",
        null,
        ["showCancelButton" => true, "submitId" => "delete_account?username=" . $username, "confirmSubmitId" => "delete-account-confirm-submit"]
    );
    Helper::redirect_to(WEBSITE_URL . "dashboard/user/views/profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true') {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "delete account");

    if (!isset($_SESSION['user_id'])) {
        Alert::error(
            "Error",
            "Unauthorized.",
            WEBSITE_URL . "dashboard/user/views/profile.php"
        );
        Helper::redirect_to(WEBSITE_URL . "dashboard/user/views/profile.php");
        exit();
    }

    $delete_account = "DELETE FROM user_accounts WHERE user_id = ?";
    $stmt = $conn->prepare($delete_account);

    if ($stmt) {

        $stmt->bind_param("s", $uid);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            Alert::success(
                "Success",
                "Account deleted successfully.",
                WEBSITE_URL . "views/login.php"
            );

            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $uid) {
                unset($_SESSION['user_id']);
                unset($_SESSION['user']);

                Helper::redirect_to(WEBSITE_URL . "views/login.php");
                exit();
            } else {
                Alert::error(
                    "Error",
                    "Account could not be deleted.",
                    WEBSITE_URL . "dashboard/user/views/profile.php"
                );
                Helper::redirect_to(WEBSITE_URL . "dashboard/user/views/profile.php");
                exit();
            }
        }
    } else {
        Logger::error("Delete account prepare failed", [
            'sql' => $delete_account,
            'error' => $conn->error ?: 'Unknown mysqli error',
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
