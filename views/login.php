<!-- UI source
https://bootstrapbrain.com/component/login-page-template-using-bootstrap-5/#code -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0">
    <title> Login Account </title>
    <link rel="icon" href="image/favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="views/assets/css/style.css">
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Scripts -->
    <script src="views/assets/js/Function.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</head>

<body>

    <noscript>
        <div class="no_js">
            <span>This site requires JavaScript.</span>
        </div>
    </noscript>

    <div>
        <nav id="navbar" class="px-4  navbar navbar-expand-md navbar-dark bg-dark shadow-sm">

            <!-- Collapse plugin for small screens -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarToggleExternalContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand" href="">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </a>

                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item "><a class="nav-link" href="http://localhost/Database/index.php">Home</a></li>

                </ul>
            </div>

            <!-- Change bg color -->
            <ul>
                <a>
                    <i style="color: gray;" id="toggleDark" onclick="Change_bg_color()" class="fa-solid fa-sun"></i>
                </a>
            </ul>
        </nav>

        <section class="py-3 py-md-5 py-xl-8">

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-5">
                            <h2 class="display-5 fw-bold text-center">Sign in</h2>
                            <p class="text-center m-0">Don't have an account? <a href="register.html" class="text-decoration-none">Sign up</a></p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="row gy-5 justify-content-center">
                            <div class="col-12 col-lg-5">

                                <form class="form-horizontal was-validated" method="POST" action="login.php">

                                    <div class="row gy-3 overflow-hidden">

                                        <!-- username table -->
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <input 
                                                placeholder="" 
                                                title="Enter your username or email" 
                                                type="text"
                                                name="username" 
                                                id="username" 
                                                class="form-control" 
                                                required
                                                pattern="[A-Za-z0-9_@.]{4,32}" 
                                                autofocus 
                                                />
                                                <label for="username" class="form-label">Username</label>
                                            </div>
                                        </div>

                                        <!-- password table -->
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" 
                                                placeholder="" 
                                                title="Enter your password"
                                                type="password" 
                                                name="password" 
                                                id="password" 
                                                required
                                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}" 
                                                />
                                                <label for="password" class="form-label">Password</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row justify-content-between">
                                                <div class="col-6">

                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check">
                                                            <input 
                                                            class="for-check-input" 
                                                            type="checkbox" 
                                                            id="remember"
                                                            name="remember" 
                                                            value="Y">
                                                            <label for="remember" class="for-check-label">Remember
                                                                Me</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-6">
                                                    <div class="text-end">
                                                        <a href="forget_password.php"
                                                            class="link-secondary text-decoration-none">Forgot
                                                            password?</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn btn-primary btn-lg" type="submit">Log in</button>
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
                                    <a href="auth/oauth/google.php" class="btn btn-lg btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                            <path
                                                d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                        </svg>
                                        <span class="ms-2 fs-6">Sign in with Google</span>
                                    </a>
                                    
                                    <!-- <a href="#!" class="btn btn-lg btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                        <span class="ms-2 fs-6">Sign in with Facebook</span>
                                    </a> -->
             
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer class="bg-dark">
        <div class="footer">
            ©2024~<span id="year"></span> Developed by gem1schte. All rights reserved.
        </div>
    </footer>

</body>

</html>
