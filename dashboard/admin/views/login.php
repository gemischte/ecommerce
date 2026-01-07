<?php
require_once __DIR__ . '/../../../core/init.php';

?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col-lg-6 d-none d-lg-block bg-login-image">
                            <img src="https://img.freepik.com/premium-vector/digital-security-access-illustration-concept_644411-40.jpg?semt=ais_hybrid&w=740" alt="Logo" class="img-fluid">
                        </div>

                        <div class="col-lg-6">
                            <div class="p-5">

                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>

                                <form class="user was-validated" method="POST" action="<?= ADMIN_URL . 'auth/login.php' ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    
                                    <div class="form-group">
                                        <input type="text"
                                            name="username"
                                            class="form-control form-control-user"
                                            placeholder="Enter Username..."
                                            pattern="[A-Za-z0-9_@.]{4,32}"
                                            autofocus
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control form-control-user"
                                            placeholder="Password"
                                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}"
                                            required>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small text-reset text-decoration-none" href="<?= WEBSITE_URL . 'auth/forget_password.php' ?>">Forgot Password?</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>