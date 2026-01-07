<?php
require_once __DIR__ . '/../core/init.php';
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
			window.location = "<?= WEBSITE_URL . 'views/login.php'?>";
		});
	}, 100);
</script>