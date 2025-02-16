<?Php
require_once '../../includes/conn.php';
include_once '../../includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST['name']) &&
        isset($_POST['description']) &&
        isset($_POST['price']) &&
        isset($_POST['original_price']) &&
        isset($_POST['stock_quantity']) &&
        isset($_POST['image_path'])
    ) {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $original_price = $_POST['original_price'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_path = $_POST['image_path'];

        $product_id = rand(1000, 99999);

        $sql = "INSERT INTO products  (name,product_id ,description, price, original_price,stock_quantity, image_path) 
         VALUES (?, ?, ?, ?, ?,?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssdss", $name, $product_id, $description, $price, $original_price, $stock_quantity, $image_path);

            if ($stmt->execute()) {
                echo "
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'successfully!',
                            text: 'New product has been added successfully!',
                            showConfirmButton: true,
                        })
                    }, 100);
                </script>
                ";
            } else {
                echo '<div class = "error">';
                echo ("Product added faild!");
                echo '</div>';
            }

            $stmt->close();
        } else {
            echo '<div class = "error">';
            echo "SQL prepare failed: " . $conn->error;
            echo '</div>';
        }
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Manage</title>
    <link rel="icon" href="/Database/image/favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="/Database/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Database/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Scripts -->
    <script src="/Database/js/Function.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>

<body class="bg-light was-validated">
    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0"><i class="fas fa-box"></i>Product Management</h2>
            </div>
            
            <div class="card-body">
                <form method="POST" action="products.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            placeholder="Enter product name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Product Description</label>
                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Enter product description"
                            required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Product Price ($)</label>
                            <input
                                type="number"
                                class="form-control"
                                id="price"
                                name="price"
                                placeholder="Enter product price"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="original_price" class="form-label">Original Price ($)</label>
                            <input
                                type="number"
                                class="form-control"
                                id="original_price"
                                name="original_price"
                                placeholder="Enter product original price">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input
                                type="number"
                                class="form-control"
                                id="stock_quantity"
                                name="stock_quantity"
                                placeholder="Enter available quantity"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image_path" class="form-label">Image Path</label>
                        <input
                            type="text"
                            class="form-control"
                            id="image_path"
                            name="image_path"
                            placeholder="Enter image path or URL"
                            required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Submit <i class="fas fa-check"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../../includes/footer.php'; ?>
</body>

</html>