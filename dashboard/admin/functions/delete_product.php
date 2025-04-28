<?php
require_once '../../../views/includes/config.php';
require_once '../../../views/includes/assets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {


    $product_id = $_POST['product_id'];

    if ($product_id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "
                <script>
                   setTimeout(function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Are you sure you want to delete this product?',
                            text: 'This action cannot be undone.',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            Swal.fire({ 
                                icon: 'success',
                                title: 'Product Deleted',
                                text: 'The product has been deleted from the database.',
                            }).then(() => {
                                window.location.href = '../../../dashboard/admin/index.php';
                            });
                            } else {
                                window.history.back();
                            }
                        });
                    }, 100);
                </script>
                ";
            }

            $stmt->close();
        }
    }
}
