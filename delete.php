<?php
require_once 'views/includes/config.php';
include_once 'views/includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        echo "
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you intend to delete your account?',
                    text: 'This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete My Account!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'delete.php?confirm=true&username=" . urlencode($username) . "';
                    } else {
                        window.history.back();
                    }
                });
            }, 100);
        </script>
        ";
    }
}

// Check if there is a delete confirmation request
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Prepare the delete query
    $delete_account = "DELETE FROM register WHERE username = ?";
    $stmt = $conn->prepare($delete_account);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $username);

        // Execute the query
        $stmt->execute();

        if ($stmt->affected_rows > 0) 
        echo "
        <script>
            window.location.href = 'login.html';   
        </script>
        ";
    } else {
        echo "
        <script>  
            window.history.back();
        </script>
        ";
    }
        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement: " . $conn->error;
    }


$conn->close();
?>
