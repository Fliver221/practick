<?php
namespace App\Models;

use App\Core\Database;

class User
{
    public static function create(array $data)
    {
        $pdo = Database::pdo();
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("
            INSERT INTO users (login, password_hash, full_name, phone, email, role, created_at, updated_at)
            VALUES (:login, :password_hash, :full_name, :phone, :email, :role, :created_at, :updated_at)
        ");

        $stmt->execute([
            ':login' => $data['login'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':full_name' => $data['full_name'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':role' => isset($data['role']) ? $data['role'] : 'user',
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function findByLoginExact($login)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE BINARY login = :login LIMIT 1");
        $stmt->execute([':login' => $login]);
        return $stmt->fetch() ?: null;
    }

    public static function loginExists($login)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login LIMIT 1");
        $stmt->execute([':login' => $login]);
        return (bool) $stmt->fetch();
    }

    public static function find($id)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch() ?: null;
    }
}
