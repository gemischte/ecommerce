<?php

require_once __DIR__ . '/../core/init.php';

use App\Utils\Helper;

session_unset();
session_destroy();
Helper::redirect_to(WEBSITE_URL . "index.php");
?>
