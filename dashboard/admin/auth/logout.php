<?php
require_once __DIR__ . '/../../../core/config.php';
require_once __DIR__ . '/../../../views/includes/assets.php';
session_unset();
session_destroy();
?>

<script>
	setTimeout(function() {
		Swal.fire({
			icon: "success",
			title: "Logout successful",
			timer: 1500
		}).then(() => {
			window.location = "<?= ADMIN_URL . 'views/login.php'?>";
		});
	}, 100);
</script>