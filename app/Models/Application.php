<?php

namespace App\Models;

use App\Core\Database;

/**
 * A single job-application submission (the public /apply form). Column
 * names intentionally match the legacy production schema — see
 * origin_db/uhwlqvsp_alnahda.sql — plus applicant_id/status/updated_at
 * added by migration 0005 for the admin + portal features.
 */
class Application
{
    public const STATUSES = ['pending', 'reviewing', 'shortlisted', 'interview', 'approved', 'placed', 'rejected'];

    public const STATUS_LABELS = [
        'pending' => 'Pending Review',
        'reviewing' => 'Under Review',
        'shortlisted' => 'Shortlisted',
        'interview' => 'Interview Scheduled',
        'approved' => 'Approved',
        'placed' => 'Placed',
        'rejected' => 'Not Selected',
    ];

    private const FIELDS = [
        'fullname', 'email', 'weight', 'phone', 'phone2', 'county', 'age',
        'preferredRole', 'gender', 'languages', 'travelledSaudia', 'returnYear',
        'durationYears', 'finishedContract', 'issueWithSponsor', 'contractExplain',
        'deported', 'exitVisa', 'reentryVisa', 'lebanon', 'jordan', 'medicalFit',
        'willingToReturn', 'validPassport', 'validConduct', 'appointmentPreference', 'consent',
    ];

    public static function create(array $data, ?int $applicantId): int
    {
        $pdo = Database::connection();
        $columns = self::FIELDS;
        $placeholders = array_map(fn($c) => ':' . $c, $columns);

        $sql = 'INSERT INTO applications (applicant_id, ' . implode(', ', $columns) . ')
                VALUES (:applicant_id, ' . implode(', ', $placeholders) . ')';
        $stmt = $pdo->prepare($sql);

        $params = ['applicant_id' => $applicantId];
        foreach ($columns as $column) {
            $params[$column] = $data[$column] ?? null;
        }
        $stmt->execute($params);

        return (int) $pdo->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function forApplicant(int $applicantId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE applicant_id = ? ORDER BY submitted_at DESC');
        $stmt->execute([$applicantId]);
        return $stmt->fetchAll();
    }

    /** Restricts lookup to applications owned by the given applicant — used by the portal so URLs can't be guessed. */
    public static function findForApplicant(int $id, int $applicantId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE id = ? AND applicant_id = ?');
        $stmt->execute([$id, $applicantId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * @param array{status?:string,search?:string,county?:string} $filters
     */
    public static function all(array $filters = []): array
    {
        $sql = 'SELECT * FROM applications WHERE 1=1';
        $params = [];

        if (!empty($filters['status']) && in_array($filters['status'], self::STATUSES, true)) {
            $sql .= ' AND status = :status';
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['county'])) {
            $sql .= ' AND county = :county';
            $params['county'] = $filters['county'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (fullname LIKE :search OR email LIKE :search OR phone LIKE :search OR phone2 LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $sql .= ' ORDER BY submitted_at DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            return;
        }
        Database::connection()->prepare('UPDATE applications SET status = ? WHERE id = ?')->execute([$status, $id]);
    }

    public static function counts(): array
    {
        $pdo = Database::connection();
        $counts = [
            'total' => (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn(),
            'this_week' => (int) $pdo->query('SELECT COUNT(*) FROM applications WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')->fetchColumn(),
        ];
        foreach (self::STATUSES as $status) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM applications WHERE status = ?');
            $stmt->execute([$status]);
            $counts[$status] = (int) $stmt->fetchColumn();
        }
        return $counts;
    }

    public static function recent(int $limit = 8): array
    {
        $limit = (int) $limit;
        return Database::connection()
            ->query("SELECT * FROM applications ORDER BY submitted_at DESC LIMIT {$limit}")
            ->fetchAll();
    }
}
