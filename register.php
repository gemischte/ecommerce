<?php
// Database connection
$conn = new mysqli("localhost", "root", "123456", "test");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Execute the statement
if ($stmt->execute()) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
