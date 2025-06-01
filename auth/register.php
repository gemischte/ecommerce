<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../views/includes/assets.php';

if ($_POST['password'] !== $_POST['confirmPassword']) {
?>
	<script>
		setTimeout(function() {
			Swal.fire({
				icon: "error",
				title: "Password Error!",
				text: "Passwords do not match.",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location = "<?= WEBSITE_URL . "views/register.php"; ?>";
			});
		}, 100);
	</script>
<?php
	exit();
}

$register_account = "INSERT INTO user_accounts 
(username,user_id, email, password, account_registered_at) 
VALUES (?, ?, ?,?,?)";

// Randomly generates a number between 8 and 19 digits
$user_id = rand(10000000, 9223372036854775807);
$stmt = $conn->prepare($register_account);

if (!$stmt) {
	die("Prepare failed: " . $conn->error); // Debugging
}

$username = $_POST['username'];
$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$account_registered_at = date('Y-m-d H:i:s');
$stmt->bind_param("sssss", $username, $user_id, $email, $password, $account_registered_at);

//Check username or email is already registered
$query = "SELECT 1 FROM user_accounts WHERE username = '$username' OR email = '$email'";
$check_result = $conn->query($query);
if ($check_result->num_rows > 0) {
?>

	<script>
		setTimeout(function() {
			Swal.fire({
				icon: "warning",
				title: "Warning!",
				text: "This username or email has been registered!",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location = "<?= WEBSITE_URL . "views/register.php"; ?>";
			});
		}, 100);
	</script>
<?php
	exit();
}

if ($stmt->execute()) {

	$phone = $_POST['phone'] ?? $phone;
	$profiles = "INSERT INTO user_profiles (user_id,phone ,first_name, last_name)
	VALUES (?,?,?,?)";
	$stmt_details = $conn->prepare($profiles);
	$stmt_details->bind_param("ssss", $user_id,$phone ,$first_name, $last_name);
	$stmt_details->execute();
	
?>

	<script>
		setTimeout(function() {
			Swal.fire({
				icon: "success",
				title: "Nice to meet you!",
				text: "You have successfully registered!",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location = "<?= WEBSITE_URL . "views/login.php"; ?>";
			});
		}, 100);
	</script>

<?php
} else {
	echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>