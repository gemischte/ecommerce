<?php
require_once 'conn.php';

// Execute the following logic only if a POST request is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure that the form fields exist
    if (isset($_POST['username_email']) && isset($_POST['password'])) {
        $username_email = $_POST['username_email'];
        $password = $_POST['password'];

        // Use a prepared statement to query the database
        $sql = "SELECT password FROM register WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $username_email, $username_email);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $row['password'])) {
                    echo "Login successful! Welcome, " . htmlspecialchars($username_email) . ".</p>";

                    //logout Account
                    echo '<form method="POST" action="logout.php">';
                    echo '<button type="submit">logout</button>';
                    echo '</form>';

                    //Delete Account
                    echo '<form method="POST" action="delete.php">';
                    echo '<input type="hidden" name="username" value="' . htmlspecialchars($username_email) . '">'; // 傳遞 username
                    echo '<button type="submit">Delete Account</button>';
                    echo '</form>';


                    echo '<img src="image/programmer_meme.jpg" title="This meme describes a programmer’s daily struggle with errors...">';
                } else {
                    // echo '<script>alert("Incorrect password!")</script>';
                    echo "<script>alert('Incorrect password!'); location.href = 'login.html';</script>";

                    exit();
                }
            } else {
                // echo '<script>alert("User not found!")</script>';
                echo "<script>alert('User not found!'); location.href = 'login.html';</script>";

                exit();                    
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
// include 'logout.php';
// include 'delete.php';
?>