<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= ADMIN_URL . "index.php" ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="sidebar-brand-text mx-3">Admin <sup>Pages</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        View Interface
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= ADMIN_URL . "index.php" ?>">
            <i class="fa-solid fa-gauge"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Action Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products"
            aria-expanded="false" aria-controls="products">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
        </a>
        <div id="products" class="collapse" aria-labelledby="products" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Select:</h6>
                <a class="collapse-item" href="<?= ADMIN_URL . "functions/upload_products.php" ?>">New products</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#permissions"
            aria-expanded="false" aria-controls="permissions">
            <i class="fa-solid fa-lock"></i>
            <span>Permissions</span>
        </a>
        <div id="permissions" class="collapse" aria-labelledby="permissions" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Select:</h6>
                <a class="collapse-item" href="<?= ADMIN_URL . "views/user_accounts.php"?>">User Account</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->