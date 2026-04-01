<section class="section">
    <div class="container">
        <div class="page-head">
            <div>
                <div class="eyebrow">Личный кабинет</div>
                <h1>Мои заявки</h1>
                <p class="page-head__text">Здесь отображаются только ваши заявки и доступные действия по ним.</p>
            </div>
            <a href="<?= url('/applications/create'); ?>" class="button">Новая заявка</a>
        </div>

        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <h2>Пока заявок нет</h2>
                <p>Создайте первую заявку на обучение — она сразу появится в вашем списке.</p>
                <a href="<?= url('/applications/create'); ?>" class="button">Подать заявку</a>
            </div>
        <?php else: ?>
            <div class="applications-grid">
                <?php foreach ($applications as $application): ?>
                    <article class="application-card">
                        <div class="application-card__head">
                            <h2><?= e($application['course_name']); ?></h2>
                            <span class="<?= e(status_badge_class($application['status'])); ?>"><?= e($application['status']); ?></span>
                        </div>

                        <dl class="info-list">
                            <div>
                                <dt>Дата начала</dt>
                                <dd><?= e(format_date($application['desired_start_date'])); ?></dd>
                            </div>
                            <div>
                                <dt>Оплата</dt>
                                <dd><?= e($application['payment_method']); ?></dd>
                            </div>
                            <div>
                                <dt>Создано</dt>
                                <dd><?= e(format_datetime($application['created_at'])); ?></dd>
                            </div>
                        </dl>

                        <div class="application-card__review">
                            <?php if (!empty($application['review_id'])): ?>
                                <div class="review-preview">
                                    <strong>Ваш отзыв</strong>
                                    <p><?= nl2br(e($application['review_text'])); ?></p>
                                </div>
                            <?php elseif ($application['status'] === 'Обучение завершено'): ?>
                                <a href="<?= url('/reviews/create?application_id=' . (int) $application['id']); ?>" class="button button--ghost">Оставить отзыв</a>
                            <?php else: ?>
                                <button type="button" class="button button--disabled" disabled>Отзыв будет доступен после завершения обучения</button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
