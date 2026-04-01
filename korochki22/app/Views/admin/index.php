<section class="section">
    <div class="container container--wide">
        <div class="page-head">
            <div>
                <div class="eyebrow">Роль: администратор</div>
                <h1>Панель администратора</h1>
                <p class="page-head__text">Просмотр всех заявок, фильтрация, поиск, пагинация, изменение статусов и управление курсами.</p>
            </div>
        </div>

        <div class="admin-top-grid">
            <form method="post" action="<?= url('/admin/courses'); ?>" class="form-card admin-course-card">
                <?= csrf_field(); ?>
                <div class="admin-course-card__head">
                    <div>
                        <h2>Добавить новый курс</h2>
                        <p>После добавления курс сразу появится в форме подачи заявки у пользователей.</p>
                    </div>
                </div>

                <div class="admin-course-form">
                    <div class="form-field">
                        <label for="admin_course_name">Название курса</label>
                        <input id="admin_course_name" type="text" name="name" value="<?= e(old('course_name')); ?>" placeholder="Например: Основы тестирования ПО" maxlength="150" required>
                    </div>
                    <button type="submit" class="button">Добавить курс</button>
                </div>

                <div class="admin-course-list">
                    <?php foreach ($courses as $course): ?>
                        <span class="course-chip"><?= e($course['name']); ?></span>
                    <?php endforeach; ?>
                </div>
            </form>

            <form method="get" action="<?= url('/admin/applications'); ?>" class="filter-bar filter-bar--admin">
                <div class="form-field">
                    <label for="search">Поиск</label>
                    <input id="search" type="text" name="search" value="<?= e($filters['search']); ?>" placeholder="Логин, ФИО или курс">
                </div>

                <div class="form-field">
                    <label for="status">Фильтр по статусу</label>
                    <select id="status" name="status">
                        <option value="">Все статусы</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status); ?>" <?= $filters['status'] === $status ? 'selected' : ''; ?>><?= e($status); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-bar__actions">
                    <button type="submit" class="button">Применить</button>
                    <a href="<?= url('/admin/applications'); ?>" class="button button--ghost">Сбросить</a>
                </div>
            </form>
        </div>

        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <h2>Ничего не найдено</h2>
                <p>Сейчас нет заявок, соответствующих выбранным параметрам поиска.</p>
            </div>
        <?php else: ?>
            <div class="table-card">
                <div class="table-scroll table-scroll--desktop">
                    <table class="data-table data-table--admin">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Курс</th>
                            <th>Старт</th>
                            <th>Оплата</th>
                            <th>Статус</th>
                            <th>Создано</th>
                            <th>Отзыв</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td data-label="ID"><?= (int) $application['id']; ?></td>
                                <td data-label="Пользователь">
                                    <div class="user-mini">
                                        <strong><?= e($application['full_name']); ?></strong>
                                        <span><?= e($application['login']); ?></span>
                                        <span><?= e($application['phone']); ?></span>
                                    </div>
                                </td>
                                <td data-label="Курс"><?= e($application['course_name']); ?></td>
                                <td data-label="Старт"><?= e(format_date($application['desired_start_date'])); ?></td>
                                <td data-label="Оплата"><?= e($application['payment_method']); ?></td>
                                <td data-label="Статус">
                                    <span class="<?= e(status_badge_class($application['status'])); ?>"><?= e($application['status']); ?></span>
                                </td>
                                <td data-label="Создано"><?= e(format_datetime($application['created_at'])); ?></td>
                                <td data-label="Отзыв"><?= !empty($application['review_id']) ? 'Есть' : 'Нет'; ?></td>
                                <td data-label="Действие">
                                    <form method="post" action="<?= url('/admin/applications/status'); ?>" class="inline-status-form">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="application_id" value="<?= (int) $application['id']; ?>">
                                        <input type="hidden" name="return_status" value="<?= e($filters['status']); ?>">
                                        <input type="hidden" name="return_search" value="<?= e($filters['search']); ?>">
                                        <input type="hidden" name="return_page" value="<?= (int) $pagination['page']; ?>">

                                        <select name="status">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= e($status); ?>" <?= $application['status'] === $status ? 'selected' : ''; ?>><?= e($status); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="button button--small">Сохранить</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($pagination['pages'] > 1): ?>
                <nav class="pagination" aria-label="Пагинация">
                    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                        <?php
                        $query = array_filter([
                            'status' => $filters['status'],
                            'search' => $filters['search'],
                            'page' => $i,
                        ], function ($value) {
                            return $value !== '';
                        });
                        ?>
                        <a class="pagination__link <?= $i === (int) $pagination['page'] ? 'is-current' : ''; ?>"
                           href="<?= url('/admin/applications?' . http_build_query($query)); ?>">
                            <?= $i; ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php \App\Core\Session::clearOld(); ?>
