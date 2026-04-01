<section class="section">
    <div class="container container--medium">
        <div class="page-head">
            <div>
                <div class="eyebrow">Личный кабинет</div>
                <h1>Формирование заявки</h1>
            </div>
            <a href="<?= url('/applications'); ?>" class="button button--ghost">К списку заявок</a>
        </div>

        <form method="post" action="<?= url('/applications/create'); ?>" class="form-card" novalidate>
            <?= csrf_field(); ?>

            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="course_name">Курс</label>
                    <select id="course_name" name="course_name" required <?= empty($courses) ? 'disabled' : ''; ?>>
                        <option value=""><?= empty($courses) ? 'Нет доступных курсов' : 'Выберите курс'; ?></option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= e($course); ?>" <?= old('course_name') === $course ? 'selected' : ''; ?>><?= e($course); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($courses)): ?><small class="form-hint">Сейчас курсы не добавлены. Обратитесь к администратору.</small><?php endif; ?>
                    <?php if (!empty($errors['course_name'])): ?><small class="form-error"><?= e($errors['course_name']); ?></small><?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="desired_start_date">Желаемая дата начала</label>
                    <input id="desired_start_date" name="desired_start_date" type="date" value="<?= e(old('desired_start_date')); ?>" min="<?= date('Y-m-d'); ?>" required>
                    <small class="form-hint">Нажмите на поле и выберите дату из календаря.</small>
                    <?php if (!empty($errors['desired_start_date'])): ?><small class="form-error"><?= e($errors['desired_start_date']); ?></small><?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="payment_method">Способ оплаты</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">Выберите способ оплаты</option>
                        <?php foreach ($paymentMethods as $method): ?>
                            <option value="<?= e($method); ?>" <?= old('payment_method') === $method ? 'selected' : ''; ?>><?= e($method); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['payment_method'])): ?><small class="form-error"><?= e($errors['payment_method']); ?></small><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button" <?= empty($courses) ? 'disabled' : ''; ?>>Отправить</button>
            </div>
        </form>
    </div>
</section>
<?php \App\Core\Session::clearOld(); ?>
