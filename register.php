<?php
require_once 'conn.php';

// Prepare statement
$sql = "INSERT INTO register (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error); // Debugging
}

// Bind parameters
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
// $password = $_POST['password']; //Storing the password in plain text is risky and insecure
$stmt->bind_param("sss", $username, $email, $password);

//Check username or email is already registered
$query = "SELECT * FROM register WHERE username = '$username' OR email = '$email'";
$check_result = $conn->query($query);
if ($check_result->num_rows > 0){
    echo "<script>alert('This username or email has been register!'); location.href = 'register.html';</script>";
    exit();
}

// Execute the statement
if ($stmt->execute()) {
    echo "<script>alert('User registered successfully!'); location.href = 'login.html';</script>";
} 
else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
