<?php

require_once __DIR__ . '/../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Helper;

if($_SERVER['REQUEST_METHOD']==='POST'){
    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "views/register.php", "register");
}

if ($_POST['password'] !== $_POST['confirmPassword']) {
    Alert::error("Oops...", "Passwords do not match.",
    WEBSITE_URL . "views/register.php");
	exit();
}

$register_account = "INSERT INTO user_accounts 
(username,user_id, email, password, account_registered_at) 
VALUES (?, ?, ?,?,?)";

$user_id = Helper::create_uid();

$stmt = $conn->prepare($register_account);

if (!$stmt) {
	Helper::write_log("Prepare failed: " . $conn->error,'ERROR');
	Helper::redirect_to(WEBSITE_URL . "views/404.php");
}

$username = $_POST['username'];
$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$account_registered_at = date('Y-m-d H:i:s');
$stmt->bind_param("sssss", $username, $user_id, $email, $password, $account_registered_at);

//Check username or email is already registered
$query = "SELECT 1 FROM user_accounts WHERE username = ? OR email = ? ";
$check_stmt = $conn->prepare($query);
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    Alert::warning("Warning", "This username or email has been registered!",
    WEBSITE_URL . "views/register.php");
	exit();
}

if ($stmt->execute()) 
{

	$phone = $_POST['phone'] ?? $phone;
	$profiles = "INSERT INTO user_profiles (user_id,phone ,first_name, last_name)
	VALUES (?,?,?,?)";
	$stmt_details = $conn->prepare($profiles);
	$stmt_details->bind_param("ssss", $user_id,$phone ,$first_name, $last_name);
	$stmt_details->execute();

	// When registered is successful, auto login
	$_SESSION['user']= $username;
	$_SESSION['user_id'] = $user_id;
    Alert::success("Success", "You have successfully registered!",
    WEBSITE_URL . "index.php");
    exit();
} 
else {
	Helper::write_log("Execute failed: " . $stmt->error,'ERROR');
}

$stmt->close();
$conn->close();
?>