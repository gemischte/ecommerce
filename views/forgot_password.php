<?php

/**
 * Ui Source code:
 * @link https://bootstrapbrain.com/component/bootstrap-free-forgot-password-form-snippet/#code
 */

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Lang;

if (isset($_SESSION['Swalfire'])) {
    Alert::Swalfire($_SESSION['Swalfire']);
    unset($_SESSION['Swalfire']);
}

?>

<?php require_once __DIR__ . '../../views/includes/header.php'; ?>

<title>Forgot Password</title>

<section class="py-3 py-md-5 py-xl-8 was-validated">
    <form method="post" action="<?= WEBSITE_URL . "auth/forgot_password.php" ?>">
        <?= Csrf::csrf_field() ?>

        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="mb-5">
                        <h2 class="display-5 fw-bold text-center"><?= Lang::__('Password Reset') ?></h2>
                        <p class="text-center m-0"><?= Lang::__('Provide the email address associated with your account to recover your password') ?>.</p>
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
                                        <button class="btn btn-primary btn-lg" type="submit"><?= Lang::__('Send Reset Mail') ?></button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row justify-content-between">
                                        <div class="col-6">
                                            <a href="<?= WEBSITE_URL . '/views/login.php' ?>" class="link-secondary text-decoration-none"><?= Lang::__('Login') ?></a>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <a href="<?= WEBSITE_URL . '/views/register.php' ?>" class="link-secondary text-decoration-none"><?= Lang::__('Register') ?></a>
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
                                    <span class="ms-2 fs-6"><?= Lang::__('Contact us via Email') ?></span>
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