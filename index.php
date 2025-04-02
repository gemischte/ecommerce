<!-- UI Source: startbootstrap-shop-homepage
 (https://github.com/StartBootstrap/startbootstrap-shop-homepage) 
 -->

<?php
session_start();
include 'views/includes/conn.php';
$sql = 'SELECT product_id, products_name, original_price,description,brand,price, product_star,products_image FROM products';
$result = $conn->query($sql);

if (!$result) {
  die("Prepare failed: " . $conn->error); // Debugging
}
?>

<?php include('views/includes/header.php'); ?>

<title>Tempest shopping</title>

<!-- Start carousel -->
<div id="carouselExampleCaptions" class="carousel slide" style="user-select: none;">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/graphic-cards/50-series/rtx-5090/geforce-rtx-5090-bm-xl770-d.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Nvidia Rtx 5060</h5>
        <p>The Nvidia GeForce RTX 5060 is a mid-range desktop graphics card utilizing the GB206 chip based on the Blackwell architecture. The 5060 offers 8 GB GDDR7 graphics memory with a 128-bit memory bus.</p>
      </div>
    </div>
    <div class="carousel-item mx-auto col-md-8 col-lg-6 order-lg-last">
      <img src="https://i.marieclaire.com.tw/assets/mc/202409/66DF5472DDB321725912178.jpeg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Apple Watch Series 10</h5>
        <p>Wide‑angle OLED,All‑day battery life, up to 18 hours of normal use6,Up to 36 hours in Low Power Mode6</p>
      </div>
    </div>

  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<!-- End carousel -->

<!-- Filters Sidebar -->
<?php
$brand = 'SELECT DISTINCT brand FROM products';
$brand_result = $conn->query($brand);
$brand = [];

if ($brand_result->num_rows > 0) {
  while ($row = $brand_result->fetch_assoc()) {
    $brand[] = $row['brand'];
  }
}
?>
<div class="container py-5">
<h4 class="mb-0">Product Collection</h4>
  <div class="row g-4">
    <div class="col-lg-3">
      <div class="filter-sidebar p-4 shadow-sm">
        <div class="filter-group">
          <h6 class="mb-3">Categories</h6>
          <div class="form-check mb-2">

            <label class="form-check-label" for="electronics">
              <?PHP

              foreach ($brand as $item) {
                echo "
                <input class='form-check-input' type='checkbox' id='electronics'>";
                echo htmlspecialchars($item) . "<br>";
              }
              ?>

            </label>
          </div>

        </div>

        <div class="filter-group">
          <h6 class="mb-3">Price Range</h6>
          <input type="range" class="form-range" min="0" max="1000" value="500">
          <div class="d-flex justify-content-between">
            <span class="text-muted">$0</span>
            <span class="text-muted">$1000</span>
          </div>
        </div>

        <div class="filter-group">
          <h6 class="mb-3">Rating</h6>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="rating" id="rating4">
            <label class="form-check-label" for="rating4">
              <i class="bi bi-star-fill text-warning"></i> 4 & above
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="rating" id="rating3">
            <label class="form-check-label" for="rating3">
              <i class="bi bi-star-fill text-warning"></i> 3 & above
            </label>
          </div>
        </div>

        <button class="btn btn-outline-primary w-100">Apply Filters</button>
      </div>
    </div>
  </div>
</div>
<!-- End Filters Sidebar -->

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
                    <div class=""><i class="fa-solid fa-star"></i><?php echo htmlspecialchars($row['product_star']); ?></div>
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
              <div class="card-footer d-flex justify-content-between bg-light">
                <div class="text-center"><a class="btn btn-primary btn-sm" href="view_product.php?id=<?php echo htmlspecialchars($row['product_id']); ?>">View products</a></div>
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
<?php include('views/includes/footer.php'); ?>

</body>

</html>