<?php

require_once __DIR__ . '/core/init.php';

use App\Utils\Helper;
use App\Utils\Lang;

$sql = 'SELECT product_id, product_name, original_price, 
description, brand,price, star, product_images FROM products';
$result = $conn->query($sql);

if (!$result) {
  Helper::write_log("Prepare failed: " . $conn->error,'ERROR');
}
?>

<?php include __DIR__ . ('/views/includes/header.php'); ?>

<title>Tempest Shopping</title>

<!-- Filters Sidebar -->
<div class="container py-5">
  <h4 class="mb-0"><i class="fa-solid fa-filter"></i> <?= Lang::__('Filters') ?></h4>
  <div class="row g-4">
    <div class="col-lg-3">
      <div class="filter-sidebar p-4 shadow-sm">

        <!-- Brand Filter -->
        <h5 class="mb-4"><?= Lang::__('Brand') ?></h5>
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
        
        <form id="filter-form">
          <?php foreach ($brand as $item): ?>
            <div class="form-check">
              <input class="form-check-input brand-checkbox" type="checkbox" id="brand_<?= htmlspecialchars($item) ?>" name="brands[]" value="<?= htmlspecialchars($item) ?>">
              <label class="form-check-label" for="brand_<?= htmlspecialchars($item) ?>">
                <?= htmlspecialchars($item) ?>
              </label>
            </div>
          <?php endforeach; ?>
        </form>

        <!-- Category Filter -->
        <h5 class="mb-4"><?= Lang::__('Category') ?></h5>
        <?php
        $category_sql = "SELECT DISTINCT category_name FROM category";
        $category_result = $conn->query($category_sql);
        $categories = [];

        if ($category_result->num_rows > 0) {
          while ($row = $category_result->fetch_assoc()) {
            $categories[] = $row['category_name'];
          }
        }
        ?>

        <form id="category-filter-form">
          <?php foreach ($categories as $cat): ?>
            <div class="form-check">
              <input class="form-check-input category-checkbox" type="checkbox" id="category_<?= htmlspecialchars($cat) ?>" name="categories[]" value="<?= htmlspecialchars($cat) ?>">
              <label class="form-check-label" for="category_<?= htmlspecialchars($cat) ?>">
                <?= htmlspecialchars($cat) ?>
              </label>
            </div>
          <?php endforeach; ?>
        </form>

      </div>
    </div>

    <div class="col-lg-9">
      <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
          <div id="products-container" class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col mb-5">
                  <div class="card h-100">

                    <?php if ($row['original_price'] > $row['price']): ?>
                      <div class='badge bg-dark text-white position-absolute' style='top: 0.5rem; right: 0.5rem'><?= Lang::__('Sale') ?></div>
                      <div class='badge bg-success text-white position-absolute' style='top: 0.5rem; left: 0.5rem'>
                        <?= round((($row['original_price'] - $row['price']) / $row['original_price']) * 100) ?>%
                      </div>
                    <?php endif; ?>

                    <img class="card-img-top" src="<?= htmlspecialchars($row['product_images']) ?>" alt="..." />
                    <div class="card-body p-4">
                      <div class="text-center">
                        <h5 class="fw-bolder"><?= htmlspecialchars($row['product_name']) ?></h5>

                        <div class="d-flex justify-content-center small text-warning mb-2">
                          <div><i class="fa-solid fa-star"></i><?= htmlspecialchars($row['star']) ?></div>
                        </div>

                        <?php if ($row['original_price'] > $row['price']): ?>
                          <span class="text-muted text-decoration-line-through">$<?= htmlspecialchars($row['original_price']) ?></span>
                        <?php endif; ?>
                        $<?= htmlspecialchars($row['price']) ?>

                      </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light">
                      <div class="text-center">
                        <a class="btn btn-primary btn-sm" href="<?= WEBSITE_URL . "views/view_product.php?id=" . htmlspecialchars($row['product_id']) ?>"><?= Lang::__('View products') ?></a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              
              <script>
                window.location.href = 'views/404.php';
              </script>

            <?php endif; ?>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<!-- End Filters Sidebar -->

<!-- Footer -->
<?php include __DIR__ . ('/views/includes/footer.php'); ?>

</body>

</html>