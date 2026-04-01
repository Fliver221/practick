<?php
declare(strict_types=1);

session_start();

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once APP_PATH . '/Core/helpers.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

$appConfig = require CONFIG_PATH . '/app.php';
$dbConfig = require CONFIG_PATH . '/database.php';

$GLOBALS['app_config'] = $appConfig;
$GLOBALS['db_config'] = $dbConfig;

try {
    \App\Core\Database::init($dbConfig);
    \App\Core\Installer::ensureInstalled();
} catch (\Throwable $e) {
    http_response_code(500);
    ?><!doctype html>
    <html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Ошибка подключения БД</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body{font-family:Arial,sans-serif;background:#f6f8fb;color:#10233d;margin:0;padding:24px}
            .box{max-width:820px;margin:40px auto;background:#fff;border-radius:20px;padding:28px;box-shadow:0 20px 60px rgba(16,35,61,.08)}
            code{background:#eef3fb;padding:2px 6px;border-radius:6px}
        </style>
    </head>
    <body>
        <div class="box">
            <h1>Не удалось подключиться к базе данных</h1>
            <p>Проверьте файл <code>config/database.php</code>, создайте пустую базу данных и обновите страницу.</p>
            <p><strong>Техническое сообщение:</strong> <?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </body>
    </html><?php
    exit;
}

$router = new \App\Core\Router();

$router->get('/', 'HomeController@entry');
$router->get('/home', 'HomeController@index');

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->get('/applications', 'ApplicationController@index');
$router->get('/applications/create', 'ApplicationController@create');
$router->post('/applications/create', 'ApplicationController@store');

$router->get('/reviews/create', 'ReviewController@create');
$router->post('/reviews/create', 'ReviewController@store');

$router->get('/admin/applications', 'AdminController@index');
$router->post('/admin/applications/status', 'AdminController@updateStatus');
$router->post('/admin/courses', 'AdminController@storeCourse');

$router->dispatch();
