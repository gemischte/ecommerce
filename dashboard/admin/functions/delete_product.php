<?php

use App\Utils\Alert;

require_once __DIR__ . '/../../../core/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && empty($_POST['confirm'])) {
    $product_id = $_POST['product_id'];

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/index.php", "delete product");
?>

    <form id="delete_product?id=<?= $product_id ?>" method="POST" action="delete_product.php">
        <input type="hidden" name="confirm" value="true">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    </form>

    <?php
    Alert::warning(
        "Warning",
        "This cannot be undone.",
        null,
        ["showCancelButton" => true, "submitId" => "delete_product?id=" . $product_id]
    );
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $delete_products = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_products);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        if (!$stmt->execute()) {
            write_log("Delete failed: " . $stmt->error, 'ERROR');
        }

        if ($stmt->affected_rows > 0) {
            Alert::success(
                "Success",
                "Product deleted successfully.",
                ADMIN_URL . "index.php"
            );
            exit();

        } else {
            Alert::error(
                "Oops...",
                "Failed to delete the product. Please try again.",
                ADMIN_URL . "index.php"
            );
            
        }
    }

    $stmt->close();
}

$conn->close();
?>