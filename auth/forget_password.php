<!-- Ui Source code:
https://bootstrapbrain.com/component/bootstrap-free-forgot-password-form-snippet/#code 
-->
<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Services\Mail;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "auth/forget_password.php", "forget password");

    //32 length token
    $token = bin2hex(random_bytes(32));

    // 30 minutes
    $token_expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    //Clean expiry token
    $cleanup_expiry_token = "UPDATE user_accounts SET token = NULL, 
    token_expiry = NULL WHERE token_expiry <= NOW()";
    $conn->query($cleanup_expiry_token);

    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = htmlspecialchars(trim($_POST['email']));
        $sql = "SELECT email,username FROM user_accounts WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $reset_password = "UPDATE user_accounts SET token = ?, 
                token_expiry = ? WHERE email = ?";
                $stmt = $conn->prepare($reset_password);

                if ($stmt) {
                    $username = $row['username'];
                    $stmt->bind_param("sss", $token, $token_expiry, $email);
                    $stmt->execute();

                    //Send mail
                    $reset_url = WEBSITE_URL . "auth/reset_password.php?token={$token}";
                    Mail::send($email, "Reset password", "<p>Hello {$username}</p> Click the link: <a href='$reset_url'>reset password</a>");
                    Alert::success("Success","An email has been sent to $email,with instructions to reset your password.");
                }
            } else {
                Alert::error("Oops...", "Email not found or invalid.");
            }
        }
    }
}
?>

<?php include __DIR__ . ('/../views/includes/header.php'); ?>

<title>Forget Password</title>

<section class="py-3 py-md-5 py-xl-8 was-validated">
    <form method="post">
        <?= csrf::csrf_field() ?>

        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="mb-5">
                        <h2 class="display-5 fw-bold text-center"><?= __('Password Reset') ?></h2>
                        <p class="text-center m-0"><?= __('Provide the email address associated with your account to recover your password') ?>.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="row gy-5 justify-content-center">
                        <div class="col-12 col-lg-5">

                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input
                                            placeholder=""
                                            title="mail@example.com"
                                            type="text"
                                            name="email"
                                            id="email"
                                            class="form-control"
                                            required
                                            pattern="\S+@\S+\.\S+"
                                            autofocus />
                                        <label for="email" class="form-label">Email</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg" type="submit"><?= __('Send Reset Mail') ?></button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row justify-content-between">
                                        <div class="col-6">
                                            <a href="<?= WEBSITE_URL . '/views/login.php' ?>" class="link-secondary text-decoration-none"><?= __('Login') ?></a>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <a href="<?= WEBSITE_URL . '/views/register.php' ?>" class="link-secondary text-decoration-none"><?= __('Register') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-2 d-flex align-items-center justify-content-center gap-3 flex-lg-column">
                            <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;"></div>
                            <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
                            <div>or</div>
                            <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;"></div>
                            <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
                        </div>

                        <div class="col-12 col-lg-5 d-flex align-items-center">
                            <div class="d-flex gap-3 flex-column w-100 ">
                                <a href="mailto:mail@example.com" class="btn btn-lg btn-danger">
                                    <i class="fa-solid fa-envelope"></i>
                                    <span class="ms-2 fs-6"><?= __('Contact us via Email') ?></span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>
</section>

<!-- Footer -->
<?php include __DIR__ . ('/../views/includes/footer.php'); ?>

</body>

</html>