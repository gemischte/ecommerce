<?php
// Check php version
define('PHP_REQUIRED_VERSION', '5.4.0');
if (version_compare(PHP_VERSION, PHP_REQUIRED_VERSION, '<')) {
    die('This site requires PHP version ' . PHP_REQUIRED_VERSION . '. Your version is: ' . PHP_VERSION);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "test";
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set website Information
define('WEBSITE_NAME', 'Tempest Shopping');
// Set website URLs
define('WEBSITE_URL', 'http://localhost/Database/');
define('ADMIN_URL', 'http://localhost/Database/dashboard/admin/');

// API URLs
$all_countries_list = "https://www.apicountries.com/countries";