<?php
namespace App\Models;

use App\Core\Database;

class Application
{
    public static function create(array $data)
    {
        $pdo = Database::pdo();
        $now = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("
            INSERT INTO applications (user_id, course_name, desired_start_date, payment_method, status, created_at, updated_at)
            VALUES (:user_id, :course_name, :desired_start_date, :payment_method, :status, :created_at, :updated_at)
        ");

        $stmt->execute([
            ':user_id' => (int) $data['user_id'],
            ':course_name' => $data['course_name'],
            ':desired_start_date' => $data['desired_start_date'],
            ':payment_method' => $data['payment_method'],
            ':status' => isset($data['status']) ? $data['status'] : 'Новая',
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function allByUserId($userId)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("
            SELECT a.*, r.id AS review_id, r.review_text, r.created_at AS review_created_at
            FROM applications a
            LEFT JOIN reviews r ON r.application_id = a.id
            WHERE a.user_id = :user_id
            ORDER BY a.created_at DESC, a.id DESC
        ");
        $stmt->execute([':user_id' => (int) $userId]);
        return $stmt->fetchAll();
    }

    public static function findOwnedByUser($applicationId, $userId)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->execute([
            ':id' => (int) $applicationId,
            ':user_id' => (int) $userId,
        ]);
        return $stmt->fetch() ?: null;
    }

    public static function findWithUser($id)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("
            SELECT a.*, u.login, u.full_name, u.phone, u.email
            FROM applications a
            INNER JOIN users u ON u.id = a.user_id
            WHERE a.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateStatus($id, $status)
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE applications SET status = :status, updated_at = :updated_at WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':updated_at' => date('Y-m-d H:i:s'),
            ':id' => (int) $id,
        ]);
    }

    public static function paginateForAdmin(array $filters, $page = 1, $perPage = 8)
    {
        $pdo = Database::pdo();
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'a.status = :status';
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(u.login LIKE :search OR u.full_name LIKE :search OR a.course_name LIKE :search)';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM applications a
            INNER JOIN users u ON u.id = a.user_id
            {$sqlWhere}
        ");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql = "
            SELECT a.*, u.login, u.full_name, u.phone, u.email, r.id AS review_id
            FROM applications a
            INNER JOIN users u ON u.id = a.user_id
            LEFT JOIN reviews r ON r.application_id = a.id
            {$sqlWhere}
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => (int) ceil($total / $perPage),
            'per_page' => $perPage,
        ];
    }
}
