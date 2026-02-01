<?php
require_once __DIR__ . '/Utils/Helper.php';

use Utils\Helper;

function write_log($error_msg, $level = 'INFO')
{
    Helper::write_log($error_msg, $level);
}

function select_lang($lang)
{
    return Helper::select_lang($lang);
}

function redirect_to($url)
{
    Helper::redirect_to($url);
}

function all_countries(mysqli $conn)
{
    return Helper::all_countries($conn);
}

require_once __DIR__ . '/Utils/Lang.php';
use Utils\Lang;

function __($str)
{
    return Lang::__($str);
}
Lang::init();