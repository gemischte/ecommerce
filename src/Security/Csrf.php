<?php

namespace App\Security;

class Csrf
{
    /**
     * @return string CSRF Token
     */
    public static function csrf_token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * @param string $token Ver CSRF Token
     * @return void
     */
    public static function ver_csrf($token, $fail_url = null, $fail_doc = " ")
    {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($token, $_SESSION['csrf_token']))
            {
                write_log("CSRF validation failed in $fail_doc", 'WARNING');
                redirect_to(WEBSITE_URL . $fail_url);
            }
            // unset($_SESSION['csrf_token']);
    }

    /**
     * @return string Html CSRF Field
     */
    public static function csrf_field()
    {
        $token = self::csrf_token();
        return '<input type="hidden" name="csrf_token" value="'. htmlspecialchars($token) .'">';
    }
}
