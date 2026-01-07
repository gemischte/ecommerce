<nav class="navbar navbar-expand-lg px-3 mb-3 bg-light">

    <div class="container-fluid">

        <a class="navbar-brand" href="<?= WEBSITE_URL ?>index.php">
            <img src="<?= WEBSITE_URL ?>images/favicon.ico" class="logo" alt="">
            <?= WEBSITE_NAME ?>
        </a>

        <!-- Offcanvas Toggle Button -->
        <button class="navbar-toggler d-lg-none p-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

            <div class="offcanvas-header px-4 pb-0">
                <a class="navbar-brand" href="<?= WEBSITE_URL ?>index.php">
                    <img src="<?= WEBSITE_URL ?>images/favicon.ico" class="logo" alt="">
                    <?= WEBSITE_NAME ?>
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end align-items-center flex-grow-1 pe-3">

                    <li class="nav-item"><a class="nav-link active me-4" href="<?= WEBSITE_URL ?>index.php"><?= __('Home') ?></a></li>

                    <!-- Translation dropdown -->
                    <li class="nav-item dropdown me-4">
                        <button class="btn btn-light dropdown-toggle" type="button" id="lang_dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-globe"></i>
                            <?= __('Language') ?>
                        </button>
                        <ul class="dropdown-menu bg-white" aria-labelledby="lang_dropdown">
                            <li><a class="text-reset text-decoration-none" href="<?= select_lang('en-us'); ?>"><?= __('English(USA)') ?></a></li>
                            <li><a class="text-reset text-decoration-none" href="<?= select_lang('zh-tw'); ?>"><?= __('Chinese(Traditional)') ?></a></li>
                            <li><a class="text-reset text-decoration-none" href="<?= select_lang('zh-cn'); ?>"><?= __('Chinese(Simplified)') ?></a></li>
                        </ul>
                    </li>

                    <!-- User dropdown -->
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION['user'])) : ?>
                                <button class="btn btn-light dropdown-toggle" type="button" id="user_dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                    <?= $_SESSION['user'] ?>
                                </button>
                                <ul class="dropdown-menu dropdown-content bg-white" id="dropdown-list" aria-labelledby="user_dropdown">
                                    <li><a class="text-reset text-decoration-none" href="<?= WEBSITE_URL . "views/order_history.php" ?>"><?= __('Order History') ?></a></li>
                                    <li><a class="text-reset text-decoration-none" href="<?= WEBSITE_URL . "dashboard/user/views/profile.php" ?>"><?= __('Profile') ?></a></li>
                                    <li><hr class="dropdown-divider"></li>        
                                    <li><a class="text-danger text-decoration-none" href="<?= WEBSITE_URL . "auth/logout.php" ?>"><?= __('Logout') ?></a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <a class="text-decoration-none text-reset" href="<?= WEBSITE_URL . "views/login.php" ?>" rel="sponsored"><?= __('Login') ?></a>
                            <div class="vr mx-2"></div>
                            <a class="text-decoration-none text-reset" href="<?= WEBSITE_URL . "views/register.php" ?>"><?= __('Register') ?></a>
                        <?php endif; ?>
                    </li>

                    <!-- cart -->
                    <li class="nav-item">
                        <form class="position-relative" action="<?= WEBSITE_URL ?>views/cart.php">
                            <button class="btn border-0 bg-transparent position-relative" type="submit">
                                <i class="fa-solid fa-cart-shopping fa-lg text-dark"></i>
                                <?php
                                if (isset($_SESSION['cart'])) {
                                    $count = count($_SESSION['cart']);
                                    echo "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small'>$count</span>";
                                } else {
                                    echo "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small'>0</span>";    
                                }
                                ?>
                            </button>
                        </form>
                    </li>

                </ul>

            </div>


        </div>

    </div>
</nav>