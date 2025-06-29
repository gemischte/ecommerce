<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../views/includes/header.php';
?>

<title>Register</title>

<section class="py-3 py-md-5 py-xl-8">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mb-5">
                    <h2 class="display-5 fw-bold text-center"><?= __('Register') ?></h2>
                    <p class="text-center m-0"><span id="Register_Section_Title"></span> <a href="login.php" class="text-decoration-none">Sign in</a></p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="row gy-5 justify-content-center">
                    <div class="col-12 col-lg-5">

                        <form class="form-horizontal was-validated" method="POST" action="<?= WEBSITE_URL . "auth/register.php" ?>">

                            <div class="row gy-3 overflow-hidden">

                                <!-- username table -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input
                                            class="form-control"
                                            placeholder="e.g.Trevor_313"
                                            title="<?= __('4-32 characters, allows letters, numbers, and special characters.')?>"
                                            type="text"
                                            name="username"
                                            id="username"
                                            required
                                            pattern="[A-Za-z0-9_@.]{4,32}"
                                            autofocus
                                            value="" />
                                        <label for="username" class="form-label">Username</label>
                                    </div>
                                </div>

                                <!-- Last name table -->
                                <div class="col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="last_name"
                                            name="last_name"
                                            title="<?= __('Real last name is required') ?>"
                                            placeholder="Enter list name"
                                            required>
                                        <label for="last_name" class="form-label">Last name</label>
                                    </div>
                                </div>

                                <!-- First name table -->
                                <div class="col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="first_name"
                                            name="first_name"
                                            title="<?= __('Real first name is required') ?>"
                                            placeholder="Enter First name"
                                            required>
                                        <label for="first_name" class="form-label">First name</label>
                                    </div>
                                </div>

                                <!-- Phone table -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input
                                            class="form-control"
                                            placeholder=""
                                            type="text"
                                            name="phone"
                                            title="<?= __('Real phone number is required') ?>"
                                            id="phone"
                                            required
                                            value="" />
                                        <label for="phone" class="form-label">Phone</label>
                                    </div>
                                </div>

                                <!-- Email table -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input
                                            class="form-control"
                                            placeholder="mail@example.com"
                                            title="<?= __('Enter a valid email address')?>."
                                            type="text"
                                            name="email"
                                            id="email"
                                            required
                                            pattern="\S+@\S+\.\S+"
                                            value="" />
                                        <label for="email" class="form-label">Email</label>
                                    </div>
                                </div>

                                <!-- password table -->
                                <div class="col-12">
                                    <div class="form-floating mb-3 position-relative">
                                        <input
                                            class="form-control"
                                            placeholder="e.g.Password123"
                                            title="<?= __('Password must be 6-16 characters long, with uppercase letters, lowercase letters, and numbers.') ?>"
                                            type="password"
                                            name="password"
                                            id="password"
                                            required
                                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                            value="" />
                                        <label for="password">Password</label>
                                        <button type="button"
                                            class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2"
                                            onclick="showpassword()">
                                            <i id="eyeIcon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm password table -->
                                <div class="col-12">
                                    <div class="form-floating mb-3 position-relative">
                                        <input
                                            placeholder="e.g.Password123"
                                            title="<?= __('Confirm Password') ?>"
                                            type="password"
                                            name="confirmPassword"
                                            id="confirmPassword"
                                            class="form-control"
                                            required
                                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}" />
                                        <label for="confirmPassword">Confirm Password</label>
                                        <button type="button"
                                            class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2"
                                            onclick="confirm_show_password()">
                                            <i id="confirm_password_eye_icon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Terms of Service -->
                                <div class="form-check mb-3">
                                    <input class="for-check-input" type="checkbox" id="tos_agree" required
                                        value="Y" name="tos_agree" />
                                    <?= __('I agree to the') ?>
                                    <a href="tos.php" class="clickable text-decoration-none"><?= __('Terms of Service.') ?></a>
                                </div>

                                <!-- Log in button -->
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg" type="submit"><?= __('Register') ?></button>
                                    </div>
                                </div>

                            </div>

                        </form>

                    </div>

                    <div
                        class="col-12 col-lg-2 d-flex align-items-center justify-content-center gap-3 flex-lg-column">
                        <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;">
                        </div>
                        <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
                        <div>or</div>
                        <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;">
                        </div>
                        <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
                    </div>

                    <div class="col-12 col-lg-5 d-flex align-items-center">
                        <div class="d-flex gap-3 flex-column w-100 ">

                            <a href="<?= WEBSITE_URL ?>/auth/oauth/google.php" class="btn btn-lg btn-danger" name="email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                    <path
                                        d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                </svg>
                                <span class="ms-2 fs-6"><?= __('With Google OAuth') ?></span>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php require_once __DIR__ . '/../views/includes/footer.php'; ?>

</body>

</html>