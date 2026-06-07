<?php

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$dotenv->load();

try {
    $host = $_ENV["DB_HOST"] ?? "db";
    $db_user = $_ENV["DB_USER"] ?? "root";
    $db_pass = $_ENV["DB_PASS"] ?? "root_password";
    $db_name = $_ENV["DB_NAME"] ?? "ecommerce";
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception $e) {
    \App\Utils\Logger::error("Database connection failed", [
        'host' => $host,
        'user' => substr($db_user, 0, 2) . '***',
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    http_response_code(500);
    echo "This site crashed";
    exit();
}

//Set web info
define('WEBSITE_NAME', 'Tempest Shopping');
define('WEBSITE_URL', 'http://localhost:8080/');
define('ADMIN_URL', 'http://localhost:8080/dashboard/admin/');
