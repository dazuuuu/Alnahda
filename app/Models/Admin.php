<?php

namespace App\Models;

use App\Core\Database;

class Admin
{
    public const ROLES = ['super_admin', 'junior_admin'];

    /**
     * Feature keys that can be granted to a 'junior_admin'. Super admins
     * always have every permission implicitly — this list only matters for
     * junior admins. Add a new entry here whenever a new admin feature is built.
     */
    public const PERMISSIONS = [
        'applications' => 'Applications — view, update status, and message applicants',
        'testimonials' => 'Testimonials — approve or reject applicant reviews',
        'countries' => 'Countries — manage the destinations shown on the website',
    ];

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        return self::decode($stmt->fetch() ?: null);
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->execute([$id]);
        return self::decode($stmt->fetch() ?: null);
    }

    public static function all(): array
    {
        $rows = Database::connection()->query('SELECT * FROM admins ORDER BY created_at ASC')->fetchAll();
        return array_map([self::class, 'decode'], $rows);
    }

    public static function countSuperAdmins(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM admins WHERE role = 'super_admin'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /** @param string[] $permissions Only used when $role is 'junior_admin'. */
    public static function create(string $email, string $password, string $name, string $role, array $permissions, ?int $createdBy): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO admins (email, name, password_hash, role, permissions, created_by)
             VALUES (:email, :name, :password_hash, :role, :permissions, :created_by)'
        );
        $stmt->execute([
            'email' => $email,
            'name' => $name !== '' ? $name : null,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => in_array($role, self::ROLES, true) ? $role : 'junior_admin',
            'permissions' => $role === 'junior_admin' ? json_encode(array_values($permissions)) : null,
            'created_by' => $createdBy,
        ]);
        return (int) $pdo->lastInsertId();
    }

    /** @param string[] $permissions Only used when $role is 'junior_admin'. */
    public static function updateRoleAndProfile(int $id, string $email, string $name, string $role, array $permissions): void
    {
        Database::connection()->prepare(
            'UPDATE admins SET email = :email, name = :name, role = :role, permissions = :permissions WHERE id = :id'
        )->execute([
            'email' => $email,
            'name' => $name !== '' ? $name : null,
            'role' => in_array($role, self::ROLES, true) ? $role : 'junior_admin',
            'permissions' => $role === 'junior_admin' ? json_encode(array_values($permissions)) : null,
            'id' => $id,
        ]);
    }

    public static function updatePassword(int $id, string $password): void
    {
        Database::connection()
            ->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
    }

    /** Super admins can do everything; junior admins only what's in their `permissions` list. */
    public static function hasPermission(?array $admin, string $key): bool
    {
        if (!$admin) {
            return false;
        }
        if (($admin['role'] ?? 'super_admin') === 'super_admin') {
            return true;
        }
        return in_array($key, $admin['permissions'] ?? [], true);
    }

    public static function isSuperAdmin(?array $admin): bool
    {
        return ($admin['role'] ?? 'super_admin') === 'super_admin';
    }

    private static function decode(?array $row): ?array
    {
        if (!$row) {
            return null;
        }
        $row['role'] = $row['role'] ?? 'super_admin';
        $row['permissions'] = $row['permissions'] ? (json_decode($row['permissions'], true) ?: []) : [];
        return $row;
    }
}
