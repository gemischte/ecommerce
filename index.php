<!-- UI Source: startbootstrap-shop-homepage
 (https://github.com/StartBootstrap/startbootstrap-shop-homepage) 
 -->

<?php
include 'includes/conn.php';
session_start();

$sql = 'SELECT product_id, products_name, original_price,description,brand,price, product_star,products_image FROM products';
$result = $conn->query($sql);

if (!$result) {
  die("Prepare failed: " . $conn->error); // Debugging
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tempest</title>
  <link rel="icon" href="image/favicon.ico">

  <!-- Styles -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

  <!-- Scripts -->
  <script src="js/Function.js"></script>
  <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">

  <noscript>
    <div class="no_js">
      <span>This site requires JavaScript.</span>
    </div>
  </noscript>

  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">

      <!--Bag Icon
    https://www.svgviewer.dev/s/500232/bag 
    -->
      <a class="navbar-brand" href="#!">
        <svg fill="#000000" width="40px" height="40px" viewBox="0 0 24 24" id="bag" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
          <path id="primary" d="M17.92,21H6.08a1,1,0,0,1-1-1.08l.85-11a1,1,0,0,1,1-.92H17.07a1,1,0,0,1,1,.92l.85,11A1,1,0,0,1,17.92,21Z" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
          <path id="secondary" d="M9,11V6a3,3,0,0,1,3-3h0a3,3,0,0,1,3,3v5" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
        </svg>
        DealNova
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="http://localhost/Database/index.php">Home</a></li>

          <!-- Dropdown -->
          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle dropbtn" type="button" id="dropdownMenuButton"
              onclick="Dropdown()">
              Brand
            </button>

            <?php
            $brand = 'SELECT brand FROM products';
            $brand_result = $conn->query($brand);
            $brand = [];

            if ($brand_result->num_rows > 0) {
              while ($row = $brand_result->fetch_assoc()) {
                $brand[] = $row['brand'];
              }
            }
            ?>

            <ul class="dropdown-menu dropdown-content" id="dropdown-list">
              <?php
              foreach ($brand as $row) {
                echo "<li><a class='dropdown-item' href='#'>" . htmlspecialchars($row) . "</a></li>";              }
              ?>
            </ul>


          </li>

        </ul>
        <form action="cart.php">
          <button class="btn btn-outline-dark" type="submit">
            <i class="bi-cart-fill me-1"></i>
            Cart
            <?php
            if (isset($_SESSION['cart'])) {
              $count = count($_SESSION['cart']);
              echo "<span class='badge bg-dark text-white ms-1 rounded-pill'>$count</span>";
            } else {
              echo "<span class='badge bg-dark text-white ms-1 rounded-pill'>0</span>";
            }
            ?>
          </button>
        </form>

        <!-- login -->
        <form class="d-flex" role="search" action="login.html" method="GET">
          <button type="submit" class="btn btn-success ms-auto">
            <i class="bi bi-person-circle"></i>
            My Account</button>
        </form>

      </div>
    </div>
  </nav>
  <!-- Header-->
  <header class="bg-dark py-5">


    </div>
    </div>
  </header>
  <!-- Section-->
  <section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
      <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) { ?>
            <div class="col mb-5">
              <div class="card h-100">

                <?php
                if ($row['original_price'] > $row['price']) {
                  echo "
                  <div class='badge bg-dark text-white position-absolute' style='top: 0.5rem; right: 0.5rem'>Sale</div>
                  ";
                }
                ?>

                <?php
                if ($row['original_price'] > $row['price']) {
                  $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);    
                  echo "
                    <div class='badge bg-success text-white position-absolute' style='top: 0.5rem; left: 0.5rem'>$discount%</div>
                  ";
                }
                ?>

                <!-- Product image-->
                <img class="card-img-top" src="<?php echo htmlspecialchars($row['products_image']); ?>" alt="..." />
                <!-- Product details-->
                <div class="card-body p-4">
                  <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder"><?php echo htmlspecialchars($row['products_name']); ?></h5>
                    <!-- Product reviews-->
                    <div class="d-flex justify-content-center small text-warning mb-2">
                      <div class="bi-star-fill"><?php echo htmlspecialchars($row['product_star']); ?></div>
                    </div>
                    
                    <!-- Product price-->
                    <span class="text-muted text-decoration-line-through">
                      <?php
                      if ($row['original_price'] > $row['price']) {
                        echo "$" . htmlspecialchars($row['original_price']);
                      };
                      ?>
                    </span>
                      
                    $<?php echo htmlspecialchars($row['price']); ?>

                  </div>
                </div>
                <!-- Product actions-->
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                  <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="view_product.php?id=<?php echo htmlspecialchars($row['product_id']); ?>">View products</a></div>
                </div>
              </div>
            </div>
        <?php }
        }
        ?> 

      </div>
    </div>
    </div>
    </div>
  </section>
  <!-- Footer -->
  <?php include_once 'includes/footer.php'; ?>

</body>

</html>