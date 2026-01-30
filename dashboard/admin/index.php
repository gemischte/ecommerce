<?php

require_once __DIR__ . '/../../core/init.php';

use App\Security\Csrf;

$sql = 'SELECT product_id, product_name, brand, stock, original_price, description, price, star, product_images FROM products';
$result = $conn->query($sql);

if (!$result) {
    write_log("Prepare failed: " . $conn->error, 'ERROR'); // Debugging
}
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <a href="index.php">
                <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /Manage Products</i>

        </div>
    </div>
</div>


<div class="container py-5">
    <!-- Cart Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">

                <tr>
                    <th>product_id</th>
                    <th>Product image</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Original Price</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>

            <tfoot>
                <th>product_id</th>
                <th>Product image</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Original Price</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Delete</th>
                <th>Edit</th>
            </tfoot>

            </thead>

            <tbody>

                <tr>

                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>

                <tr>
                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                    <td> <img src="<?php echo htmlspecialchars($row['product_images']); ?>" class="rounded" width="100" /> </td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['brand']) ?></td>

                    <td>
                        <?php
                            if ($row['original_price'] > 0) {
                                echo htmlspecialchars($row['original_price']);
                            } else {
                                echo "NULL";
                            }
                        ?>
                    </td>

                    <td><?= htmlspecialchars($row['price']); ?></td>
                    <td><?= htmlspecialchars($row['stock']); ?></td>

                    <!-- Delete product -->
                    <td>
                        <form action=<?= ADMIN_URL . "functions/delete_product.php?id=" . htmlspecialchars($row['product_id']) ?> method='post' class='d-inline'>
                            <?= csrf::csrf_field() ?>
                            <input type='hidden' name='product_id' value='<?= htmlspecialchars($row['product_id']); ?>'>
                            <button type='submit' name='submit' class='btn btn-danger'><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>

                    <!-- Edit product -->
                    <td>
                        <form action=<?= ADMIN_URL . "functions/edit_product.php?id=" . htmlspecialchars($row['product_id']) ?> method="GET" class="d-d-inline">
                            <input type="hidden" name='product_id' value='<?= htmlspecialchars($row['product_id']); ?>'>
                            <button type="submit" name='submit' class="btn btn-secondary"><i class="fa-solid fa-pen-to-square"></i></button>
                        </form>
                    </td>

                </tr>


        <?php }
                    }
        ?>

        </tr>

            </tbody>

        </table>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>