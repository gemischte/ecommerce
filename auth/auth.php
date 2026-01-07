<?php
require_once __DIR__ . '/../core/init.php';

$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = [
    'login.php',
    'register.php',
    'forget_password.php',
    'reset_password.php',
    'index.php',
    '404.php'
];

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {

    if (!in_array($current_page, $allowed_pages)) {
        redirect_to(WEBSITE_URL . "views/login.php");
    }
}