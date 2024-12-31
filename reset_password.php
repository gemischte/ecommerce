<?php
include_once 'includes/conn.php';
include_once 'includes/assets.php';

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
                    }
                    else 
                    {
                        echo '<div class = "error">';
                        echo("Token not found or has expired.");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link rel="icon" href="image/favicon.ico">
    <!-- Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Scripts -->
    <script src="js/Function.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>

<body class="bg-secondary was-validated">

    <noscript>
        <div class="no_js">
            <span>This site requires JavaScript.</span>
        </div>
    </noscript>

    <main class="container mt-5">
        <form method="post">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 col-sm-8">
                    <div class="card text-white bg-dark">
                        <div class="card-header text-center fw-bold">Reset Password</div>
                        <div class="mb-3">

                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-sm">
                                <input
                                    placeholder="e.g.Password123"
                                    title="Password must be 6-16 characters long, with uppercase letters, lowercase letters, and numbers."
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    required
                                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                    autofocus />
                                <button type="button" class="btn btn-light" onclick="showpassword()">
                                    <i id="eyeIcon" class="fa fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">Valid password.</div>
                                <div class="valid-feedback">Please enter a valid password.</div>
                            </div>

                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group input-group-sm">
                                <input
                                    placeholder="e.g.Password123"
                                    title="Enter Confirm Password"
                                    type="password"
                                    name="confirmPassword"
                                    id="confirmPassword"
                                    class="form-control"
                                    required
                                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                    autofocus />
                                <button type="button" class="btn btn-light" onclick="confirm_show_password()">
                                    <i id="confirm_password_eye_icon" class="fa fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">Valid password.</div>
                                <div class="valid-feedback">Please enter a valid password.</div>
                            </div>

                        </div>

                        <!-- submit button -->
                        <div class="align-items-center d-flex justify-content-center">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <?php include_once 'includes/footer.php';?>

</body>

</html>