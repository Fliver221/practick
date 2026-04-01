<section class="section">
    <div class="container container--medium">
        <div class="page-head">
            <div>
                <div class="eyebrow">Форма отзыва</div>
                <h1>Оцените качество образовательных услуг</h1>
                <p class="page-head__text">
                    Заявка: <strong><?= e($application['course_name']); ?></strong>,
                    дата начала: <strong><?= e(format_date($application['desired_start_date'])); ?></strong>
                </p>
            </div>
            <a href="<?= url('/applications'); ?>" class="button button--ghost">Назад к заявкам</a>
        </div>

        <form method="post" action="<?= url('/reviews/create'); ?>" class="form-card" novalidate>
            <?= csrf_field(); ?>
            <input type="hidden" name="application_id" value="<?= (int) $application['id']; ?>">

            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="review_text">Текст отзыва</label>
                    <textarea id="review_text" name="review_text" rows="8" placeholder="Расскажите, насколько полезным был курс, как был подан материал, что понравилось."><?= e(old('review_text')); ?></textarea>
                    <?php if (!empty($errors['review_text'])): ?><small class="form-error"><?= e($errors['review_text']); ?></small><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Сохранить отзыв</button>
            </div>
        </form>
    </div>
</section>
<?php \App\Core\Session::clearOld(); ?>
