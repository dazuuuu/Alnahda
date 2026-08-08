<?php

namespace App\Models;

use App\Core\Database;

/**
 * A note an admin leaves on an application. Always visible on the
 * applicant's portal dashboard; optionally also emailed to them the
 * moment it's created (notified_at is set when that happens).
 */
class ApplicationNote
{
    public static function create(int $applicationId, ?int $applicantId, ?int $adminId, string $message, bool $notified): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO application_notes (application_id, applicant_id, admin_id, message, notified_at)
             VALUES (:application_id, :applicant_id, :admin_id, :message, :notified_at)'
        );
        $stmt->execute([
            'application_id' => $applicationId,
            'applicant_id' => $applicantId,
            'admin_id' => $adminId,
            'message' => $message,
            'notified_at' => $notified ? date('Y-m-d H:i:s') : null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function forApplication(int $applicationId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT n.*, a.email AS admin_email
             FROM application_notes n
             LEFT JOIN admins a ON a.id = n.admin_id
             WHERE n.application_id = ?
             ORDER BY n.created_at ASC'
        );
        $stmt->execute([$applicationId]);
        return $stmt->fetchAll();
    }

    /** All notes across every application belonging to this applicant, most recent first. */
    public static function forApplicant(int $applicantId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT n.*, ap.preferredRole, ap.fullname
             FROM application_notes n
             JOIN applications ap ON ap.id = n.application_id
             WHERE n.applicant_id = ?
             ORDER BY n.created_at DESC'
        );
        $stmt->execute([$applicantId]);
        return $stmt->fetchAll();
    }

    public static function markReadForApplicant(int $applicationId, int $applicantId): void
    {
        Database::connection()
            ->prepare('UPDATE application_notes SET read_at = NOW() WHERE application_id = ? AND applicant_id = ? AND read_at IS NULL')
            ->execute([$applicationId, $applicantId]);
    }

    public static function unreadCountForApplicant(int $applicantId): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM application_notes WHERE applicant_id = ? AND read_at IS NULL');
        $stmt->execute([$applicantId]);
        return (int) $stmt->fetchColumn();
    }

    /** @return array<int,int> application_id => note count, for annotating the admin applications list. */
    public static function countsByApplication(): array
    {
        $rows = Database::connection()
            ->query('SELECT application_id, COUNT(*) AS c FROM application_notes GROUP BY application_id')
            ->fetchAll();
        $counts = [];
        foreach ($rows as $row) {
            $counts[(int) $row['application_id']] = (int) $row['c'];
        }
        return $counts;
    }
}
