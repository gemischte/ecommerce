<?php
require_once __DIR__ . '/../../../core/config.php';
require_once __DIR__ . '/../../../views/includes/assets.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userid = $_POST['user_id'];

    $edit_user = "SELECT * FROM user_accounts WHERE user_id = ?";
    $stmt = $conn->prepare($edit_user);

    if ($stmt) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['username'];
            $email = $row['email'];
            $token = $row['token'];
            $token_expiry = $row['token_expiry'];
        } else {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Account Not Found',
                        text: 'The requested account does not exist.',
                        showConfirmButton: true,
                    }).then(() => {
                        window.location = '<?= ADMIN_URL . 'views/user_accounts.php'; ?>';
                    });
                }, 100);
            </script>

            <?php
            exit();
        }
    } else {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // Validate and sanitize input data
    $name = $_POST['username'] ?? $name;
    $email = $_POST['email'] ?? $email;
    $token = $_POST['token'] ?? $token;
    $token_expiry = $_POST['token_expiry'] ?? $token_expiry;

    $update = "UPDATE user_accounts SET username = ?, email = ?, token = ?, token_expiry = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update);

    if ($stmt) {
        $stmt->bind_param(
            "sssss",
            $name,
            $email,
            $token,
            $token_expiry,
            $userid
        );
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
            ?>

                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your account has been successfully updated!',
                            showConfirmButton: true,
                        }).then(() => {
                            window.location = '<?= ADMIN_URL . 'views/user_accounts.php'; ?>';
                        });
                    }, 100);
                </script>
            <?php
                exit();
            }
        } else {
            ?>

            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to edit the account. Please try again.',
                        showConfirmButton: true,
                    }).then(() => {
                        window.location = '<?= ADMIN_URL . 'views/user_accounts.php'; ?>';
                    });
                }, 100);
            </script>
<?php
        }
    };
}
?>

<?php include __DIR__ . ('/../includes/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <a href="<?= ADMIN_URL . 'views/user_accounts.php'; ?>">
                <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /Edit User Account</i>

        </div>
    </div>
</div>


<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Edit User Account</h2>
        </div>

        <div class="card-body">
            <form method="POST" action="edit_user.php" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userid); ?>">

                <div class="mb-3">
                    <label for="userid" class="form-label">ID</label>
                    <input
                        type="text"
                        class="form-control"
                        id="userid"
                        value="<?= htmlspecialchars($userid); ?>"
                        name="userid"
                        disabled>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Username</label>
                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        value="<?= htmlspecialchars($name); ?>"
                        pattern="[A-Za-z0-9_@.]{4,32}"
                        name="username"
                        placeholder="Enter Username">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="text"
                        class="form-control"
                        id="email"
                        name="email"
                        pattern="\S+@\S+\.\S+"
                        placeholder="Enter Email"
                        value="<?= htmlspecialchars("$email") ?>">
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Token</label>
                        <input
                            type="text"
                            class="form-control"
                            id="token"
                            name="token"
                            value="<?= htmlspecialchars($token); ?>"
                            placeholder="Enter Token">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="original_price" class="form-label">Token Expiry</label>
                        <input
                            type="date"
                            class="form-control"
                            id="token_expiry"
                            name="token_expiry"
                            value="<?= htmlspecialchars($token_expiry); ?>">
                    </div>

                    <div class="text-end">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </div>

                </div>

            </form>
        </div>

    </div>
</div>
<!-- Footer -->
<?php include __DIR__ . ('/../includes/footer.php'); ?>