<?php
require_once __DIR__ . '/../core/init.php';
require_once __DIR__ . '/../functions/includes/mailer.php';

//all country list
$countries = all_countries();

if (isset($_POST['remove_from_cart'])) {

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "views/checkout.php", "checkout");

    remove_cart_product($_POST['product_id']);
}

if (isset($_POST['checkout'])) {

    // CSRF token validation
    ver_csrf($_POST['csrf_token'] ?? '', "views/checkout.php", "checkout");
    
    $orders_id = 'ORD' . '-' . date("Y") . '-' . date("m") . '-' . uniqid() . '-' . bin2hex(random_bytes(4));
    $country = $_POST['country'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $orders_created_at = date('Y-m-d H:i:s');
    $payment_method = $_POST['payment_method'];
    $postal_code = $_POST['postal_code'];

    $order_info = "INSERT INTO orders_info (orders_id, country, city, address, orders_created_at, payment_method, postal_code)
    VALUES(?,?,?,?,?,?,?)";

    if ($stmt = $conn->prepare($order_info)) {
        $stmt->bind_param("sssssss", $orders_id, $country, $city, $address, $orders_created_at, $payment_method, $postal_code);
        $stmt->execute();

        if (!empty($_SESSION['cart'])) {
            $user_id = $_SESSION['user_id'];

            $product_ids = implode(',', array_keys($_SESSION['cart']));
            $sql = "SELECT product_id, price FROM products WHERE product_id IN ($product_ids)";
            $result = $conn->query($sql);
            $prices = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $prices[$row['product_id']] = $row['price'];
                }
            }

            $order_details = "INSERT INTO order_details (orders_id, user_id, product_id, quantity, price)
            VALUES (?,?,?,?,?)";

            $stmt = $conn->prepare($order_details);
            if (!$stmt) {
                write_log("Prepare failed: " . $conn->error, 'ERROR');
            }

            $total_price = 0;
            $tax = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $price = isset($prices[$product_id]) ? $prices[$product_id] : 0;
                $totals = calc_cart_totals($price, $quantity);
                $sub_total = $totals['total'];
                $stmt->bind_param("ssiid", $orders_id, $user_id, $product_id, $quantity, $sub_total);
                $stmt->execute();
                update_product_stock($product_id, $quantity);
            }
        }

        if ($stmt->affected_rows > 0) {
            unset($_SESSION['cart']);
?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: "success",
                        title: "Payment successfully",
                        text: "Your order has been placed successfully.",
                        showConfirmButton: true,
                    }).then(() => {
                        window.location = "<?= WEBSITE_URL . "index.php" ?>";
                    });
                }, 100);
            </script>

        <?php
        } else {
        ?>

            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Order placed failed!",
                    showConfirmButton: true,
                }).then(() => {
                    window.location = "checkout.php";
                });
            </script>

<?php
        }
        # code...
    } else {
        write_log("Error: " . $conn->error, 'ERROR');
    }
}

?>

<?php include __DIR__ . ('/../views/includes/header.php'); ?>

<title>Checkout form</title>

