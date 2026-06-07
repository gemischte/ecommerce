<?php

namespace App\Security;

use App\Utils\Helper;
use App\Utils\Logger;

class Csrf
{
    private static function isValidToken($token): bool
    {
        return is_string($token) &&
            strlen($token) === 64 &&
            ctype_xdigit($token);
    }

    /**
     * Generate a CSRF token and store it in the session if it does not exist.
     * 
     * @return string CSRF Token
     */
    public static function csrf_token(): string
    {
        if (!self::isValidToken($_SESSION['csrf_token'] ?? null)) {
            try {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } catch (\Exception $e) {
                Logger::error("Failed to generate CSRF token", [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]);
                throw new \RuntimeException('Unable to generate CSRF token', 0, $e);
            }
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Verify the CSRF token against the session token.
     * 
     * @param string|null $token CSRF token provided by the request
     * @return bool True if the token is valid
     */
    public static function verify_token(?string $token): bool
    {
        if (
            !self::isValidToken($_SESSION['csrf_token'] ?? null) ||
            !self::isValidToken($token)
        ) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Validate CSRF token and redirect if validation fails.
     * 
     * @param string|null $token CSRF token from request
     * @param string|null $fail_url Redirect path when validation fails
     * @param string $fail_doc Document name used for logging
     * @return void
     */
    public static function ver_csrf(?string $token, ?string $fail_url = null, string $fail_doc = ""): void
    {
        if (!self::verify_token($token)) {

            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $maskedIp = preg_replace('/\.\d+$/', '.xxx', $ip);
            } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $maskedIp = substr($ip, 0, 12) . '...';
            } else {
                $maskedIp = 'unknown';
            }

            Logger::warning('CSRF validation failed', [
                'fail_doc' => $fail_doc,
                'fail_url' => $fail_url,
                'token_ref' => $token !== null ? substr(hash('sha256', $token), 0, 12) : null, // Log a hash reference of the token instead of the token itself
                'ip' => $maskedIp,
            ]);
            Helper::redirect_to(WEBSITE_URL . ($fail_url ?? ''));
        }
    }

    /**
     * Generate a hidden HTML input field containing the CSRF token.
     * 
     * @return string HTML hidden input field containing the CSRF token
     */
    public static function csrf_field(): string
    {
        $token = self::csrf_token();
        return '<input type="hidden" name="csrf_token" value="' .
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
