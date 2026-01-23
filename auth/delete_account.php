<?php

use App\Utils\Alert;

require_once __DIR__ . '/../core/init.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && empty($_POST['confirm'])) {
    $username = $_POST['username'];

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "delete account");

?>

    <form id="delete_account?username=<?= $username ?>" method="POST" action="delete_account.php">
        <input type="hidden" name="confirm" value="true">
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    </form>
    
    <?php
    Alert::warning(
        "Warning",
        "This cannot be undone.",
        null,
        ["showCancelButton" => true, "submitId" => "delete_account?username=" . $username]
    );
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true' && isset($_POST['username'])) {

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "delete account");

    $username = $_POST['username'];

    $delete_account = "DELETE FROM user_accounts WHERE username = ?";
    $stmt = $conn->prepare($delete_account);

    if ($stmt) {

        $stmt->bind_param("s", $username);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            Alert::success(
                "Success",
                "Account deleted successfully.",
                WEBSITE_URL . "views/login.php"
            );

            if (isset($_SESSION['user']) && $_SESSION['user'] === $username) {
                unset($_SESSION['user']);
            }
        }
    } else {
        write_log("Failed to prepare SQL statement: " . $conn->error);
        ?>

        <script>
            window.history.back();
        </script>

<?php
    }

    $stmt->close();
    $conn->close();
}
?>