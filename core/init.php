<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../vendor/composer/platform_check.php')) {
    require __DIR__ . '/../vendor/composer/platform_check.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/Utils/Lang.php';
require_once __DIR__ . '/../views/includes/assets.php';
require_once __DIR__ . '/../src/helpers.php';