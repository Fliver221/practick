<?php
if (!\App\Core\Auth::check()) {
    header('Location: ' . url('/login'));
    exit;
}
if (\App\Core\Auth::isAdmin()) {
    header('Location: ' . url('/admin/applications'));
    exit;
}

$courseList = (isset($courses) && is_array($courses)) ? $courses : [];
$totalCourses = isset($courseCount) ? (int) $courseCount : count($courseList);
$featuredCourses = array_slice($courseList, 0, 4);
?>
<section class="hero hero--stacked">
    <div class="container container--medium">
        <div class="hero__grid hero__grid--stacked">
            <div class="hero__content">
                <div class="eyebrow">Портал записи на онлайн-курсы ДПО</div>
                <h1>Получайте новую квалификацию без лишней бюрократии</h1>
                <p class="hero__text">
                    Удобная регистрация, понятная подача заявки, прозрачные статусы обучения и
                    возможность оставить отзыв после завершения курса — всё в одном аккуратном интерфейсе.
                </p>

                <div class="hero__actions">
                    <a href="<?= url('/applications/create'); ?>" class="button">Подать заявку</a>
                    <a href="<?= url('/applications'); ?>" class="button button--ghost">Мои заявки</a>
                </div>

                <div class="hero__stats">
                    <div class="stat-card">
                        <strong><?= $totalCourses > 0 ? $totalCourses : 0; ?></strong>
                        <span>программ обучения</span>
                    </div>
                    <div class="stat-card">
                        <strong>24/7</strong>
                        <span>доступ к заявкам</span>
                    </div>
                    <div class="stat-card">
                        <strong>1</strong>
                        <span>личный кабинет</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container container--wide">
        <div class="hero__slider hero__slider--wide">
            <div class="slider" data-slider>
                <div class="slider__track">
                    <div class="slider__slide is-active">
                        <img src="<?= e(embedded_asset('assets/img/slides/photo-slide-1.jpg')); ?>" alt="Современная аудитория для онлайн-обучения">
                        <div class="slider__overlay">
                            <span class="slider__tag">Комфортное обучение</span>
                            <h3>Современные аудитории и понятная организация обучения</h3>
                            <p>Запись на курс, контроль статуса и обратная связь собраны в одном аккуратном интерфейсе.</p>
                        </div>
                    </div>
                    <div class="slider__slide">
                        <img src="<?= e(embedded_asset('assets/img/slides/photo-slide-2.jpg')); ?>" alt="Светлая учебная аудитория">
                        <div class="slider__overlay">
                            <span class="slider__tag">Гибкий формат</span>
                            <h3>Подходящая среда для профессионального роста</h3>
                            <p>Подавайте заявку онлайн и отслеживайте путь от новой заявки до завершения обучения.</p>
                        </div>
                    </div>
                    <div class="slider__slide">
                        <img src="<?= e(embedded_asset('assets/img/slides/photo-slide-3.jpg')); ?>" alt="Компьютерный класс для обучения">
                        <div class="slider__overlay">
                            <span class="slider__tag">Практические навыки</span>
                            <h3>Курсы, ориентированные на реальные задачи</h3>
                            <p>Доступные программы, удобный личный кабинет и отзыв после окончания курса без лишних шагов.</p>
                        </div>
                    </div>
                    <div class="slider__slide">
                        <img src="<?= e(embedded_asset('assets/img/slides/photo-slide-4.jpg')); ?>" alt="Учебный класс для дополнительного образования">
                        <div class="slider__overlay">
                            <span class="slider__tag">Дополнительное образование</span>
                            <h3>Единый портал для заявок, статусов и отзывов</h3>
                            <p>Все ключевые действия пользователя и администратора работают через связанную базу данных.</p>
                        </div>
                    </div>
                </div>

                <button class="slider__button slider__button--prev" type="button" data-slider-prev aria-label="Предыдущий слайд">‹</button>
                <button class="slider__button slider__button--next" type="button" data-slider-next aria-label="Следующий слайд">›</button>

                <div class="slider__dots" data-slider-dots></div>
            </div>
        </div>
    </div>
</section>

<section class="section section--tight-top">
    <div class="container">
        <div class="section-heading">
            <div class="eyebrow">Как это работает</div>
            <h2>Логика сервиса без лишних шагов</h2>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-card__icon">01</div>
                <h3>Подача заявки</h3>
                <p>Выберите курс, дату старта и способ оплаты. Заявка сразу уходит на рассмотрение администратору.</p>
            </article>

            <article class="feature-card">
                <div class="feature-card__icon">02</div>
                <h3>Контроль статуса</h3>
                <p>В личном кабинете видно текущее состояние заявки: новая, обучение в процессе или завершено.</p>
            </article>

            <article class="feature-card">
                <div class="feature-card__icon">03</div>
                <h3>Отзыв после обучения</h3>
                <p>После окончания обучения можно оставить отзыв только по своей завершённой заявке, без путаницы.</p>
            </article>

            <article class="feature-card">
                <div class="feature-card__icon">04</div>
                <h3>Личный кабинет</h3>
                <p>Все ваши заявки, даты, способы оплаты и статусы собраны в одном месте и сохраняются в базе данных.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div>
                <div class="eyebrow">Доступные программы</div>
                <h2>Курсы, доступные для записи</h2>
            </div>
            <?php if (!empty($courseList)): ?>
                <div class="course-pill-list">
                    <?php foreach (array_slice($courseList, 0, 3) as $quickCourse): ?>
                        <span class="course-pill"><?= e($quickCourse); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($featuredCourses)): ?>
            <div class="course-list course-list--featured">
                <?php foreach ($featuredCourses as $index => $course): ?>
                    <article class="course-card course-card--featured">
                        <span class="course-card__number">0<?= $index + 1; ?></span>
                        <h3><?= e($course); ?></h3>
                        <p>Онлайн-формат, понятная структура, прозрачная запись и удобное администрирование процесса обучения.</p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>Курсы пока не добавлены</h2>
                <p>Администратор может добавить новые направления обучения в панели управления, после чего они сразу появятся здесь и в форме заявки.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