<body class="bg-body-tertiary  was-validated">

    <div class="container">
        <main>
            <div class="py-5 text-center">
                <h2><?= __('Thanks you for buying our products') ?></h2>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary"><?= __('Your cart') ?></span>

                        <?php
                        if (isset($_SESSION['cart'])) {
                            $count = count($_SESSION['cart']);
                            echo "
                            <span class='badge bg-primary rounded-pill'>$count</span>
                            ";
                        } else {
                            echo "
                            <span class='badge bg-primary rounded-pill'>0</span>
                            ";
                        }
                        ?>

                    </h4>

                    <?php
                    if (!empty($_SESSION['cart'])) {

                        $product_ids = implode(',', array_keys($_SESSION['cart']));
                        $sql = "SELECT product_id, product_images,product_name,price FROM products WHERE product_id IN ($product_ids)";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                    ?>

                            <ul class='list-group mb-3'>
                                <li class='card mb-3'>
                                    <br>

                                    <?php
                                    $sub_total = 0;
                                    $total_tax = 0;

                                    while ($row = $result->fetch_assoc()) {
                                        $product_id = $row['product_id'];
                                        $product_names = $row['product_name'];
                                        $price = $row['price'];
                                        $product_images = $row['product_images'];
                                        $quantity = $_SESSION['cart'][$product_id];
                                        $totals = calc_cart_totals($price, $quantity);
                                        $sub_total += $totals['total'];
                                        $total_tax += $totals['tax'];
                                        $total_price = $totals['subtotal'];
                                    ?>


                                        <hr>
                                        <div class='card-body'>
                                            <div class='d-flex justify-content-between'>
                                                <div class='d-flex flex-row align-items-center'>
                                                    <div>
                                                        <img
                                                            src='<?= $product_images ?>'
                                                            class='img-fluid rounded-3' style='width: 100px;'>
                                                    </div>

                                                    <div class='ms-3'>
                                                        <h5><?= $product_names ?></h5>
                                                    </div>

                                                </div>
                                                <div class='d-flex flex-row align-items-center'>
                                                    <div style='width: 50px;'>
                                                        <p class='fw-normal mb-0'>*<?= $quantity ?></p>
                                                    </div>
                                                    <div style='width: 80px;'>
                                                        <p class='mb-0'>$ <?= $total_price ?></p>
                                                    </div>

                                                    <form action='checkout.php' method='post' class='d-inline'>
                                                        <input type='hidden' name='product_id' value='<?= $product_id ?>'>
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                        <button type='submit' name='remove_from_cart' class='btn btn-danger'><i class='fa-solid fa-trash'></i></button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                <li class='list-group-item d-flex justify-content-between bg-body-tertiary'>
                                    <div class='text-success'>
                                        <h6 class='my-0'><?= __('Tax') ?></h6>
                                        <small>(5%)</small>
                                    </div>
                                    <span class='text-success'><?= $total_tax ?></span>
                                </li>

                                <li class='list-group-item d-flex justify-content-between bg-body-tertiary'>
                                    <div class='text-success'>
                                        <h6 class='my-0'><?= __('Promo code') ?></h6>
                                    </div>
                                    <span class='text-success'>−$5</span>
                                </li>

                                <li class='list-group-item d-flex justify-content-between'>
                                    <span><?= __('Total') ?> (USD)</span>
                                    <strong><?= $sub_total ?></strong>
                                </li>

                            </ul>
                    <?php
                        } else {
                            echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
                        }
                    } else {
                        echo "<p>" . __('Your cart is empty') . "</p>";
                        echo "<p class='text-center'><a href='" . WEBSITE_URL . "index.php' class='btn btn-primary'>" .  __('Continue Shopping') . "</a></p>";
                    }
                    ?>

                    <form class="card p-2">
                        <div class="input-group">
                            <input type="text"
                                class="form-control"
                                placeholder="<?= __('Promo code') ?>">
                            <button type="submit" class="btn btn-secondary"><?= __('Redeem') ?></button>
                        </div>
                    </form>
                </div>


                <?php
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    $sql = "SELECT
                    up.first_name AS f_name,
                    up.last_name AS l_name,
                    up.country AS ctry,
                    up.city AS city,
                    up.address AS address,
                    up.postal_code AS p_code,
                    ua.email AS email
                    FROM user_profiles up
                    JOIN user_accounts ua ON up.user_id = ua.user_id
                    WHERE up.user_id = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                }
                ?>

                <div class="col-md-7 col-lg-8">
                    <h4 class="mb-3"><?= __('Billing address') ?></h4>
                    <form class="needs-validation" method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label for="firstName" class="form-label"><?= __('First name') ?></label>
                                <input type="text"
                                    class="form-control"
                                    id="firstName"
                                    name="firstName"
                                    placeholder=""
                                    value="<?= htmlspecialchars($row['f_name']) ?>"
                                    required>
                                <div class="invalid-feedback">
                                    <?= __('Real first name is required') ?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="lastName" class="form-label"><?= __('Last name') ?></label>
                                <input type="text"
                                    class="form-control"
                                    id="lastName"
                                    name="lastName"
                                    placeholder=""
                                    value="<?= htmlspecialchars($row['l_name']) ?>"
                                    required>
                                <div class="invalid-feedback">
                                    <?= __('Real last name is required') ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="username" class="form-label"><?= __('Email') ?></label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">@</span>
                                    <input type="text"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value="<?= htmlspecialchars($row['email']) ?>"
                                        placeholder="Email"
                                        pattern="\S+@\S+\.\S+"
                                        required>
                                    <div class="invalid-feedback"><?= __('Enter your email address') ?></div>
                                    <div class="valid-feedback"><?= __('The email is valid. You can go to email get orders information') ?></div>
                                </div>
                            </div>

                            <?php
                            if (isset($_POST['checkout'])) {
                                $email = $_POST['email'];

                                require __DIR__ . '/../vendor/autoload.php';
                                $mpdf = new \Mpdf\Mpdf();
                                $html = "<h2>Order Confirmation</h2>
                                <p>Order ID: <strong>$orders_id</strong></p>
                                <p>Shipping Country: $country,$postal_code</p>
                                <p>Shipping City: $city</p>
                                <p>Order Created At: $orders_created_at</p>
                                <p>Payment Method: $payment_method</p>";

                                $mpdf->WriteHTML($html);
                                $pdfContent = $mpdf->Output('', 'S');

                                if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                    $mail->addAddress($email);
                                    $mail->Subject = "Order Confirmation";
                                    $mail->Body = "The is your order confirmation. Please find the attached PDF for details.";
                                    $mail->addStringAttachment($pdfContent, 'order.pdf');

                                    try {
                                        $mail->send();
                                    } catch (Exception $e) {
                                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }
                                }
                            }
                            ?>

                            <div class="col-12">
                                <label for="address" class="form-label"><?= __('Address') ?></label>
                                <input type="text"
                                    class="form-control"
                                    id="address"
                                    name="address"
                                    value="<?= htmlspecialchars($row['address']) ?>"
                                    placeholder="1234 Main St"
                                    required>
                                <div class="invalid-feedback">
                                    <?= __('Please enter your shipping address') ?>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label for="country" class="form-label"><?= __('Country') ?></label>
                                <select class="form-select"
                                    id="country"
                                    name="country"
                                    required>
                                    <option value="">Choose...</option>
                                    <?php
                                    $selectedCountry = htmlspecialchars($row['ctry']);
                                    if (!empty($countries)) {
                                        foreach ($countries as $country) {
                                            $countryName = htmlspecialchars($country['name']);
                                            $selected = ($countryName === $selectedCountry) ? ' selected' : '';
                                            echo "<option value='$countryName'$selected>$countryName</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="city" class="form-label"><?= __('city') ?></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="city"
                                    name="city"
                                    value="<?= htmlspecialchars($row['city']) ?>"
                                    placeholder="<?= __('California') ?>"
                                    required>
                                <div class="invalid-feedback">
                                    <?= __('Provide a valid city') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="postal_code" class="form-label"><?= __('postal code') ?></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="postal_code"
                                    name="postal_code"
                                    value="<?= htmlspecialchars($row['p_code']) ?>"
                                    placeholder=""
                                    required>
                                <div class="invalid-feedback">
                                    <?= __('Provide a valid postal code') ?>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3"><?= __('Payment') ?> </h4>

                        <div class="col-md-3">
                            <label for="payment_method" class="form-label"><?= __('Payment method') ?></label>
                            <select class="form-select"
                                id="payment_method"
                                name="payment_method"
                                required>
                                <option selected disabled value=""><?= __('Choose') ?>...</option>
                                <option value="Credit Card"><?= __('Credit Card') ?></option>
                                <option value="PayPal">Paypal</option>
                                <option value="Bank Transfer"><?= __('Bank Transfer') ?></option>
                                <option value="Cash on delivery"><?= __('Cash on delivery') ?></option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-primary btn-lg" type="submit" name="checkout"><?= __('Continue to checkout') ?></button>
                    </form>

                    <form action="<?= WEBSITE_URL ?>functions/payment/stripe/stripe.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                        <button class="btn btn-lg btn-dark">
                            <i class="fa-solid fa-credit-card"></i>
                            <span class="ms-2 fs-6"><?= __('Debit Card or Credit Card') ?></span>
                        </button>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Footer -->
    <?php include __DIR__ . ('/../views/includes/footer.php'); ?>

</body>

</html>