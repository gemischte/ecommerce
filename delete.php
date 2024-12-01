<?php
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        // Display a confirmation dialog using JavaScript and perform the operation
        echo "<script>
                if (confirm('Are you sure you want to delete your account?')) {
                    // If user clicks OK, redirect to delete.php with confirmation parameter
                    window.location.href = 'delete.php?username=" . urlencode($username) . "&confirm=true';  // Redirect to the same page to perform deletion
                } else {
                    window.history.back();  // If user cancels, go back to the previous page
                }
              </script>";
    }
}

// Check if there is a delete confirmation request
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Prepare the delete query
    $sql = "DELETE FROM register WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $username);

        // Execute the query
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>
                    alert('Account deleted successfully!');
                    window.location.href = 'login.html';  // After deletion, redirect to login page
                  </script>";
        } else {
            echo "<script>
                    alert('No matching user found. Delete Failed!');
                    window.history.back();  // Go back to the previous page
                  </script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement: " . $conn->error;
    }
}

$conn->close();
?>
