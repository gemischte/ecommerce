<?php

require_once __DIR__ . '/../../../core/init.php';

use App\Utils\Alert;

session_unset();
session_destroy();
Alert::success("Success", "Logout successful", ADMIN_URL . "views/login.php");
?>
