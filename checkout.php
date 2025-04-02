<!-- UI Source: Checkout form
 (https://getbootstrap.com/docs/5.3/examples/checkout/) 
 -->

<?php
session_start();
include_once 'views/includes/conn.php';
include_once 'views/includes/assets.php';

//all country list
$all_countries_list = "https://restcountries.com/v3.1/all";
$countries_data = file_get_contents($all_countries_list);
$countries = json_decode($countries_data, true);

if (isset($_POST['remove_from_cart'])) {
  $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
  unset($_SESSION['cart'][$product_id]);
}

if (isset($_POST['checkout'])) {
  $orders_id = 'ORD' . '-' . date("Y") . '-' . date("m") . '-' . rand(100000, 999999) . '-' . rand(100000, 999999);
  $country = $_POST['country'];
  $city = $_POST['city'];
  $address = $_POST['address'];
  $orders_created_at = date('Y-m-d H:i:s');
  $payment_method = $_POST['payment_method'];
  $postal_code = $_POST['postal_code'];

  $sql = "INSERT INTO orders_info (orders_id, country, city, address, orders_created_at, payment_method, postal_code) 
  VALUES(?,?,?,?,?,?,?)";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssss", $orders_id, $country, $city, $address, $orders_created_at, $payment_method, $postal_code);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {

      echo "
          <script>
          setTimeout(function() {
              const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                  toast.onmouseenter = Swal.stopTimer;
                  toast.onmouseleave = Swal.resumeTimer;
                  }
                  });
                  Toast.fire({
                  icon: 'success',
                  title: 'Order placed successfully'
                  })
                  }, 100);
          </script>
          ";
    } else {
      echo '
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
        ';
    }
    # code...
  } else {
    die("Error: " . $conn->error);
  }
}

?>


<?php include('views/includes/header.php'); ?>

<title>Checkout form</title>

<body class="bg-body-tertiary  was-validated">

  <div class="container">
    <main>
      <div class="py-5 text-center">
        <h2>Thanks you for buying our products</h2>
      </div>

      <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Your cart</span>

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
            $sql = "SELECT product_id, products_image,products_name,price FROM products WHERE product_id IN ($product_ids)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
          ?>

              <ul class='list-group mb-3'>
                <li class='card mb-3'>
                  <br>

                  <?php
                  $subtotal = 0;
                  while ($row = $result->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $name = $row['products_name'];
                    $price = $row['price'];
                    $image_path = $row['products_image'];
                    $quantity = $_SESSION['cart'][$product_id];
                    $total_price = $price * $quantity;
                    $tax = $total_price * 0.05;
                    $subtotal += $row['price'] * $quantity + $tax;
                  ?>


                    <hr>
                    <div class='card-body'>
                      <div class='d-flex justify-content-between'>
                        <div class='d-flex flex-row align-items-center'>
                          <div>
                            <img
                              src='<?= $image_path ?>'
                              class='img-fluid rounded-3' style='width: 100px;'>
                          </div>

                          <div class='ms-3'>
                            <h5><?= $name ?></h5>
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
                    <h6 class='my-0'>Tax </h6>
                    <small>(5%)</small>
                  </div>
                  <span class='text-success'><?= $tax ?></span>
                </li>

                <li class='list-group-item d-flex justify-content-between bg-body-tertiary'>
                  <div class='text-success'>
                    <h6 class='my-0'>Promo code</h6>
                    <small>EXAMPLECODE</small>
                  </div>
                  <span class='text-success'>−$5</span>
                </li>

                <li class='list-group-item d-flex justify-content-between'>
                  <span>Total (USD)</span>
                  <strong><?= $subtotal ?></strong>
                </li>

              </ul>
          <?php
            } else {
              echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
            }
          } else {
            echo "<p>Your cart is empty</p>";
          }
          ?>


          <form class="card p-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Promo code">
              <button type="submit" class="btn btn-secondary">Redeem</button>
            </div>
          </form>
        </div>


        <div class="col-md-7 col-lg-8">
          <h4 class="mb-3">Billing address</h4>
          <form class="needs-validation" method="post">
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="firstName" class="form-label">First name</label>
                <input type="text"
                  class="form-control"
                  id="firstName"
                  name="firstName"
                  placeholder=""
                  value=""
                  required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="lastName" class="form-label">Last name</label>
                <input type="text"
                  class="form-control"
                  id="lastName"
                  name="lastName"
                  placeholder=""
                  value=""
                  required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>

              <div class="col-12">
                <label for="username" class="form-label">Email</label>
                <div class="input-group has-validation">
                  <span class="input-group-text">@</span>
                  <input type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Email"
                    pattern="\S+@\S+\.\S+"
                    required>
                  <div class="invalid-feedback">Enter your email.</div>
                  <div class="valid-feedback">The email is valid. You can go to email get orders information.</div>
                </div>
              </div>

              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text"
                  class="form-control"
                  id="address"
                  name="address"
                  placeholder="1234 Main St"
                  required>
                <div class="invalid-feedback">
                  Please enter your shipping address.
                </div>
              </div>

              <div class="col-md-5">
                <label for="country" class="form-label">Country</label>
                <select class="form-select"
                  id="country"
                  name="country"
                  required>
                  <option value="">Choose...</option>
                  <?php
                  foreach ($countries as $country) {
                    echo "<option value='" . $country['name']['common'] . "'>" . $country['name']['common'] . "</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="city" class="form-label">city</label>
                <input
                  type="text"
                  class="form-control"
                  id="city"
                  name="city"
                  placeholder="California"
                  required>
                <div class="invalid-feedback">
                  Please provide a valid city.
                </div>
              </div>

              <div class="col-md-3">
                <label for="zip" class="form-label">Zip</label>
                <input
                  type="text"
                  class="form-control"
                  id="postal_code"
                  name="postal_code"
                  placeholder=""
                  required>
                <div class="invalid-feedback">
                  Zip code required.
                </div>
              </div>

            </div>

            <hr class="my-4">

            <h4 class="mb-3">Payment </h4>

            <div class="col-md-3">
              <label for="payment_method" class="form-label">Payment method</label>
              <select class="form-select"
                id="payment_method"
                name="payment_method"
                required>
                <option selected disabled value="">Choose...</option>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">Paypal</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cash">Cash</option>
              </select>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit" name="checkout">Continue to checkout</button>
          </form>

        </div>
      </div>
    </main>



  </div>

  <!-- Footer -->
  <?php include('views/includes/footer.php'); ?>

</body>

</html>