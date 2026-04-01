<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? e($title) : 'Корочки.есть'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ff5a1f">
    <link rel="preload" href="<?= asset('assets/img/logo.webp'); ?>" as="image">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css'); ?>">
</head>
<body>
<div class="page-shell">
    <?php include APP_PATH . '/Views/partials/header.php'; ?>

    <main class="page-main">
        <div class="container">
            <?php include APP_PATH . '/Views/partials/flash.php'; ?>
        </div>
        <?= $content; ?>
    </main>

    <?php include APP_PATH . '/Views/partials/footer.php'; ?>
</div>

<script src="<?= asset('assets/js/app.js'); ?>"></script>
<script src="<?= asset('assets/js/slider.js'); ?>"></script>
</body>
</html>
