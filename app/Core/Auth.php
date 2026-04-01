<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt($login, $password)
    {
        $user = User::findByLoginExact($login);

        if ($user && password_verify($password, $user['password_hash'])) {
            self::login($user);
            return true;
        }

        $fallback = app_config('admin_fallback');
        if (
            is_array($fallback) &&
            isset($fallback['login'], $fallback['password'], $fallback['db_login']) &&
            $login === $fallback['login'] &&
            $password === $fallback['password']
        ) {
            $admin = User::findByLoginExact($fallback['db_login']);
            if ($admin && $admin['role'] === 'admin') {
                self::login($admin);
                return true;
            }
        }

        return false;
    }

    public static function login(array $user)
    {
        Session::put('auth_user', [
            'id' => (int) $user['id'],
            'login' => $user['login'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
        ]);
    }

    public static function logout()
    {
        Session::forget('auth_user');
    }

    public static function user()
    {
        return Session::get('auth_user');
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function id()
    {
        $user = self::user();
        return $user ? (int) $user['id'] : null;
    }

    public static function isAdmin()
    {
        $user = self::user();
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }
}
