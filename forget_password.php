<?php
require_once 'includes/conn.php';
include_once 'includes/assets.php';
include_once 'includes/mail/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // generate a unique random token of length 32
    $token = bin2hex(random_bytes(32));

    //limit 30 minutes
    $token_expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    //Clean expiry token
    $cleanup_expiry_token = "UPDATE register SET token = NULL, token_expiry = NULL WHERE token_expiry <= NOW()";
    $conn->query($cleanup_expiry_token);
    
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = htmlspecialchars(trim($_POST['email']));
        $sql = "SELECT email FROM register WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $update_sql = "UPDATE register SET token = ?, token_expiry = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param("sss", $token, $token_expiry, $email);
                    $update_stmt->execute();

                    //Send mail link
                    $mail->addAddress($email);
                    $mail->Subject = 'Reset password';
                    $mail->Body = "<p>Hello {$email}</p> Click the link: <a href='http://localhost/Database/reset_password.php?token={$token}'>reset password</a>";

                    try{
                        $mail->send();
                    }
                    catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    
                    echo "
                    <script>
                        setTimeout(function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Send email successfully!',
                                text: 'An email has been sent to {$email}，with instructions to reset your password.',
                                showConfirmButton: true,
                            })
                        }, 100);
                    </script>
                    ";
                }
            } else {
                echo "
                    <script>
                        setTimeout(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Send email failed!',
                                text: 'Email not found or invalid.',
                                showConfirmButton: true,
                            })
                        }, 100);
                    </script>
                    ";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget password</title>
    <link rel="icon" href="image/favicon.ico">
    <!-- Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group input-group-sm">
                                <input
                                    placeholder="mail@example.com"
                                    title=""
                                    type="text"
                                    name="email"
                                    id="email"
                                    class="form-control"
                                    required
                                    pattern="\S+@\S+\.\S+"
                                    autofocus />
                                <div class="invalid-feedback">Enter your registered email.</div>
                                <div class="valid-feedback">The email is valid. You can now submit to reset your password via email.</div>
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