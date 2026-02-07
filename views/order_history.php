<?php

require_once __DIR__ . '/../core/init.php';

use App\Utils\Helper;
use App\Utils\Lang;

$user_id = $_SESSION['user_id'];
if (!$user_id) {
    Helper::redirect_to("login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT 
pd.product_name,
pd.product_images,
pd.price,
pd.brand,
oi.payment_method AS pay,
oi.orders_created_at AS date,
cat.category_name AS cat_name,
od.*
FROM
order_details AS od
JOIN products AS pd ON od.product_id = pd.product_id
JOIN orders_info AS oi ON od.orders_id = oi.orders_id
JOIN category AS cat ON od.product_id = cat.product_id
WHERE od.user_id = ?
ORDER BY 
od.product_id DESC, 
od.orders_id DESC, 
od.quantity DESC, 
oi.orders_created_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    Helper::write_log("SQL prepare failed: " . $conn->error,'ERROR');
}
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders_id = $row['orders_id'];
    if (!isset($orders[$orders_id])) {
        $orders[$orders_id] = [
            'order_id' => $orders_id,
            'pay' => $row['pay'],
            'date' => $row['date'],
            'brand' => $row['brand'],
            'products' => []
        ];
    }
    $orders[$orders_id]['products'][] = [
        'product_name' => $row['product_name'],
        'product_images' => $row['product_images'],
        'brand' => $row['brand'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'cat_name' => $row['cat_name']
    ];
}
?>

<?php include __DIR__ . '/../views/includes/header.php'; ?>

<body class="bg-white">

    <div class="container py-5">
        <h1 class="text-center mb-4"><?= Lang::__('Order History') ?></h1>

        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <?php
                $total_price = 0;
                foreach ($order['products'] as $product) {
                    $total_price += $product['price']*$product['quantity'];
                }
                ?>
                <div class="card mb-4">

                    <div class="card-header bg-light">
                        <strong><?= Lang::__('Order Id') ?>:</strong> <?= htmlspecialchars($order['order_id']) ?>
                        <span class="ms-3"><strong><?= Lang::__('Payment method') ?>:</strong> <?= htmlspecialchars($order['pay']) ?></span>
                        <span class="ms-3"><strong><?= Lang::__('Total') ?>:</strong> <?= htmlspecialchars(number_format($total_price, 2)) ?></span>
                        <span class="ms-3"><strong><?= Lang::__('Date') ?>:</strong> <?= htmlspecialchars($order['date']) ?></span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th><?= Lang::__('Product image') ?></th>
                                        <th><?= Lang::__('Product name') ?></th>
                                        <th><?= Lang::__('Quantity') ?></th>
                                        <th><?= Lang::__('Price') ?>(5%)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($order['products'] as $product): ?>
                                        <tr>

                                            <td>
                                                <img src="<?= htmlspecialchars($product['product_images']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid" style="max-width: 100px;">
                                            </td>

                                            <td><?= htmlspecialchars($product['product_name']) ?>
                                                <p class="small"><strong><?= Lang::__('Brand') ?>:</strong><?= htmlspecialchars($product['brand']) ?></p>
                                                <p class="small"><strong><?= Lang::__('Category') ?>:</strong><?= htmlspecialchars($product['cat_name']) ?></p>
                                            </td>

                                            <td><?= htmlspecialchars($product['quantity']) ?></td>

                                            <td>
                                                <?php
                                                $sub_total = $product['price']*$product['quantity'];
                                                echo htmlspecialchars(number_format($sub_total, 2));
                                                ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-danger"><?= Lang::__('No orders found') ?></p>
            <div class="text-center">
                <a href="<?= WEBSITE_URL . "index.php" ?>" class="btn btn-primary">
                    <?= Lang::__('Continue Shopping') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../views/includes/footer.php'; ?>
</body>

</html>