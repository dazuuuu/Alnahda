<?php

namespace App\Models;

use App\Core\Database;

class Testimonial
{
    public const STATUSES = ['pending', 'approved', 'rejected'];

    public static function approved(int $limit = 12): array
    {
        $limit = (int) $limit;
        return Database::connection()
            ->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY approved_at DESC LIMIT {$limit}")
            ->fetchAll();
    }

    public static function all(?string $status = null): array
    {
        $pdo = Database::connection();
        if ($status && in_array($status, self::STATUSES, true)) {
            $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE status = ? ORDER BY created_at DESC');
            $stmt->execute([$status]);
            return $stmt->fetchAll();
        }
        return $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM testimonials WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function forApplicant(int $applicantId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM testimonials WHERE applicant_id = ? ORDER BY created_at DESC');
        $stmt->execute([$applicantId]);
        return $stmt->fetchAll();
    }

    public static function create(?int $applicantId, string $authorName, ?string $authorRole, int $rating, string $message): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO testimonials (applicant_id, author_name, author_role, rating, message)
             VALUES (:applicant_id, :author_name, :author_role, :rating, :message)'
        );
        $stmt->execute([
            'applicant_id' => $applicantId,
            'author_name' => $authorName,
            'author_role' => $authorRole,
            'rating' => max(1, min(5, $rating)),
            'message' => $message,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function updateStatus(int $id, string $status, int $adminId): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            return;
        }
        Database::connection()->prepare(
            "UPDATE testimonials SET status = ?, reviewed_by = ?, approved_at = IF(? = 'approved', NOW(), approved_at) WHERE id = ?"
        )->execute([$status, $adminId, $status, $id]);
    }

    public static function counts(): array
    {
        $pdo = Database::connection();
        $counts = ['total' => (int) $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn()];
        foreach (self::STATUSES as $status) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM testimonials WHERE status = ?');
            $stmt->execute([$status]);
            $counts[$status] = (int) $stmt->fetchColumn();
        }
        return $counts;
    }
}
