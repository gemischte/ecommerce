<?php
require_once '../../includes/conn.php';
session_start();

$sql = 'SELECT product_id, name, stock_quantity,original_price,description,price, product_star,image_path FROM products';
$result = $conn->query($sql);

if (!$result) {
    die("Prepare failed: " . $conn->error); // Debugging
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Database/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Database/css/dashboard.css">
    <link rel="stylesheet" href="/Database/css/style.css">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const main = document.querySelector(".main");
            const toggleBtn = document.querySelector(".toggle-btn");

            toggleBtn.addEventListener("click", function() {
                sidebar.classList.toggle("expand");
                main.classList.toggle("expand");
            });
        });
    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="bi bi-layout-sidebar"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">Admin page</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-person-circle"></i>
                        <span>User</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="bi bi-bag"></i>
                        <span>Products</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="http://localhost/Database/dashboard/admin/products.php" class="sidebar-link">Upload</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Change</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-bell"></i>
                        <span>Notification</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-gear"></i>
                        <span>Setting</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-box-arrow-in-left"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main p-3">
            <div class="text-center">
                <h1>
                    <!-- Sidebar Bootstrap 5 -->

                    <div class="container py-5">
                        <h1 class="text-center">Products Manage</h1>
                        <!-- Cart Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>product_id</th>
                                        <th>Product</th>
                                        <th>Original Price</th>
                                        <th>Price</th>
                                        <th>Stock Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>

                                            <tr>
                                                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?> <img src="<?php echo htmlspecialchars($row['image_path']); ?>" width="100" height="100" alt="..." /> </td>
                                                <td><?php echo htmlspecialchars($row['original_price']); ?></td>
                                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                                <td><?php echo htmlspecialchars($row['stock_quantity']); ?></td>


                                                <td>
                                                    <form action='cart.php' method='post' class='d-inline'>
                                                        <input type='hidden' name='product_id' value='$product_id'>
                                                        <button type='submit' name='remove_from_cart' class='bi bi-trash btn btn-danger'></button>
                                                    </form>
                                                </td>

                                            </tr>


                                    <?php }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>

                </h1>
            </div>
        </div>
    </div>

    <?php require_once '../../includes/footer.php'; ?>

</body>

</html>