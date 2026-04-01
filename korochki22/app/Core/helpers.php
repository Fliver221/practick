<?php

function app_config($key = null, $default = null)
{
    $config = isset($GLOBALS['app_config']) ? $GLOBALS['app_config'] : [];

    if ($key === null) {
        return $config;
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function db_config($key = null, $default = null)
{
    $config = isset($GLOBALS['db_config']) ? $GLOBALS['db_config'] : [];

    if ($key === null) {
        return $config;
    }

    return array_key_exists($key, $config) ? $config[$key] : $default;
}

function base_path()
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    if (!empty(app_config('base_url'))) {
        $base = rtrim(app_config('base_url'), '/');
        return $base;
    }

    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
    $base = ($scriptName === '/' || $scriptName === '.') ? '' : rtrim($scriptName, '/');

    return $base;
}


function project_root($path = '')
{
    $root = dirname(__DIR__, 2);
    if ($path === '') {
        return $root;
    }

    return $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($path, '/\\'));
}

function embedded_asset($path)
{
    $file = project_root($path);

    if (!is_file($file) || !is_readable($file)) {
        return asset($path);
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeMap = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'gif' => 'image/gif',
    ];

    $mime = isset($mimeMap[$extension]) ? $mimeMap[$extension] : 'application/octet-stream';
    $content = @file_get_contents($file);

    if ($content === false) {
        return asset($path);
    }

    return 'data:' . $mime . ';base64,' . base64_encode($content);
}
function url($path = '')
{
    $path = '/' . ltrim($path, '/');
    $base = base_path();

    if ($path === '/') {
        return $base !== '' ? $base . '/' : '/';
    }

    return ($base !== '' ? $base : '') . $path;
}

function asset($path)
{
    return url($path);
}

function current_path()
{
    $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
    $base = base_path();

    if ($base && strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }

    return $uri ?: '/';
}

function request_method()
{
    return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old($key, $default = '')
{
    return \App\Core\Session::old($key, $default);
}

function flash($key)
{
    return \App\Core\Session::flash($key);
}

function has_flash($key)
{
    return \App\Core\Session::hasFlash($key);
}

function csrf_field()
{
    return '<input type="hidden" name="_token" value="' . e(\App\Core\Csrf::token()) . '">';
}

function is_active($path)
{
    return current_path() === $path ? 'is-active' : '';
}

function format_datetime($datetime)
{
    if (empty($datetime)) {
        return '—';
    }

    $timestamp = strtotime((string) $datetime);
    return $timestamp ? date('d.m.Y H:i', $timestamp) : '—';
}

function format_date($date)
{
    if (empty($date)) {
        return '—';
    }

    $timestamp = strtotime((string) $date);
    return $timestamp ? date('d.m.Y', $timestamp) : '—';
}

function status_badge_class($status)
{
    switch ($status) {
        case 'Новая':
            return 'badge badge--new';
        case 'Идет обучение':
            return 'badge badge--progress';
        case 'Обучение завершено':
            return 'badge badge--done';
        default:
            return 'badge';
    }
}
