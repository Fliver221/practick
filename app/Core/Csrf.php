<?php
namespace App\Core;

class Csrf
{
    public static function token()
    {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }

    public static function check($token)
    {
        return isset($_SESSION['_token']) && is_string($token) && hash_equals($_SESSION['_token'], $token);
    }
}
