<?php
require_once __DIR__ . '/../../../core/config.php';
require_once __DIR__ . '/../../../views/includes/assets.php';

$product_id = null;
$product_names = $description = $price = $original_price = $stock = $brand = $images = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_names = $row['product_name'];
            $description = $row['description'];
            $price = $row['price'];
            $original_price = $row['original_price'];
            $stock = $row['stock'];
            $brand = $row['brand'];
            $images = $row['product_images'];
        } else {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Product Not Found',
                        text: 'The requested product does not exist.',
                        showConfirmButton: true,
                    }).then(() => {
                        window.location = '<?= ADMIN_URL . 'index.php'; ?>';
                    });
                }, 100);
            </script>

            <?php
            exit();
        }
    } else {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // Validate and sanitize input data
    $product_names = $_POST['product_name'] ?? $product_names;
    $description = $_POST['description'] ?? $description;
    $brand = $_POST['brand'] ?? $brand;
    $original_price = $_POST['original_price'] ?? $original_price;
    $price = $_POST['price'] ?? $price;
    $stock = $_POST['stock'] ?? $stock;
    $images = $_POST['product_images'] ?? $images;

    $update = "UPDATE products SET product_name = ?, description = ?, price = ?, original_price = ?, stock = ?, brand = ?, product_images = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update);

    if ($stmt) {
        $stmt->bind_param(
            "ssddssss",
            $product_names,
            $description,
            $price,
            $original_price,
            $stock,
            $brand,
            $images,
            $product_id
        );
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
            ?>

                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your product has been successfully updated!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location = '<?= ADMIN_URL . 'index.php'; ?>';
                        });
                    }, 100);
                </script>
            <?php
                exit();
            }
        } else {
            ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to edit the product. Please try again.',
                        showConfirmButton: true,
                    }).then(() => {
                        window.location = '<?= ADMIN_URL . 'index.php'; ?>';
                    });
                }, 100);
            </script>
<?php
        }
    };
}
?>

<?php include __DIR__ . ('/../includes/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <a href="<?= ADMIN_URL . 'index.php'; ?>">
                <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /Edit Product</i>

        </div>
    </div>
</div>


<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Edit Product</h2>
        </div>


        <div class="card-body">
            <form method="POST" action="edit_product.php" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        value="<?= htmlspecialchars($product_names); ?>"
                        name="product_name"
                        placeholder="Enter product name"
                        required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Product Description</label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Enter product description"
                        required><?= htmlspecialchars("$description") ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Product Price ($)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="price"
                            name="price"
                            value="<?= htmlspecialchars($price); ?>"
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
                            value="<?= htmlspecialchars($original_price); ?>"
                            placeholder="Enter product original price">
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input
                            type="number"
                            class="form-control"
                            id="stock"
                            name="stock"
                            value="<?= htmlspecialchars($stock); ?>"
                            placeholder="Enter available quantity"
                            required>
                    </div>

                    <?php
                    $sql = 'SELECT DISTINCT brand FROM products';
                    $result = $conn->query($sql);
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
                            value="<?= htmlspecialchars($brand); ?>"
                            placeholder="Enter available quantity"
                            list="brand_list">

                        <datalist id="brand_list">
                            <?php foreach ($brand_list as $brand_item): ?>
                                <option value="<?= htmlspecialchars($brand_item) ?>">
                                <?php endforeach; ?>
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
                        value="<?= htmlspecialchars($images); ?>"
                        placeholder="Enter image path or URL"
                        required>
                </div>


                <div class="text-end">
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </div>

        </div>





        </form>
    </div>

</div>

<!-- Footer -->
<?php include __DIR__ . ('/../includes/footer.php'); ?>