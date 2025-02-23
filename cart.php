<?php
session_start();
include 'includes/conn.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// 加入商品到購物車
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

// 從購物車移除商品
if (isset($_POST['remove_from_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    unset($_SESSION['cart'][$product_id]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="icon" href="image/favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>

<body class="bg-white">


    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">

                <!-- Cart Icon
                  https://www.svgviewer.dev/s/497237/cart
                   -->

                <svg fill="#000000" width="40px" height="40px" viewBox="0 0 24 24" id="cart" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                    <path id="secondary" d="M18.5,20.5A1.5,1.5,0,1,1,17,19,1.5,1.5,0,0,1,18.5,20.5ZM11,19a1.5,1.5,0,1,0,1.5,1.5A1.5,1.5,0,0,0,11,19Z" style="fill: rgb(44, 169, 188);"></path>
                    <path id="primary" d="M21.79,6.38A1,1,0,0,0,21,6H7.48L7.12,3.69A2,2,0,0,0,5.14,2H3A1,1,0,0,0,3,4H5.14L7,16.15A1,1,0,0,0,8,17h.09l11-1a1,1,0,0,0,.88-.76l2-8A1,1,0,0,0,21.79,6.38Z" style="fill: rgb(0, 0, 0);"></path>
                </svg>
                Product Cart</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>


    <div class="container py-5">
        <h1 class="text-center">Shopping Cart</h1>
        <!-- Cart Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>product_id</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        $product_ids = implode(',', array_keys($_SESSION['cart']));
                        $sql = "SELECT product_id, image_path,name, price FROM products WHERE product_id IN ($product_ids)";
                        $result = $conn->query($sql);

                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $product_id = $row['product_id'];
                                $name = htmlspecialchars($row['name']);
                                $image_path = htmlspecialchars($row['image_path']);
                                $price = number_format($row['price'], 2);
                                $quantity = $_SESSION['cart'][$product_id];
                                $total_price = number_format($row['price'] * $quantity, 2);

                                echo "<tr>
                                        <td>$product_id</td>
                                        <td>$name <img src='$image_path' alt='$name' class='rounded' width='100'></td>
                                        <td>$$price</td>
                                        <td>
                                            <input type='number' class='form-control' value='$quantity' min='1' >
                                        </td>
                                        <td>$$total_price</td>
                                        <td>
                                            <form action='cart.php' method='post' class='d-inline'>
                                                <input type='hidden' name='product_id' value='$product_id'>
                                                <button type='submit' name='remove_from_cart' class='bi bi-trash btn btn-danger'></button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
                            $tax = $subtotal * 0.10;
                            $total = $subtotal + $tax;
                            ?>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tax (10%)</span>
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
    <?php include_once 'includes/footer.php'; ?>

</body>

</html>