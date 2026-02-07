<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Lang;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['token'])) {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "auth/reset_password.php", "reset password");

    if (!empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password === $confirmPassword) {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "SELECT token, token_expiry FROM user_accounts WHERE token = ?";

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
                            $update_password = "UPDATE user_accounts SET password = ?,
                            token = NULL, token_expiry = NULL WHERE token = ?";
                            $stmt = $conn->prepare($update_password);

                            if ($stmt) {
                                $stmt->bind_param("ss", $password, $token);
                                if ($stmt->execute()) {
                                    Alert::success("Success",
                                    "Your password has been successfully reset!",
                                    WEBSITE_URL . "views/login.php"
                                    );
                                    exit();
                                }
                            }
                        }
                    } else {
                        Alert::error("Oops...", "Token not found or has expired.", 
                        WEBSITE_URL . "auth/forget_password.php");
                        exit();
                    }
                }
            }
        } else {
            Alert::error("Oops...", "Passwords do not match.");
        }
    } else {
        Alert::error("Oops...", "Please enter a valid password.");
    }
}
?>

<?php include __DIR__ . ('/../views/includes/header.php'); ?>

<title>Reset Password</title>

<section class="py-3 py-md-5 py-xl-8 was-validated">
    <form method="post">
        <?= csrf::csrf_field() ?>
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold"><?= Lang::__('Reset Password') ?></h2>
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
                                    title="<?= Lang::__('Password must be 6-16 characters long, with uppercase letters, lowercase letters, and numbers.') ?>"
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
                                    title="<?= Lang::__('Confirm Password') ?>" />
                                <label for="confirmPassword">Confirm Password</label>
                                <button type="button" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2" onclick="confirm_show_password()">
                                    <i id="confirm_password_eye_icon" class="fa fa-eye"></i>
                                </button>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit"><?= Lang::__('Reset Password') ?></button>
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
<?php include __DIR__ . ('/../views/includes/footer.php'); ?>

</body>

</html>