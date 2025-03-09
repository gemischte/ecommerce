<?Php
require_once '../../includes/conn.php';
include_once '../../includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST['products_name']) &&
        isset($_POST['description']) &&
        isset($_POST['price']) &&
        isset($_POST['original_price']) &&
        isset($_POST['stock_quantity']) &&
        isset($_POST['products_image'])
    ) {

        $name = $_POST['products_name'];
        $description = $_POST['description'];
        $brand = $_POST['brand'];
        $price = $_POST['price'];
        $original_price = $_POST['original_price'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_path = $_POST['products_image'];

        $product_id = rand(1000, 99999);

        $sql = "INSERT INTO products  (products_name,product_id ,description, brand,price, original_price,stock_quantity, products_image) 
         VALUES (?, ?, ?, ?, ?, ?,?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssssdss", $name, $product_id, $description, $brand, $price, $original_price, $stock_quantity, $image_path);

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


<?php
include('includes/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <a href="index.php">
            <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /New Products</i>
            
        </div>
    </div>
</div>


<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">New products on shelves</h2>
        </div>


        <div class="card-body">
            <form method="POST" action="products.php" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="products_name"
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

                    <div class="col-md-6 mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input
                            type="text"
                            class="form-control"
                            id="brand"
                            name="brand"
                            placeholder="Enter available quantity">
                    </div>

                </div>

                <div class="mb-3">
                    <label for="products_image" class="form-label">Image Path</label>
                    <input
                        type="text"
                        class="form-control"
                        id="products_image"
                        name="products_image"
                        placeholder="Enter image path or URL"
                        required>
                </div>


                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit </button>
                </div>

        </div>





        </form>
    </div>

</div>



<?php
include('includes/footer.php');
?>