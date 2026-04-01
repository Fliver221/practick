<?php $authUser = \App\Core\Auth::user(); ?>
<header class="site-header">
    <div class="container site-header__inner">
        <?php if (!$authUser): ?>
            <a class="brand" href="<?= url('/login'); ?>">
        <?php elseif ($authUser['role'] === 'admin'): ?>
            <a class="brand" href="<?= url('/admin/applications'); ?>">
        <?php else: ?>
            <a class="brand" href="<?= url('/home'); ?>">
        <?php endif; ?>
                <img src="<?= asset('assets/img/logo.webp'); ?>" alt="Логотип Корочки.есть" class="brand__logo">
                <span class="brand__text">
                    <span class="brand__title">Корочки.есть</span>
                    <span class="brand__subtitle">Онлайн-курсы ДПО</span>
                </span>
            </a>

        <?php if ($authUser): ?>
            <button class="nav-toggle" type="button" aria-label="Открыть меню" data-nav-toggle>
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" data-nav-menu>
                <?php if ($authUser['role'] === 'admin'): ?>
                    <a class="main-nav__link <?= is_active('/admin/applications'); ?>" href="<?= url('/admin/applications'); ?>">Админ-панель</a>
                <?php else: ?>
                    <a class="main-nav__link <?= is_active('/home'); ?>" href="<?= url('/home'); ?>">Главная</a>
                    <a class="main-nav__link <?= is_active('/applications/create'); ?>" href="<?= url('/applications/create'); ?>">Подать заявку</a>
                    <a class="main-nav__link <?= is_active('/applications'); ?>" href="<?= url('/applications'); ?>">Мои заявки</a>
                <?php endif; ?>

                <div class="main-nav__user">
                    <span class="main-nav__user-name"><?= e($authUser['full_name']); ?></span>
                    <form method="post" action="<?= url('/logout'); ?>">
                        <?= csrf_field(); ?>
                        <button type="submit" class="button button--ghost button--small">Выйти</button>
                    </form>
                </div>
            </nav>
        <?php endif; ?>
    </div>
</header>
