<?php
require_once __DIR__ . '/../../core/config.php';

if (isset($_POST['brands'])) {
    $brands = json_decode($_POST['brands'], true);

    if ($brands && is_array($brands) && count($brands) > 0) {
        $placeholders = implode(',', array_fill(0, count($brands), '?'));
        $sql = "SELECT product_id, product_name, original_price, description, brand, price, star, product_images FROM products WHERE brand IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($brands)), ...$brands);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT product_id, product_name, original_price, description, brand, price, star, product_images FROM products";
        $result = $conn->query($sql);
    }

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='col mb-5'>";
            echo "<div class='card h-100'>";
            if ($row['original_price'] > $row['price']) {
                echo "<div class='badge bg-dark text-white position-absolute' style='top: 0.5rem; right: 0.5rem'>Sale</div>";
                $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);
                echo "<div class='badge bg-success text-white position-absolute' style='top: 0.5rem; left: 0.5rem'>{$discount}%</div>";
            }
            echo "<img class='card-img-top' src='" . htmlspecialchars($row['product_images']) . "' alt='...' />";
            echo "<div class='card-body p-4'>";
            echo "<div class='text-center'>";
            echo "<h5 class='fw-bolder'>" . htmlspecialchars($row['product_name']) . "</h5>";
            echo "<div class='d-flex justify-content-center small text-warning mb-2'>";
            echo "<div><i class='fa-solid fa-star'></i>" . htmlspecialchars($row['star']) . "</div>";
            echo "</div>";
            if ($row['original_price'] > $row['price']) {
                echo "<span class='text-muted text-decoration-line-through'>$" . htmlspecialchars($row['original_price']) . "</span> ";
            }
            echo "$" . htmlspecialchars($row['price']);
            echo "</div></div>";
            echo "<div class='card-footer d-flex justify-content-between bg-light'>";
            echo "<div class='text-center'><a class='btn btn-primary btn-sm' href='" . WEBSITE_URL . "views/view_product.php?id=" . htmlspecialchars($row['product_id']) . "'>View products</a></div>";
            echo "</div></div></div>";
        }
    } else {
        echo "<div class='col-12'><p>No products found.</p></div>";
    }
    exit;
}
