<?php
//Check user have the admin role
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !=1 ) {
    if (basename($_SERVER['PHP_SELF']) != 'login.php') {
        redirect_to(ADMIN_URL . "views/login.php");
    }
}