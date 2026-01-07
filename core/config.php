<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$dotenv->load();

try{
    $host = "localhost";
    $db_user = "root";
    $db_pass = $_ENV["DB_PASS"];
    $db_name = "ecommerce";
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);
}
catch(mysqli_sql_exception $e){
    error_log("Connection failed: " . $e->getMessage());
    echo"This site crashed";
    exit();
}

//Set web info
define('WEBSITE_NAME', 'Tempest Shopping');
define('WEBSITE_URL', 'http://localhost/ecommerce/');
define('ADMIN_URL', 'http://localhost/ecommerce/dashboard/admin/');