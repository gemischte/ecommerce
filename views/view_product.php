<?php
require_once __DIR__ . '/../core/config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>

        <?php include __DIR__ . ('/../views/includes/header.php'); ?>

        <title>View Product</title>

        <div class="container py-5">
            <div class="row">

                <!-- Left Column -->
                <div class="col-md-6 mb-4">
                    <img src="<?= htmlspecialchars($row['product_images']); ?>" class="img-fluid product-image" alt="Product Image">
                </div>

                <!-- Right Column -->
                <div class="col-md-6 mb-4">
                    <div class="product-card">
                        <div class="card-body">

                            <h1 class="card-title"><?= htmlspecialchars($row['product_name']); ?></h1>

                            <span class="text-muted text-decoration-line-through">
                                <?php
                                if ($row['original_price'] > $row['price']) {
                                    echo "$" . htmlspecialchars($row['original_price']);
                                }
                                ?>
                            </span>

                            <span class="h4 me-2 text-primary">$<?= htmlspecialchars($row['price']); ?></span>

                            <?php
                            if ($row['original_price'] > $row['price'] && $row['original_price'] != 0) {
                                $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);
                                echo "<span class='badge bg-danger ms-2'>$discount % OFF</span>";
                            } else {
                                echo "<span class='d-none'></span>";
                            }
                            ?>

                            <?php
                            if ($row['stock'] > 10) {
                                echo "<span class='badge bg-success ms-2'>" . htmlspecialchars($row['stock']) . __('In a stock') . "</span>";
                            } elseif ($row['stock'] > 0) {
                                echo "<span class='badge bg-warning ms-2'>" . htmlspecialchars($row['stock']) . __('Almost sold out') . "</span>";
                            } else {
                                echo "<span class='badge bg-danger ms-2'>" . __('Out of Stock') . "</span>";
                            }
                            ?>

                            <div class="mb-4">
                                <strong><?= __('Description') ?>:</strong>
                                <p><?= htmlspecialchars($row['description']); ?></p>
                            </div>

                            <!-- Add to Cart Form -->
                            <form action="<?= WEBSITE_URL . "views/cart.php" ?>" method="post">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                <label class="me-2"><?= __('Quantity') ?>:</label>
                                <select name="quantity" id="quantity">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <button class="btn btn-primary flex-shrink-0"
                                    type="submit"
                                    name="add_to_cart"
                                    <?php
                                    if ($row['stock'] <= 0) {
                                        echo 'disabled';
                                    }
                                    ?>>
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <?= __('Add to cart') ?>
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include __DIR__ . ('/../views/includes/footer.php'); ?>
<?php
    } else {
        echo "
        <strong>Product not found.</strong>
        <br>
        <a href='" . WEBSITE_URL . "index.php'>Continue Shopping.</a>
        ";
    }
    $stmt->close();
} else {
    echo "No product selected.";
}

$conn->close();
?>

</body>

</html>