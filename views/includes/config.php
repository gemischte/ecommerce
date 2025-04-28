<?php
// Database connection
$host = "localhost";
$db_user = "root";
$db_pass ="123456";
$db_name = "test";
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$web_url = "http://localhost/Database/";
$admin_url = "http://localhost/Database/dashboard/admin/";
?>