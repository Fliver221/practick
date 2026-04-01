<?php
return [
    'name' => 'Корочки.есть',
    'base_url' => '',
    'seed_demo_data' => false,
    'admin_fallback' => [
        'login' => 'Admin',
        'password' => 'KorokNET',
        'db_login' => 'admin',
    ],
    'default_courses' => [
        'Основы алгоритмизации и программирования',
        'Основы веб-дизайна',
        'Основы проектирования баз данных',
    ],
    'payment_methods' => [
        'Наличными',
        'Переводом по номеру телефона',
    ],
    'application_statuses' => [
        'Новая',
        'Идет обучение',
        'Обучение завершено',
    ],
];
