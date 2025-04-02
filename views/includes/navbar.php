    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>

                </div>
                <div>
                    <a class="text-reset" href="login.html" target="_blank" rel="sponsored">Login</a>
                    <div class="vr mx-2"></div>
                    <a class="text-reset" href="register.html" target="_blank">Register</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Tempest shopping</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item"><a style="display: none;" class="nav-link" href="#!">About</a></li>
                    <div class="gtranslate_wrapper"></div>

                </ul>
                <form class="d-flex" action="cart.php">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Cart
                        <?php

                        if (isset($_SESSION['cart'])) {
                            $count = count($_SESSION['cart']);
                            echo "<span class='badge bg-dark text-white ms-1 rounded-pill'>$count</span>";
                        } else {
                            echo "
                                <span class='badge bg-dark text-white ms-1 rounded-pill'>0</span>";
                        }
                        ?> </button>
                </form>

                <!-- Register & Login for mobile view -->
                <div class="d-lg-none mt-3">
                    <a class="text-reset" href="login.html" target="_blank" rel="sponsored">Login</a>
                    <div class="vr mx-2"></div>
                    <a class="text-reset" href="register.html" target="_blank">Register</a>
                </div>


            </div>
        </div>
    </nav>