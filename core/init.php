<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

if (file_exists(__DIR__ . '/../vendor/composer/platform_check.php')) {
    require __DIR__ . '/../vendor/composer/platform_check.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/Utils/Lang.php';