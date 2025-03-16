<?php
require_once 'views/includes/conn.php';
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>

        <?php include('views/includes/header.php') ?>

        <div class="container py-5">
            <div class="row">

                <!-- Left Column -->
                <div class="col-md-6 mb-4">
                    <img src="<?php echo htmlspecialchars($row['products_image']); ?>" class="img-fluid product-image" alt="Product Image">
                </div>

                <!-- Right Column -->
                <div class="col-md-6 mb-4">
                    <div class="product-card">
                        <div class="card-body">
                            
                            <h1 class="card-title"><?php echo htmlspecialchars($row['products_name']); ?></h1>

                            <span class="text-muted text-decoration-line-through">
                                <?php
                                if ($row['original_price'] > $row['price']) {
                                    echo "$" . htmlspecialchars($row['original_price']);
                                }
                                ?>
                            </span>

                            <span class="h4 me-2 text-primary">$<?php echo htmlspecialchars($row['price']); ?></span>
                            
                            <?php
                            if ($row['original_price']<['price']&&$row['original_price']!=0) {
                                $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);
                                echo"<span class='badge bg-danger ms-2'>$discount % OFF</span>";
                            }
                            ?>

                            <div class="mb-4">
                                <strong>Description:</strong>
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                            </div>

                            <!-- Add to Cart Form -->
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id']) ?>">
                                <label class="me-2">Quantity:</label>
                                <select name="quantity" id="quantity">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <button class="btn btn-primary flex-shrink-0"
                                    type="submit"
                                    name="add_to_cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Add to cart
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <hr>

        <!-- Related items section-->
        <section class="py-5 ">
            <div class="container px-4 px-lg-5 mt-5">
                <h2 class="fw-bolder mb-4">Related products</h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Fancy Product</h5>
                                    <!-- Product price-->
                                    $40.00 - $80.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View options</a></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>



        <!-- Footer -->
        <?php include('views/includes/footer.php') ?>

<?php
    } else {
        echo "Product not found.";
    }
    $stmt->close();
} else {
    echo "No product selected.";
}

$conn->close();
?>

</body>

</html>