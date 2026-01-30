<?php

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Utils\Alert;

//all country list
$countries = all_countries($conn);

$userid = $_GET['uid'] ?? $_POST['user_id'] ?? null;
$phone = $call_code = $email = $country = $city = $address = $postal_code = $last_name = $first_name = $birthday = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && $userid) {

    $edit_profile = "SELECT 
    ua.email AS email,
    up.*
    FROM user_profiles AS up
    JOIN user_accounts AS ua ON up.user_id = ua.user_id
    WHERE up.user_id = ?";
    $stmt = $conn->prepare($edit_profile);

    if ($stmt) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $call_code = $row['calling_code'];
            $phone = $row['phone'];
            $email = $row['email'];
            $country = $row['country'];
            $city = $row['city'];
            $address = $row['address'];
            $postal_code = $row['postal_code'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $birthday = $row['birthday'];
        } else {
            Alert::error("Oops...", "Account not found.",
            WEBSITE_URL . "dashboard/user/views/profile.php");
            exit();
        }
    } else {
        write_log("Error preparing statement: " . $conn->error, 'ERROR');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // CSRF token validation
    Csrf::ver_csrf($_POST['csrf_token'] ?? '', "dashboard/user/views/profile.php", "edit profile");

    // Validate and sanitize input data
    $call_code = $_POST['calling_code'] ?? '';
    $phone = $_POST['phone'] ?? $phone;
    $email = $_POST['email'] ?? $email;
    $country = $_POST['country'] ?? $country;
    $city = $_POST['city'] ?? $city;
    $address = $_POST['address'] ?? $address;
    $postal_code = $_POST['postal_code'] ?? $postal_code;
    $first_name = $_POST['first_name'] ?? $first_name;
    $last_name = $_POST['last_name'] ?? $last_name;
    $birthday = $_POST['birthday'] ?? $birthday;

    $update = "UPDATE 
    user_profiles AS up
    JOIN user_accounts AS ua ON up.user_id = ua.user_id
    SET up.calling_code = ?,
    up.phone = ?, 
    up.country = ?,
    up.city = ?, 
    up.address = ?, 
    up.postal_code = ?, 
    up.first_name = ?, 
    up.last_name = ?, 
    up.birthday = ?,
    ua.email = ?
    WHERE up.user_id = ?";
    $stmt = $conn->prepare($update);

    if ($stmt) {
        $stmt->bind_param(
            "sssssssssss",
            $call_code,
            $phone,
            $country,
            $city,
            $address,
            $postal_code,
            $first_name,
            $last_name,
            $birthday,
            $email,
            $userid
        );
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                Alert::success("Success", "Your profile has been successfully updated!",
                WEBSITE_URL . "dashboard/user/views/profile.php");
                exit();
            } else {
                Alert::warning("Warning", "Please change the profile info.");
            }
        } else {
            Alert::error("Error", "Failed to edit the profile. Please try again.",
            WEBSITE_URL . "dashboard/user/views/profile.php");
            exit();
        }
    };
}
?>

<?php include __DIR__ . ('/../../../views/includes/header.php'); ?>

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Edit User Account</h2>
        </div>

        <div class="card-body">
            <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userid); ?>">
                <?= csrf::csrf_field() ?>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label"><?= __('First name') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="first_name"
                            name="first_name"
                            placeholder="Enter First Name"
                            value="<?= htmlspecialchars("$first_name") ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label"><?= __('Last name') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="last_name"
                            name="last_name"
                            value="<?= htmlspecialchars($last_name); ?>"
                            placeholder="Enter Last Name">
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="calling_code" class="form-label"><?= __('Phone') ?></label>

                        <select name="calling_code" id="calling_code" class="form-select">
                            <?php
                            $selectedCode = htmlspecialchars($call_code);
                            foreach ($countries as $c) {
                                $call_code = htmlspecialchars($c['calling_codes']);
                                $country_name = htmlspecialchars($c['name']);
                                $selected = ($call_code === $selectedCode) ? 'selected' : '';
                                echo "<option value='$call_code'$selected>$country_name | $call_code</option>";
                            }
                            ?>
                        </select>

                        <input
                            type="text"
                            class="form-control"
                            id="phone"
                            value="<?= htmlspecialchars($phone); ?>"
                            name="phone"
                            placeholder="Enter phone number">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label"><?= __('email') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="email"
                            value="<?= htmlspecialchars($email); ?>"
                            name="email"
                            placeholder="Enter email">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label"><?= __('Birthday') ?></label>
                        <input
                            type="date"
                            class="form-control"
                            id="birthday"
                            name="birthday"
                            value="<?= htmlspecialchars($birthday); ?>"
                            placeholder="Enter Birthday">
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label"><?= __('Country') ?></label>
                        <select class="form-select"
                            id="country"
                            name="country"
                            required>
                            <option value="">Choose...</option>
                            <?php
                            $selectedCountry = htmlspecialchars($country);
                            foreach ($countries as $ctry) {
                                $country_name = htmlspecialchars($ctry['name']);
                                $selected = ($country_name === $selectedCountry) ? 'selected' : '';
                                echo "<option value='$country_name'$selected>$country_name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label"><?= __('city') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="city"
                            name="city"
                            value="<?= htmlspecialchars($city); ?>"
                            placeholder="Enter city">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label"><?= __('Address') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="address"
                            value="<?= htmlspecialchars($address); ?>"
                            name="address"
                            placeholder="Enter address">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="postal_code" class="form-label"><?= __('postal code') ?></label>
                        <input
                            type="text"
                            class="form-control"
                            id="postal_code"
                            value="<?= htmlspecialchars($postal_code); ?>"
                            name="postal_code"
                            placeholder="Enter Zip">
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
<?php include __DIR__ . ('/../../../views/includes/footer.php'); ?>