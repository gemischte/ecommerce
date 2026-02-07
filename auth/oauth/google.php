<?php

require_once __DIR__ . '/../../core/init.php';
require __DIR__ . "/../../vendor/autoload.php";

use App\Utils\Alert;
use App\Utils\Helper;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../', '.env');
$dotenv->load();

$clientID = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
$redirectUrl = $_ENV['GOOGLE_REDIRECT_URL'];

$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUrl);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) 
{
	$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
	$client->setAccessToken($token);

	$google_oauth = new Google\Service\Oauth2($client);
	$google_account_info = $google_oauth->userinfo->get();
	$email = $google_account_info->email;
	$name = $google_account_info->name;

	$name_parts = explode(' ', $name);
	$first_name = $name_parts[0] ?? '';
	$last_name = $name_parts[1] ?? '';

	$user_id = Helper::create_uid();
	$username = explode('@', $email)[0];
	$account_registered_at = date('Y-m-d H:i:s');

	// Check if the email has already been registered
	$query = "SELECT * FROM user_accounts WHERE email = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0){

		$row = $result->fetch_assoc();

		$set_time = date('Y-m-d H:i:s');
		$login_time = "UPDATE user_accounts SET last_login_time = ? WHERE username = ?";
		$update_stmt = $conn->prepare($login_time);
		if ($update_stmt) {

			$update_stmt->bind_param("ss", $set_time, $username);
			$update_stmt->execute();
		}

		$_SESSION['user_id'] = $row['user_id'];
		Helper::redirect_to(WEBSITE_URL . "index.php");

	} 
    else
    {

		$google_register = "INSERT INTO user_accounts (username, user_id, email, account_registered_at) 
		VALUES (?, ?, ?, ?)";
		$google_stmt = $conn->prepare($google_register);
		$google_stmt->bind_param("ssss", $username, $user_id, $email, $account_registered_at);

		if ($google_stmt->execute()) {

			$google_profile = "INSERT INTO user_profiles (user_id,first_name, last_name) 
			VALUES (?, ?, ?)";
			$profile_stmt = $conn->prepare($google_profile);
			$profile_stmt->bind_param("sss", $user_id, $first_name, $last_name);
			$profile_stmt->execute();

			// When registered is successful, auto login
			$_SESSION['user'] = $username;
			$_SESSION['user_id'] = $user_id;
            Alert::success("Success", "You have successfully registered!",
            WEBSITE_URL . "index.php");
            exit();
		} 
        else {
			Helper::write_log("Error: " . $google_stmt->error);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Google Auth</title>
</head>

<body>
	<a href="<?= $client->createAuthUrl() ?>">Google Login</a>
</body>

</html>