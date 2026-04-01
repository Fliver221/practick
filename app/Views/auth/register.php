<section class="section section--auth">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card__intro">
                <div class="eyebrow">Создание учётной записи</div>
                <h1>Регистрация пользователя</h1>
                <p>Заполните обязательные поля. После успешной регистрации вы сразу попадёте в личный кабинет.</p>
            </div>

            <form method="post" action="<?= url('/register'); ?>" class="form-card" novalidate>
                <?= csrf_field(); ?>

                <?php if (!empty($errors['common'])): ?>
                    <div class="form-alert"><?= e($errors['common']); ?></div>
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="login">Логин</label>
                        <input id="login" name="login" type="text" value="<?= e(old('login')); ?>" minlength="6" pattern="[A-Za-z0-9]{6,}" required>
                        <?php if (!empty($errors['login'])): ?><small class="form-error"><?= e($errors['login']); ?></small><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="password">Пароль</label>
                        <input id="password" name="password" type="password" minlength="8" required>
                        <?php if (!empty($errors['password'])): ?><small class="form-error"><?= e($errors['password']); ?></small><?php endif; ?>
                    </div>

                    <div class="form-field form-field--full">
                        <label for="full_name">ФИО</label>
                        <input id="full_name" name="full_name" type="text" value="<?= e(old('full_name')); ?>" required>
                        <?php if (!empty($errors['full_name'])): ?><small class="form-error"><?= e($errors['full_name']); ?></small><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="phone">Телефон</label>
                        <input id="phone" name="phone" type="text" value="<?= e(old('phone')); ?>" placeholder="8(XXX)XXX-XX-XX" data-mask="phone" inputmode="numeric" required>
                        <?php if (!empty($errors['phone'])): ?><small class="form-error"><?= e($errors['phone']); ?></small><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?= e(old('email')); ?>" required>
                        <?php if (!empty($errors['email'])): ?><small class="form-error"><?= e($errors['email']); ?></small><?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button">Зарегистрироваться</button>
                    <a class="text-link" href="<?= url('/login'); ?>">Уже есть аккаунт? Войти</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php \App\Core\Session::clearOld(); ?>
