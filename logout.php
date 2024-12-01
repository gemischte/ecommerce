<?php
session_start();
session_unset();
session_destroy();
// <a href='login.html'>logout</a>";
echo "<script>
if(confirm('Are you want logout?')){
location.href = 'login.html';
}
else{

window.history.back()
}
</script>"

?>