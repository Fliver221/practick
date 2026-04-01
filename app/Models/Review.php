<?php
namespace App\Models;

use App\Core\Database;

class Review
{
    public static function create(array $data)
    {
        $pdo = Database::pdo();
        $now = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("
            INSERT INTO reviews (user_id, application_id, review_text, created_at, updated_at)
            VALUES (:user_id, :application_id, :review_text, :created_at, :updated_at)
        ");

        $stmt->execute([
            ':user_id' => (int) $data['user_id'],
            ':application_id' => (int) $data['application_id'],
            ':review_text' => $data['review_text'],
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function findByApplicationId($applicationId)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE application_id = :application_id LIMIT 1");
        $stmt->execute([':application_id' => (int) $applicationId]);
        return $stmt->fetch() ?: null;
    }
}
