<?php
session_start();
session_unset();
session_destroy();
require_once __DIR__ . '/views/includes/assets.php';
?>

<script>
	setTimeout(function() {
		Swal.fire({
			icon: "success",
			title: "Logout successful",
			timer: 1500
		}).then(() => {
			window.location = "login.html";
		});
	}, 100);
</script>
