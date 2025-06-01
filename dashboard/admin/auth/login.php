<?php
require_once __DIR__ . '/../../../core/config.php';
require_once __DIR__ . '/../../../views/includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

                    // Check if the user is an admin(admin_role = 1)
                    if ($row['admin_role'] == 1) {
                        $_SESSION['user'] = $username;
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['admin_role'] = $row['admin_role'];

                        header("Location: " . ADMIN_URL . "index.php");
                        exit();
                    } else {
?>
                        <script>
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'You are not an admin!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location = "<?= ADMIN_URL . 'views/login.php' ?>";
                                });
                            }, 100);
                        </script>
                        
                    <?php
                    }
                } else {
                    ?>

                    <script>
                        setTimeout(function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Incorrect password!",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location = "<?= ADMIN_URL . 'views/login.php' ?>";
                            });
                        }, 100);
                    </script>

                <?php
                }
            } else {
                ?>

                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "User not found!"
                        }).then(() => {
                            window.location = "<?= ADMIN_URL . 'views/login.php' ?>";
                        });
                    }, 100);
                </script>

<?php
            }
        }
    }
}

$conn->close();
