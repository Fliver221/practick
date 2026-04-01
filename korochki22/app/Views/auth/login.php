<section class="section section--auth">
    <div class="container">
        <div class="auth-card auth-card--narrow">
            <div class="auth-card__intro">
                <div class="eyebrow">Авторизация</div>
                <h1>Вход в систему</h1>
                <p>Войдите в систему, чтобы перейти к заявкам и обучению.</p>
            </div>

            <form method="post" action="<?= url('/login'); ?>" class="form-card" novalidate>
                <?= csrf_field(); ?>

                <?php if (!empty($errors['common'])): ?>
                    <div class="form-alert"><?= e($errors['common']); ?></div>
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-field form-field--full">
                        <label for="login">Логин</label>
                        <input id="login" name="login" type="text" value="<?= e(old('login')); ?>" required>
                        <?php if (!empty($errors['login'])): ?><small class="form-error"><?= e($errors['login']); ?></small><?php endif; ?>
                    </div>

                    <div class="form-field form-field--full">
                        <label for="password">Пароль</label>
                        <input id="password" name="password" type="password" required>
                        <?php if (!empty($errors['password'])): ?><small class="form-error"><?= e($errors['password']); ?></small><?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button">Войти</button>
                    <a class="text-link" href="<?= url('/register'); ?>">Еще не зарегистрированы? Регистрация</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php \App\Core\Session::clearOld(); ?>
