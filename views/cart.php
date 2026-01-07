<?php
require_once __DIR__ . '/../core/init.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "views/cart.php", "cart");
}

if (isset($_POST['add_to_cart'])) {
    add_product_to_cart($_POST['product_id'], $_POST['quantity']);
}

if (isset($_POST['remove_from_cart'])) {
    remove_cart_product($_POST['product_id']);
}

if (isset($_POST['delete_quantity'])) {
    delete_cart_qty($_POST['product_id'], $_POST['quantity']);
}
?>

<?php include __DIR__ . ('/../views/includes/header.php'); ?>

<title>Cart</title>

<body class="bg-white">

    <div class="container py-5">
        <h1 class="text-center"><?= __('Shopping Cart') ?></h1>
        <!-- Cart Table -->
        <div class="table-responsive">
            <?php
            if (!empty($_SESSION['cart'])) {
                $product_ids = implode(',', array_keys($_SESSION['cart']));
                $sql = "SELECT product_id, brand,product_images,product_name, price FROM products WHERE product_id IN ($product_ids)";
                $result = $conn->query($sql);

                if ($result) {
            ?>
                    <div class="card mb-4">
                        <div class="card-body">

                            <?php
                            while ($row = $result->fetch_assoc()):
                                $product_id = $row['product_id'];
                                $product_names = htmlspecialchars($row['product_name']);
                                $brand = htmlspecialchars($row['brand']);
                                $product_images = htmlspecialchars($row['product_images']);
                                $price = number_format($row['price'], 2);
                                $quantity = $_SESSION['cart'][$product_id];
                                $total_price = number_format($row['price'] * $quantity, 2);
                            ?>

                                <div class='row cart-item mb-3'>

                                    <div class='col-md-3'>
                                        <img src='<?= $product_images ?>' style='width: 100px;'>
                                    </div>

                                    <div class='col-md-5'>
                                        <h5 class='card-title'><?= $product_names ?></h5>
                                        <p class='text-muted'>Brand: <?= $brand ?></p>
                                    </div>

                                    <div class='col-md-2'>
                                        <form method="POST">
                                            <div class='input-group'>
                                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button class='btn btn-outline-secondary btn-sm' name="delete_quantity" value='-1' type='submit'>-</button>
                                                <input style='max-width:100px' type='text' class='form-control form-control-sm text-center quantity-input' value='<?= $quantity ?>'>
                                                <button class='btn btn-outline-secondary btn-sm' name="add_to_cart" value='1' type='submit'>+</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class='col-md-2 text-end'>
                                        <p class='fw-bold'>$ <?= $total_price ?></p>
                                        <form action='cart.php' method='POST'>
                                            <input type='hidden' name='product_id' value='<?= $product_id ?>'>
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                            <button type='submit' name='remove_from_cart' class='btn btn-sm btn-danger'>
                                                <i class='fa-solid fa-trash'></i>
                                            </button>
                                        </form>
                                    </div>

                                </div>

                                <hr>

                            <?php endwhile; ?>
                        </div>
                    </div>


            <?php
                } else {
                    write_log("Prepare failed: " . $conn->error, 'ERROR'); // Debugging
                }
            } else {
                echo "<p style='user-select: none;' class='fs-1 text-center  text-danger'>" . __('Your cart is empty') . "</p>";
                echo "<p class='text-center'><a href='" . WEBSITE_URL . "index.php' class='btn btn-primary'>" .  __('Continue Shopping') . "</a></p>";
            }
            ?>
        </div>

        <!-- Cart Summary -->
        <?php if (!empty($_SESSION['cart'])) : ?>
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('Cart Summary') ?></h5>
                            <?php
                            $subtotal = 0;
                            $tax = 0;
                            $total = 0;
                            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                                $sql = "SELECT price FROM products WHERE product_id = $product_id";
                                $result = $conn->query($sql);
                                if ($result && $row = $result->fetch_assoc()) {
                                    $totals = calc_cart_totals($row['price'], $quantity);
                                    $subtotal += $totals['subtotal'];
                                    $tax += $totals['tax'];
                                    $total += $totals['total'];
                                }
                            }
                            ?>
                            <div class="d-flex justify-content-between">
                                <span><?= __('Subtotal') ?></span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><?= __('Tax') ?>(5%)</span>
                                <span>$<?php echo number_format($tax, 2); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span><?= __('Total') ?></span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>

                            <form action=<?= WEBSITE_URL . "views/checkout.php" ?> method="post">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                <button class="btn btn-primary w-100 mt-3"><?= __('Proceed to Checkout') ?></button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . ('/../views/includes/footer.php'); ?>

</body>

</html>