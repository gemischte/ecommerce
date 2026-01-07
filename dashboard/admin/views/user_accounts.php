<?php
require_once __DIR__ . '/../../../core/init.php';
$user_account = "SELECT user_id ,username,email,token,token_expiry FROM user_accounts";
$result = $conn->query($user_account);

if (!$result) {
    write_log("Prepare failed: " . $conn->error, 'ERROR'); // Debugging
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <a href="<?= ADMIN_URL . 'index.php'; ?>">
                <i class="material-symbols-rounded opacity-5">Dashboard</a>
            /User Account Management</i>

        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Cart Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">

                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Token</th>
                    <th>Token Expiry</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Token</th>
                    <th>Token Expiry</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
            </tfoot>

            <tbody>


                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>

                        <tr>
                            <td><?= htmlspecialchars($row['user_id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['token']); ?></td>
                            <td><?= htmlspecialchars($row['token_expiry']); ?></td>

                            <!-- Delete user -->
                            <td>
                                <form action="<?= ADMIN_URL . "functions/delete_user.php?id=" . htmlspecialchars($row['user_id']) ?>" method="post" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['user_id']); ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    <button type="submit" name="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>

                            <!-- Edit user -->
                            <td>
                                <form action="<?= ADMIN_URL . "functions/edit_user.php?id=" . htmlspecialchars($row['user_id']) ?>" method="GET" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['user_id']); ?>">
                                    <button type="submit" name="submit" class="btn btn-secondary"><i class="fa-solid fa-pen-to-square"></i></button>
                                </form>
                            </td>

                        </tr>


                <?php }
                }
                ?>



            </tbody>

        </table>

    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>