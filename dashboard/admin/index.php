<?php
require_once '../../views/includes/conn.php';
session_start();

$sql = 'SELECT product_id, products_name, stock_quantity,original_price,description,price, product_star,products_image FROM products';
$result = $conn->query($sql);

if (!$result) {
    die("Prepare failed: " . $conn->error); // Debugging
}
?>


<?php
include('includes/header.php');
?>

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
                    <th>Product</th>
                    <th>Original Price</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>

            <tfoot>
                <th>product_id</th>
                <th>Product image</th>
                <th>Product</th>
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
                    <td> <img src="<?php echo htmlspecialchars($row['products_image']); ?>" class="rounded" width="100" /> </td>
                    <td><?php echo htmlspecialchars($row['products_name']); ?></td>
                    
                    <td>
                        <?php
                            if ($row['original_price'] > 0) {
                                echo htmlspecialchars($row['original_price']);
                            } else {
                                echo "NULL";
                            }
                        ?>
                    </td>
                    
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['stock_quantity']); ?></td>

                    <!-- Delete product -->
                    <td>
                        <form action='#' method='post' class='d-inline'>
                            <input type='hidden' name='product_id' value='$product_id'>
                            <button type='submit' name='remove_from_cart' class=' btn-danger'><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>

                    <!-- Edit product -->
                    <td>
                        <form action="#" method="post" class="d-d-inline">
                            <input type="hidden" name='product_id' value='$product_id'>
                            <button type="submit" name='#' class=""><i class="fa-solid fa-pen-to-square"></i></button>
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


<?php
include('includes/footer.php');
?>