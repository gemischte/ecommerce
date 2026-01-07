<?php
require_once __DIR__ . '/../../../core/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['submit'])) {
    $userid = $_POST['user_id'];

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/views/user_accounts.php", "delete user");
?>

    <form id="delete" method="POST" action="delete_user.php">
        <input type="hidden" name="confirm" value="true">
        <input type="hidden" name="userid" value="<?= htmlspecialchars($userid) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    </form>

    <script>
        setTimeout(function() {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this account?',
                text: 'This action cannot be undone.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete').submit();
                    // window.location.href = 'delete_user.php?confirm=true&userid=<?= $userid; ?>';
                } else {
                    window.history.back();
                }
            });
        }, 100);
    </script>

    <?php
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true' && isset($_POST['userid'])) {

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/views/user_accounts.php", "delete user");

    $userid = $_POST['userid'];
    $delete_user = "DELETE FROM user_accounts WHERE user_id = ?";
    $stmt = $conn->prepare($delete_user);

    if ($stmt) {
        $stmt->bind_param("s", $userid);
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
                        window.location.href = "<?= ADMIN_URL . 'views/user_accounts.php'; ?>";
                    });
                }, 100);
            </script>

        <?php
        } else {
        ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete the account. Please try again.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "<?= ADMIN_URL . 'views/user_accounts.php'; ?>";
                    });
                }, 100);
            </script>
<?php
        }
        $stmt->close();
    }
}

$conn->close();
?>