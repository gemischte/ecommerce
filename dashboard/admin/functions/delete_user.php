<?php

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['submit'])) {
    $userid = $_POST['user_id'];

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/views/user_accounts.php", "delete user");
?>

    <form id="delete_user_account?id=<?= $userid ?>" method="POST" action="delete_user.php">
        <input type="hidden" name="confirm" value="true">
        <input type="hidden" name="userid" value="<?= htmlspecialchars($userid) ?>">
        <?= csrf::csrf_field() ?>
    </form>

    <?php
    Alert::warning(
        "Warning",
        "This cannot be undone.",
        null,
        ["showCancelButton" => true, "submitId" => "delete_user_account?id=" . $userid]
    );
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true' && isset($_POST['userid'])) {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/views/user_accounts.php", "delete user");

    $userid = $_POST['userid'];
    $delete_user = "DELETE FROM user_accounts WHERE user_id = ?";
    $stmt = $conn->prepare($delete_user);

    if ($stmt) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            Alert::success(
                "Success",
                "Account deleted successfully.",
                ADMIN_URL . "views/user_accounts.php"
            );
            exit();

        } else {
            Alert::error(
                "Oops...",
                "Failed to delete the account. Please try again.",
                ADMIN_URL . "views/user_accounts.php"
            );
            exit();
            
        }
        $stmt->close();
    }
}

$conn->close();
?>