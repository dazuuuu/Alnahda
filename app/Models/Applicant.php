<?php

namespace App\Models;

use App\Core\Database;
use PDOException;

/**
 * A portal account for a job seeker. Created automatically the first time
 * someone submits the public application form (see Api\ApplicationController)
 * so they can sign in later — with the same email — to track it.
 */
class Applicant
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applicants WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByIdentifier(string $type, string $value): ?array
    {
        $column = $type === 'email' ? 'email' : 'phone';
        $stmt = Database::connection()->prepare("SELECT * FROM applicants WHERE $column = ?");
        $stmt->execute([$value]);
        return $stmt->fetch() ?: null;
    }

    public static function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    public static function markEmailVerified(int $id): void
    {
        Database::connection()
            ->prepare('UPDATE applicants SET email_verified_at = COALESCE(email_verified_at, NOW()) WHERE id = ?')
            ->execute([$id]);
    }

    /**
     * Finds an existing applicant by email then phone, or creates a new one —
     * used when a job application is submitted, so the same person always
     * lands on the same portal account no matter how many times they apply.
     */
    public static function findOrCreateFromApplication(string $email, string $phone, string $fullName): int
    {
        $pdo = Database::connection();
        $email = trim($email);
        $phone = $phone !== '' ? self::normalizePhone($phone) : '';

        $applicant = $email !== '' ? self::findByIdentifier('email', $email) : null;
        if (!$applicant && $phone !== '') {
            $applicant = self::findByIdentifier('phone', $phone);
        }

        if ($applicant) {
            if (empty($applicant['full_name']) && $fullName !== '') {
                $pdo->prepare('UPDATE applicants SET full_name = ? WHERE id = ?')->execute([$fullName, $applicant['id']]);
            }
            if ($phone !== '' && empty($applicant['phone'])) {
                try {
                    $pdo->prepare('UPDATE applicants SET phone = ? WHERE id = ?')->execute([$phone, $applicant['id']]);
                } catch (PDOException $e) {
                    // phone already used by a different applicant — leave as-is.
                }
            }
            return (int) $applicant['id'];
        }

        $stmt = $pdo->prepare('INSERT INTO applicants (full_name, email, phone) VALUES (?, ?, ?)');
        $stmt->execute([
            $fullName !== '' ? $fullName : null,
            $email !== '' ? $email : null,
            $phone !== '' ? $phone : null,
        ]);
        return (int) $pdo->lastInsertId();
    }
}
