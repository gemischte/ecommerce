<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../views/includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'])) {
    $username = $_POST['username'];
?>

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
                    window.location.href = 'delete_account.php?confirm=true&username=' + ('<?= $username ?>');
                } else {
                    window.history.back();
                }
            });
        }, 100);
    </script>
    <?php
    exit();
}

// Check if there is a delete confirmation request
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['username'])) {
    $username = $_GET['username'];

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
            // Unset the session variable if the deleted account is the current user
            if (isset($_SESSION['user']) && $_SESSION['user'] === $username) {
                unset($_SESSION['user']);
            }
        }
    } else {
        ?>

        <script>
            window.history.back();
        </script>

<?php
    }
    
    $stmt->close();
} else {
    echo "Failed to prepare SQL statement: " . $conn->error;
}

$conn->close();
?>