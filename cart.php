<?php
session_start();
include 'views/includes/conn.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add product to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

    if ($product_id > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}

// Remove product from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    unset($_SESSION['cart'][$product_id]);
}
?>

<?php include('views/includes/header.php');?>

<title>Cart</title>

<body class="bg-white">

    <div class="container py-5">
        <h1 class="text-center">Shopping Cart</h1>
        <!-- Cart Table -->
        <div class="table-responsive">
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        $product_ids = implode(',', array_keys($_SESSION['cart']));
                        $sql = "SELECT product_id, brand,products_image,products_name, price FROM products WHERE product_id IN ($product_ids)";
                        $result = $conn->query($sql);

                        if ($result) {
                            echo"    <div class='card mb-4'>                            
                                    <div class='card-body'>";
                                    
                            while ($row = $result->fetch_assoc()) {
                                $product_id = $row['product_id'];
                                $name = htmlspecialchars($row['products_name']);
                                $brand = htmlspecialchars($row['brand']);
                                $image_path = htmlspecialchars($row['products_image']);
                                $price = number_format($row['price'], 2);
                                $quantity = $_SESSION['cart'][$product_id];
                                $total_price = number_format($row['price'] * $quantity, 2);

                                echo "
                                        <div class='row cart-item mb-3'>
                                            <div class='col-md-3'>
                                                <img src='{$image_path}'style='width: 100px;'>
                                            </div>
                                            <div class='col-md-5'>
                                                <h5 class='card-title'>{$name}</h5>
                                                <p class='text-muted'>Brand: {$brand}</p>
                                            </div>

                                            <div class='col-md-2'>
                                                <div class='input-group'>
                                                    <button class='btn btn-outline-secondary btn-sm' value='-1' type='button'>-</button>
                                                    <input style='max-width:100px' type='text' class='form-control  form-control-sm text-center quantity-input' value='{$quantity}'>
                                                    <button class='btn btn-outline-secondary btn-sm' value='1' type='button'>+</button>
                                                </div>
                                            </div>

                                            <div class='col-md-2 text-end'>
                                                <p class='fw-bold'>$ {$total_price}</p>

                                                <form action='cart.php' method='POST' >
                                                <input type='hidden' name='product_id' value='$product_id'>
                                                <button type='submit' name='remove_from_cart'  class='btn btn-sm btn-danger'>
                                                        <i class='fa-solid fa-trash'></i>
                                                    </button>
                                                </form>

                                            </div>
                                            
                                        </div>
                                        <hr>

                                 ";
                            }
                            echo "</div>";
                            echo "</div>";

                        } else {
                            echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
                        }
                    } else {
                        echo "<p style='user-select: none;' class='fs-1 text-center  text-danger'>Your cart is empty.</p>";
                    }
                    ?>
        </div>
            
        <!-- Cart Summary -->
        <?php if (!empty($_SESSION['cart'])) : ?>
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cart Summary</h5>
                            <?php
                            $subtotal = 0;
                            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                                $sql = "SELECT price FROM products WHERE product_id = $product_id";
                                $result = $conn->query($sql);
                                if ($result && $row = $result->fetch_assoc()) {
                                    $subtotal += $row['price'] * $quantity;
                                }
                            }
                            $tax = $subtotal * 0.05;
                            $total = $subtotal + $tax;
                            ?>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tax (5%)</span>
                                <span>$<?php echo number_format($tax, 2); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>

                            <form action="checkout.php" method="post">
                            <button class="btn btn-primary w-100 mt-3">Proceed to Checkout</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include('views/includes/footer.php');?>

</body>

</html>