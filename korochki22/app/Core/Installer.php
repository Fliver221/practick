<?php
namespace App\Core;

use App\Models\Course;
use App\Models\User;

class Installer
{
    public static function ensureInstalled()
    {
        $pdo = Database::pdo();
        $schemaPath = ROOT_PATH . '/database/schema.sql';
        $sql = file_get_contents($schemaPath);

        if ($sql === false) {
            throw new \RuntimeException('Не удалось прочитать schema.sql');
        }

        $pdo->exec($sql);

        self::ensureCourses();
        self::ensureAdmin();
        self::seedDemoDataIfNeeded();
    }

    private static function ensureCourses()
    {
        $existing = Course::names();
        if (!empty($existing)) {
            return;
        }

        $defaults = app_config('default_courses', []);
        foreach ($defaults as $courseName) {
            Course::create($courseName);
        }
    }

    private static function ensureAdmin()
    {
        $user = User::findByLoginExact('admin');
        if ($user) {
            return;
        }

        User::create([
            'login' => 'admin',
            'password' => 'admin',
            'full_name' => 'Главный администратор',
            'phone' => '8(900)000-00-00',
            'email' => 'admin@korochki.local',
            'role' => 'admin',
        ]);
    }

    private static function seedDemoDataIfNeeded()
    {
        if (!app_config('seed_demo_data')) {
            return;
        }

        $pdo = Database::pdo();

        $countStmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
        $count = (int) $countStmt->fetchColumn();

        if ($count > 0) {
            return;
        }

        $courses = Course::names();
        $courseA = isset($courses[0]) ? $courses[0] : 'Основы алгоритмизации и программирования';
        $courseB = isset($courses[1]) ? $courses[1] : 'Основы веб-дизайна';
        $courseC = isset($courses[2]) ? $courses[2] : 'Основы проектирования баз данных';

        $userId1 = User::create([
            'login' => 'student01',
            'password' => 'password123',
            'full_name' => 'Иванов Иван Иванович',
            'phone' => '8(999)123-45-67',
            'email' => 'student01@example.com',
            'role' => 'user',
        ]);

        $userId2 = User::create([
            'login' => 'student02',
            'password' => 'password123',
            'full_name' => 'Петров Петр Сергеевич',
            'phone' => '8(999)987-65-43',
            'email' => 'student02@example.com',
            'role' => 'user',
        ]);

        $now = date('Y-m-d H:i:s');
        $pdo->prepare("INSERT INTO applications (user_id, course_name, desired_start_date, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([
            $userId1,
            $courseA,
            date('Y-m-d', strtotime('+7 days')),
            'Наличными',
            'Новая',
            $now,
            $now,
        ]);

        $pdo->prepare("INSERT INTO applications (user_id, course_name, desired_start_date, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([
            $userId1,
            $courseB,
            date('Y-m-d', strtotime('+14 days')),
            'Переводом по номеру телефона',
            'Обучение завершено',
            $now,
            $now,
        ]);

        $applicationId = (int) $pdo->lastInsertId();

        $pdo->prepare("INSERT INTO reviews (user_id, application_id, review_text, created_at, updated_at) VALUES (?, ?, ?, ?, ?)")->execute([
            $userId1,
            $applicationId,
            'Курс оказался понятным, материал подан последовательно, преподаватель отвечал на вопросы.',
            $now,
            $now,
        ]);

        $pdo->prepare("INSERT INTO applications (user_id, course_name, desired_start_date, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([
            $userId2,
            $courseC,
            date('Y-m-d', strtotime('+21 days')),
            'Переводом по номеру телефона',
            'Идет обучение',
            $now,
            $now,
        ]);
    }
}
