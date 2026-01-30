<?php

include __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;

$user_id = $_SESSION['user_id'];
if (!$user_id) {
    redirect_to(WEBSITE_URL . "views/login.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $profiles = "SELECT 
    up.first_name AS f_name, 
    up.last_name AS l_name,
    up.calling_code AS call_code,
    up.phone AS phone, 
    up.birthday AS birthday,
    up.country AS ctry,
    up.city AS city,
    up.address AS address,
    up.postal_code AS p_code,
    ua.email AS email
    FROM user_profiles up
    JOIN user_accounts ua ON up.user_id = ua.user_id
    WHERE up.user_id = ?";

    $stmt = $conn->prepare($profiles);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

?>

<?php include __DIR__ . ('/../../../views/includes/header.php'); ?>

<title>Profile</title>

<div class="bg-light">
    <div class="container py-5">
        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Sidebar -->
                            <div class="col-lg-3 border-end">
                                <div class="p-4">
                                    <div class="nav flex-column nav-pills">
                                        <a class="nav-link active" href="#"><i class="fas fa-user me-2"></i><?= __('Personal Information') ?></a>
                                        <a class="nav-link" href="#"><i class="fas fa-lock me-2"></i><?= __('Security') ?></a>

                                        <form action="<?= WEBSITE_URL . "auth/delete_account.php" ?>" method="POST" class="mt-3">
                                            <input type="hidden" name="username" value="<?= $_SESSION['user'] ?>">
                                            <?= csrf::csrf_field() ?>
                                            <button type="submit" class="btn btn-danger w-100"><?= __('Delete Account') ?></button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div class="col-lg-9">
                                <div class="p-4">
                                    <!-- Personal Information -->
                                    <div class="mb-4">
                                        <h5 class="mb-4"><?= __('Personal Information') ?></h5>

                                        <form method="post">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('First name') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="first_name"
                                                    id="first_name"
                                                    value="<?= htmlspecialchars($row['f_name']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Last name') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="last_name"
                                                    id="last_name"
                                                    value="<?= htmlspecialchars($row['l_name']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Birthday') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="birthday"
                                                    id="birthday" 
                                                    value="<?= htmlspecialchars($row['birthday']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Email') ?></label>
                                                    <input
                                                    type="email"
                                                    class="form-control"
                                                    name="email"
                                                    id="email"
                                                    value="<?= htmlspecialchars($row['email']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Phone') ?></label>
                                                    <input
                                                    type="tel"
                                                    class="form-control"
                                                    name="phone"
                                                    id="phone"
                                                    value="<?= htmlspecialchars($row['call_code'] . " ". $row['phone']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Country') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="country"
                                                    id="country"
                                                    value="<?= htmlspecialchars($row['ctry']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('city') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="city"
                                                    id="city"
                                                    value="<?= htmlspecialchars($row['city']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('Address') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="address"
                                                    id="address"
                                                    value="<?= htmlspecialchars($row['address']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><?= __('postal code') ?></label>
                                                    <input
                                                    type="text"
                                                    class="form-control"
                                                    name="postal_code"
                                                    id="postal_code"
                                                    value="<?= htmlspecialchars($row['p_code']) ?>"
                                                    disabled>
                                                </div>

                                                <div class="col-12">
                                                    <a class="btn btn-primary" href="<?= WEBSITE_URL . "dashboard/user/functions/edit_profile.php?uid=" . htmlspecialchars($user_id) ?>">
                                                        <?= __('Edit Information') ?>
                                                    </a>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include __DIR__ . ('/../../../views/includes/footer.php'); ?>

</body>

</html>