<?php
require_once __DIR__ . '/../core/init.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && empty($_POST['confirm'])) {
    $username = $_POST['username'];

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "delete account");

?>

    <form id="delete" method="POST" action="delete_account.php">
        <input type="hidden" name="confirm" value="true">
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    </form>

    <script>
        setTimeout(function() {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you intend to delete your account?',
                text: 'This action cannot be undone.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete My Account!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete').submit();
                    // window.location.href = 'delete_account.php?confirm=true&username=' + ('<?= $username ?>');
                } else {
                    window.history.back();
                }
            });
        }, 100);
    </script>
    <?php
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
    ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Deleted',
                        text: 'Account deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "<?= WEBSITE_URL . 'views/login.php' ?>";
                    });
                }, 100);
            </script>

        <?php
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