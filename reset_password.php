<?php
include_once 'views/includes/conn.php';
include_once 'views/includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password === $confirmPassword) {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "SELECT token, token_expiry FROM register WHERE token = ?";

            if (isset($_GET['token'])) {

                $token = $_GET['token'];
                $stmt = $conn->prepare($sql);

                if ($stmt) {

                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $db_token = $row['token'];
                        $token_expiry = $row['token_expiry'];

                        if ($token === $db_token && strtotime($token_expiry) > time()) {
                            $UPDATEsql = "UPDATE register SET password = ?,token = NULL, token_expiry = NULL WHERE token = ?";
                            $UPDATEstmt = $conn->prepare($UPDATEsql);

                            if ($UPDATEstmt) {
                                $UPDATEstmt->bind_param("ss", $password, $token);
                                if ($UPDATEstmt->execute()) {
                                    echo "
                                <script>
                                    setTimeout(function() {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: 'Your password has been successfully reset!',
                                            showConfirmButton: true,
                                        }).then(() => {
                                            window.location = 'login.html';
                                        });
                                    }, 100);
                                </script>
                                ";
                                }
                            }
                        }
                    } else {
                        echo '<div class = "error">';
                        echo ("Token not found or has expired.");
                        echo '</div>';
                    }
                }
            }
        }
    } else {
        echo "
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Reset Error:',
                    text: ' Please enter a valid password.',
                    showConfirmButton: true,
                })
            }, 100);
        </script>
        ";
    }
}
?>

<?php include('views/includes/header.php');?>

<title>Reset Password</title>

<section class="py-3 py-md-5 py-xl-8 was-validated">
    <form method="post">
        <div class="container">
            
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Reset Password</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="row gy-5 justify-content-center">
                        <div class="col-12 col-lg-5">

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" 
                                name="password" 
                                id="password" 
                                class="form-control"
                                placeholder="" 
                                required
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                title="Password must be 6-16 characters long, with uppercase letters, lowercase letters, and numbers."
                                autofocus />
                                <label for="password">Password</label>
                                <button type="button" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2" onclick="showpassword()">
                                    <i id="eyeIcon" class="fa fa-eye"></i>
                                </button>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input 
                                type="password" 
                                name="confirmPassword" 
                                id="confirmPassword" 
                                class="form-control"
                                placeholder="" 
                                required
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                title="Confirm Password" />
                                <label for="confirmPassword">Confirm Password</label>
                                <button type="button" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2" onclick="confirm_show_password()">
                                    <i id="confirm_password_eye_icon" class="fa fa-eye"></i>
                                </button>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit">Reset Password</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</section>


<!-- Footer -->
<?php include('views/includes/footer.php');?>

</body>

</html>