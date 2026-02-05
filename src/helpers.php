<?php
require_once __DIR__ . '/Utils/Helper.php';

use Utils\Helper;

// function select_lang($lang)
// {
//     return Helper::select_lang($lang);
// }

// function all_countries(mysqli $conn)
// {
//     return Helper::all_countries($conn);
// }

require_once __DIR__ . '/Utils/Lang.php';
use Utils\Lang;

function __($str)
{
    return Lang::__($str);
}
Lang::init();