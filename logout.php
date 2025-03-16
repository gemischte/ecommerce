<?php
 
session_start();
session_unset();
session_destroy();

echo '
	<script>
		setTimeout(function() {
			Swal.fire({
  				icon: "question",
  				title: "Do you want to log out?",
				text: "See you later",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Confirm Logout!"
			}).then((result) => {
  				if (result.isConfirmed) {
    				Swal.fire({
      					title: "Logged Out!",
      					text: "You have been logged out successfully.",
      					icon: "success"
    				}).then(() => {
						
						window.location = "login.html";
					});
  				} else {
					
					window.history.back();
				}
			});
		}, 100);
	</script>
';
?>
