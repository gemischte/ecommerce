<?php

namespace App\Utils;

use mysqli;

class Helper
{
    private mysqli $conn;
    
    public function __construct(mysqli $conn)
    {
        self::$conn = $conn;
    }
    
    public static function create_uid()
    {
        return 'user_' . bin2hex(random_bytes(16));
    }

    /**  
     * @param string $error_msg Error message
     * @param string $level Log level,default is 'INFO'
     * @return void
     */
    public static function write_log($error_msg, $level = 'INFO')
    {
        $date = date('Y-m');
        $file = __DIR__ . '/../../storage/logs/' . $date . '.txt';
        $dir = dirname($file);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $times = date('Y-m-d H:i:s');
        $entry_log = "[$times] [$level] $error_msg" . PHP_EOL;
        file_put_contents($file, $entry_log, FILE_APPEND | LOCK_EX);
    }

    /** 
     * @param string $lang select language
     * @return string URL with selected language params
     */
    public static function select_lang($lang)
    {
        $params = $_GET;
        $params['lang'] = $lang;
        return basename($_SERVER['PHP_SELF']) . '?' . http_build_query($params);
    }

    /**
     * @param string $url URL to redirect
     * @return void
     */
    public static function redirect_to($url)
    {
        header("Location: $url");
        exit();
    }

    /**
     * Data Source https://www.apicountries.com/countries
     * @return array list of all countries
     */
    public static function all_countries(mysqli $conn)
    {
        $sql = "SELECT * FROM countries ORDER BY name ASC";
        $stmt = $conn->prepare($sql);
        if (! $stmt) {
            self::write_log("Prepare filed:" . $conn->error, 'ERROR');
            return [];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
