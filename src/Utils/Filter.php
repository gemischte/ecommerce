<?php

namespace Utils\Filter;

use mysqli;

require_once __DIR__ . '/../../core/init.php';

class Filter
{
    public static function render_products($result)
    {
        if ($result && $result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc()) 
            {
                echo "<div class='col mb-5'>";
                echo "<div class='card h-100'>";
                if ($row['original_price'] > $row['price'])
                {
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
        } 
        else {
            echo "<div class='col-12 text-danger'><p>No products found.</p></div>";
        }
    }

    public static function bind_params_dynamic($stmt, $params)
    {
        $types = str_repeat('s', count($params));
        $refs = [];
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }
        array_unshift($refs, $types);
        return call_user_func_array([$stmt, 'bind_param'], $refs);
    }

}

class ProductFilterService
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }
    
    public function filter_products()
    {
        $brands = isset($_POST['brands']) ? json_decode($_POST['brands'], true) : [];
        $categories = isset($_POST['categories']) ? json_decode($_POST['categories'], true) : [];

        $sql = "SELECT DISTINCT 
        p.product_id,
        p.product_name,
        p.original_price,
        p.description,
        p.brand,
        p.price,
        p.star,
        p.product_images
        FROM products p ";

        $params = [];
        $conditions = [];

        // Category filter requires extra join
        if (!empty($categories))
        {
            $sql .= "JOIN category c ON p.product_id = c.product_id ";
            $placeholders_cat = implode(',', array_fill(0, count($categories), '?'));
            $conditions[] = "c.category_name IN ($placeholders_cat)";
            $params = array_merge($params, $categories);
        }

        if (!empty($brands))
        {
            $placeholders_brand = implode(',', array_fill(0, count($brands), '?'));
            $conditions[] = "p.brand IN ($placeholders_brand)";
            $params = array_merge($params, $brands);
        }

        if (!empty($conditions)) {
            $sql .= "WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            write_log("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params))
        {
            if (Filter::bind_params_dynamic($stmt, $params)) {
                write_log("Binding parameters failed: " . $stmt->error);
            }
        }

        $stmt->execute();
        $result = $stmt->get_result();
        Filter::render_products($result);
        exit;
    }
    
}

$service = new ProductFilterService($conn);
$service->filter_products();
exit;