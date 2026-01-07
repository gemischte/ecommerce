<?php
require_once __DIR__ . '/../../../core/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
?>

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
                    window.location.href = 'delete_product.php?confirm=true&product_id=<?= $product_id; ?>';
                } else {
                    window.history.back();
                }
            });
        }, 100);
    </script>

    <?php
    exit();
}
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $delete_products = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_products);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        if(!$stmt->execute()){
          write_log("Delete failed: " . $stmt->error, 'ERROR');  
        }

        if ($stmt->affected_rows > 0) {
    ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Deleted',
                        text: 'Product deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "<?= ADMIN_URL . 'index.php'; ?>";
                    });
                }, 100);
            </script>

        <?php
        } else {
        ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete the product. Please try again.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "<?= ADMIN_URL . 'index.php';?>";
                    });
                }, 100);
            </script>
<?php
        }
    }

    $stmt->close();
}

$conn->close();
?>