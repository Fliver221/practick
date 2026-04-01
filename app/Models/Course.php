<?php
namespace App\Models;

use App\Core\Database;

class Course
{
    public static function all()
    {
        $pdo = Database::pdo();
        $stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC, id DESC");
        return $stmt->fetchAll();
    }

    public static function names()
    {
        $pdo = Database::pdo();
        $stmt = $pdo->query("SELECT name FROM courses ORDER BY name ASC");
        $rows = $stmt->fetchAll();
        return array_map(function ($row) {
            return $row['name'];
        }, $rows);
    }

    public static function existsByName($name)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE LOWER(name) = LOWER(:name) LIMIT 1");
        $stmt->execute([':name' => trim((string) $name)]);
        return (bool) $stmt->fetchColumn();
    }

    public static function create($name)
    {
        $pdo = Database::pdo();
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO courses (name, created_at, updated_at) VALUES (:name, :created_at, :updated_at)");
        $stmt->execute([
            ':name' => trim((string) $name),
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
        return (int) $pdo->lastInsertId();
    }
}
