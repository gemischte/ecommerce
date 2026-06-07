<?php

namespace App\Utils;

use mysqli;

class Helper
{    
    public static function create_uid()
    {
        return 'user_' . bin2hex(random_bytes(16));
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
        if (!$stmt) {
            \App\Utils\Logger::error("Prepare failed: ", [
                'sql' => $sql,
                'error' => $conn->error ?: 'Unknown mysqli error',
            ]);
            return [];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
