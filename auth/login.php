<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../views/includes/assets.php';
require_once __DIR__ . '/../functions/includes/mailer.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
                        header("Location:" . WEBSITE_URL . "index.php");
                        exit();
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
                                window.location = "<?= WEBSITE_URL . 'views/login.php' ?>";
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
                            text: "User not found!",
                            footer: "<a href=<?= WEBSITE_URL . 'views/register.php' ?>>Register a new account now?</a>"
                        }).then(() => {
                            window.location = "<?= WEBSITE_URL . 'views/login.php' ?>";
                        });
                    }, 100);
                </script>

<?php
                exit();
            }

            $stmt->close();
        } else {
            echo "Failed to prepare statement: " . $conn->error;
        }
    } else {
        echo "Please enter a username and password!";
    }
}

$conn->close();
?>