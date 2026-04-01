<?php
namespace App\Core;

use App\Models\Course;
use App\Models\User;

class Validator
{
    public static function registration(array $data)
    {
        $errors = [];

        $login = trim(isset($data['login']) ? $data['login'] : '');
        $password = isset($data['password']) ? (string) $data['password'] : '';
        $fullName = trim(isset($data['full_name']) ? $data['full_name'] : '');
        $phone = trim(isset($data['phone']) ? $data['phone'] : '');
        $email = trim(isset($data['email']) ? $data['email'] : '');

        if ($login === '') {
            $errors['login'] = 'Введите логин.';
        } elseif (!preg_match('/^[A-Za-z0-9]{6,}$/', $login)) {
            $errors['login'] = 'Логин должен содержать минимум 6 символов и только латиницу/цифры.';
        } elseif (User::loginExists($login)) {
            $errors['login'] = 'Такой логин уже существует.';
        }

        if ($password === '') {
            $errors['password'] = 'Введите пароль.';
        } elseif (mb_strlen($password, 'UTF-8') < 8) {
            $errors['password'] = 'Пароль должен содержать минимум 8 символов.';
        }

        if ($fullName === '') {
            $errors['full_name'] = 'Введите ФИО.';
        } elseif (!preg_match('/^[А-Яа-яЁё\s]+$/u', $fullName)) {
            $errors['full_name'] = 'ФИО должно содержать только кириллицу и пробелы.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Введите телефон.';
        } elseif (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
            $errors['phone'] = 'Телефон должен быть в формате 8(XXX)XXX-XX-XX.';
        }

        if ($email === '') {
            $errors['email'] = 'Введите email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный email.';
        }

        return $errors;
    }

    public static function login(array $data)
    {
        $errors = [];

        if (trim(isset($data['login']) ? $data['login'] : '') === '') {
            $errors['login'] = 'Введите логин.';
        }

        if (trim(isset($data['password']) ? $data['password'] : '') === '') {
            $errors['password'] = 'Введите пароль.';
        }

        return $errors;
    }

    public static function application(array $data)
    {
        $errors = [];

        $course = trim(isset($data['course_name']) ? $data['course_name'] : '');
        $date = trim(isset($data['desired_start_date']) ? $data['desired_start_date'] : '');
        $payment = trim(isset($data['payment_method']) ? $data['payment_method'] : '');

        if ($course === '') {
            $errors['course_name'] = 'Выберите курс.';
        } elseif (!in_array($course, Course::names(), true)) {
            $errors['course_name'] = 'Недопустимое значение курса.';
        }

        if ($date === '') {
            $errors['desired_start_date'] = 'Выберите дату начала обучения.';
        } else {
            $parsed = null;
            $isValid = false;

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $parsed = \DateTime::createFromFormat('Y-m-d', $date);
                $isValid = $parsed && $parsed->format('Y-m-d') === $date;
            } elseif (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $date)) {
                $parsed = \DateTime::createFromFormat('d.m.Y', $date);
                $isValid = $parsed && $parsed->format('d.m.Y') === $date;
            }

            if (!$isValid) {
                $errors['desired_start_date'] = 'Выберите корректную дату начала обучения.';
            } else {
                $today = new \DateTime('today');
                if ($parsed < $today) {
                    $errors['desired_start_date'] = 'Дата начала не может быть раньше сегодняшнего дня.';
                }
            }
        }

        if ($payment === '') {
            $errors['payment_method'] = 'Выберите способ оплаты.';
        } elseif (!in_array($payment, app_config('payment_methods'), true)) {
            $errors['payment_method'] = 'Недопустимый способ оплаты.';
        }

        return $errors;
    }

    public static function review(array $data)
    {
        $errors = [];
        $text = trim(isset($data['review_text']) ? $data['review_text'] : '');

        if ($text === '') {
            $errors['review_text'] = 'Введите текст отзыва.';
        } elseif (mb_strlen($text, 'UTF-8') < 20) {
            $errors['review_text'] = 'Отзыв должен содержать минимум 20 символов.';
        } elseif (mb_strlen($text, 'UTF-8') > 1200) {
            $errors['review_text'] = 'Отзыв не должен превышать 1200 символов.';
        }

        return $errors;
    }

    public static function adminStatus(array $data)
    {
        $errors = [];
        $status = trim(isset($data['status']) ? $data['status'] : '');

        if (!in_array($status, app_config('application_statuses'), true)) {
            $errors['status'] = 'Недопустимый статус.';
        }

        if (empty($data['application_id']) || !ctype_digit((string) $data['application_id'])) {
            $errors['application_id'] = 'Некорректная заявка.';
        }

        return $errors;
    }

    public static function course(array $data)
    {
        $errors = [];
        $name = trim(isset($data['name']) ? $data['name'] : '');

        if ($name === '') {
            $errors['name'] = 'Введите название курса.';
        } elseif (mb_strlen($name, 'UTF-8') < 6) {
            $errors['name'] = 'Название курса должно содержать минимум 6 символов.';
        } elseif (mb_strlen($name, 'UTF-8') > 150) {
            $errors['name'] = 'Название курса не должно превышать 150 символов.';
        } elseif (Course::existsByName($name)) {
            $errors['name'] = 'Такой курс уже существует.';
        }

        return $errors;
    }
}
