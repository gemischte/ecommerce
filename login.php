<?php
$conn = new mysqli("localhost", "root", "123456", "test");

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute the following logic only if a POST request is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure that the form fields exist
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use a prepared statement to query the database
        $sql = "SELECT password FROM register WHERE username = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $row['password'])) {
                    echo "Login successful! Welcome, " . htmlspecialchars($username) . ".";
                } else {
                    echo "Incorrect password!";
                }
            } else {
                echo "User not found!";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Failed to prepare statement: " . $conn->error;
        }
    } else {
        echo "Please enter a username and password!";
    }
}

// Close the database connection
$conn->close();
?>
