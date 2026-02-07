<?php

namespace App\Utils;

class Lang
{
    private static array $lang = [];

    public static function init()
    {

        if (isset($_GET['lang'])) {
            $_SESSION['lang'] = $_GET['lang'];
        }
        
        $_SESSION['lang'] = $_SESSION['lang'] ?? 'en-us';
        $file = __DIR__ . "/../../languages/" . $_SESSION['lang'] . ".php";
        if(file_exists($file))
        {
            include $file;
            if(isset($lang) && is_array($lang)){
                self::$lang = $lang;
            }
        }
    }

    public static function __($str)
    {
        return self::$lang[$str] ?? $str;
    }

    public static function get_language_file()
    {
        $_SESSION['lang'] = $_GET['lang'] ?? $_SESSION['lang'];
        return (__DIR__ . "/../../languages/" . $_SESSION['lang'] . ".php");
    }
    
}
