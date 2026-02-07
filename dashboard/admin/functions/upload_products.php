<?Php

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;
use App\Utils\Helper;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/admin/functions/upload_products.php", "upload products");

    if (
        isset($_POST['product_name']) &&
        isset($_POST['description']) &&
        isset($_POST['price']) &&
        isset($_POST['original_price']) &&
        isset($_POST['stock']) &&
        isset($_POST['product_images'])
    ) {

        $product_names = $_POST['product_name'];
        $description = $_POST['description'];
        $brand = $_POST['brand'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $original_price = $_POST['original_price'];
        $stock = $_POST['stock'];
        $images = $_POST['product_images'];

        $product_id = rand(1000, 99999);

        $sql = "INSERT INTO products  (product_name,product_id ,description, 
        brand,price, original_price,stock, product_images) 
         VALUES (?, ?, ?, ?, ?, ?,?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "sisssdss",
                $product_names,
                $product_id,
                $description,
                $brand,
                $price,
                $original_price,
                $stock,
                $images
            );

            if ($stmt->execute()) {

                $category_sql = "INSERT INTO category (product_id, category_name) 
                VALUES (?, ?)";
                $stmt2 = $conn->prepare($category_sql);

                if ($stmt2) {
                    $stmt2->bind_param("is", $product_id, $category);
                    $stmt2->execute();
                    $stmt2->close();
                }
                Alert::success("Success", "New product has been added successfully!");
            } 
            else {
                Alert::error("Oops...", "Product addition failed!");
            }

            $stmt->close();
        } 
        else {
            Helper::write_log("SQL prepare failed: " . $conn->error, 'ERROR');
        }
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <a href="<?= ADMIN_URL . "index.php" ?>">
                <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /New Products</i>

        </div>
    </div>
</div>

<div class="container py-5">
    <div class="card shadow-lg">

        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">New products on shelves</h2>
        </div>

        <div class="card-body">
            <form method="POST" action="upload_products.php" enctype="multipart/form-data">
                <?= csrf::csrf_field() ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="product_name"
                        maxlength="255"
                        placeholder="Enter product name"
                        required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Enter product description"
                        required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Product Price ($)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="price"
                            name="price"
                            placeholder="Enter product price"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="original_price" class="form-label">Original Price ($)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="original_price"
                            name="original_price"
                            placeholder="Enter product original price">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input
                            type="number"
                            class="form-control"
                            id="stock"
                            name="stock"
                            placeholder="Enter available quantity"
                            required>
                    </div>

                    <?php
                    $brand = 'SELECT DISTINCT brand FROM products';
                    $result = $conn->query($brand);
                    $brand_list = [];

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $brand_list[] = $row['brand'];
                        }
                    }
                    ?>
                    <div class="col-md-6 mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input
                            type="text"
                            class="form-control"
                            id="brand"
                            name="brand"
                            maxlength="20"
                            placeholder="Enter Brand"
                            list="brand_list">
                        <datalist id="brand_list">
                            <?php
                            foreach ($brand_list as $brand_item): ?>
                                <option value="<?= htmlspecialchars($brand_item) ?>">
                                <?php endforeach;
                                ?>
                        </datalist>
                    </div>

                    <?php
                    $category = "SELECT DISTINCT category_name FROM category";
                    $result = $conn->query($category);
                    $category_list = [];

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $category_list[] = $row['category_name'];
                        }
                    }
                    ?>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">category</label>
                        <input
                            type="text"
                            class="form-control"
                            id="category"
                            name="category"
                            placeholder="Enter Category"
                            list="category_list">
                        <datalist id="category_list">
                            <?php
                            foreach ($category_list as $category_item): ?>
                                <option value="<?= htmlspecialchars($category_item) ?>">
                                <?php endforeach;
                                ?>
                        </datalist>
                    </div>

                </div>

                <div class="mb-3">
                    <label for="product_images" class="form-label">Image Path</label>
                    <input
                        type="text"
                        class="form-control"
                        id="product_images"
                        name="product_images"
                        placeholder="Enter image path or URL"
                        required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

        </div>

        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>