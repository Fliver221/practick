<?php
namespace App\Core;

class Session
{
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public static function setFlash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function flash($key, $default = null)
    {
        if (!isset($_SESSION['_flash'][$key])) {
            return $default;
        }

        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function hasFlash($key)
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function setOld(array $data)
    {
        $_SESSION['_old'] = $data;
    }

    public static function old($key, $default = '')
    {
        return isset($_SESSION['_old'][$key]) ? $_SESSION['_old'][$key] : $default;
    }

    public static function clearOld()
    {
        unset($_SESSION['_old']);
    }
}
