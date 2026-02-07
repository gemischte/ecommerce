<?php

require_once __DIR__ . '/../core/init.php';
require_once __DIR__ . '/includes/header.php';

use App\Utils\Lang;
?>

<title>404 Not Found</title>

<link rel="stylesheet" href="<?= WEBSITE_URL?>views/assets/css/404.css">

<main class="page-404 d-flex align-items-center justify-content-center py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8 text-center">
        <div class="page-404__code display-1 fw-bold">404</div>
        <h1 class="page-404__title h2 mt-3 mb-2"><?= Lang::__('Oops! Page not found') ?></h1>
        <p class="page-404__desc text-muted mb-4">
          <?= Lang::__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') ?>
        </p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <a class="btn btn-primary" href="<?= WEBSITE_URL ?>index.php"><?= Lang::__('Back to Home') ?></a>
          <a class="btn btn-outline-secondary" href="javascript:history.back()"><?= Lang::__('Go Back') ?></a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>

</html>