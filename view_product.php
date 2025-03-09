<?php
require_once 'includes/conn.php';
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

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Product Details</title>
            <link rel="icon" href="image/favicon.ico">

            <!-- Styles -->
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

            <!-- Scripts -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

            <style>
                .product-image {
                    height: 300px;
                    object-fit: cover;
                }

                .product-card .card-body {
                    padding: 20px;
                }
            </style>
        </head>

        <body>

            <!-- Navigation Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container px-4 px-lg-5">
                    <a class="navbar-brand" href="#!">Product Details</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                            </li>
                        </ul>
                        <form class="d-flex" action="cart.php" method="post">
                            <button class="btn btn-outline-dark" type="submit">
                                <i class="bi-cart-fill me-1"></i>
                                Cart

                                <?php

                                if (isset($_SESSION['cart'])) {
                                    $count = count($_SESSION['cart']);
                                    echo "<span class='badge bg-dark text-white ms-1 rounded-pill'>$count</span>";
                                } else{
                                echo"
                                <span class='badge bg-dark text-white ms-1 rounded-pill'>0</span>
                                ";
                            }
                                ?>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

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

                                <span class="text-muted text-decoration-line-through text-primary h5">
                                    <?php
                                    if ($row['original_price'] > $row['price']) {
                                        echo "$" . htmlspecialchars($row['original_price']);
                                    }
                                    ?>
                                </span>
                                <span class="h5 text-primary">$<?php echo htmlspecialchars($row['price']); ?>
                                </span>

                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p>
                                        <li><?php echo htmlspecialchars($row['description']); ?></li>
                                    </p>
                                </div>

                                <!-- Add to Cart Form -->
                                    <form action="cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id'])?>" >
                                        <select name="quantity" id="quantity">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    
                                    <button 
                                    type="submit" 
                                    name="add_to_cart" 
                                    class="btn btn-primary btn-sm w-100">Add to Cart</button>
                                
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php include_once 'includes/footer.php'; ?>

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