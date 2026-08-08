<?php

namespace App\Models;

use App\Core\Database;

/** A recruitment destination shown in the public "Countries We Recruit To" section. */
class Country
{
    /** Small fallback emoji shown when no flag image has been uploaded yet. */
    private const FLAG_FALLBACK = [
        'oman' => '🇴🇲',
        'saudi arabia' => '🇸🇦',
        'bahrain' => '🇧🇭',
        'kuwait' => '🇰🇼',
        'dubai' => '🇦🇪',
        'united arab emirates' => '🇦🇪',
        'uae' => '🇦🇪',
        'lebanon' => '🇱🇧',
        'qatar' => '🇶🇦',
        'jordan' => '🇯🇴',
    ];

    public static function all(): array
    {
        return Database::connection()->query('SELECT * FROM countries ORDER BY sort_order ASC, name ASC')->fetchAll();
    }

    public static function active(): array
    {
        return Database::connection()
            ->query("SELECT * FROM countries WHERE is_active = 1 ORDER BY sort_order ASC, name ASC")
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM countries WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $fields): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO countries (name, flag_image, cover_image, description, sort_order, is_active)
             VALUES (:name, :flag_image, :cover_image, :description, :sort_order, :is_active)'
        );
        $stmt->execute($fields);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $fields): void
    {
        $fields['id'] = $id;
        Database::connection()->prepare(
            'UPDATE countries SET name = :name, flag_image = :flag_image, cover_image = :cover_image,
                description = :description, sort_order = :sort_order, is_active = :is_active WHERE id = :id'
        )->execute($fields);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM countries WHERE id = ?')->execute([$id]);
    }

    public static function flagFallback(string $name): string
    {
        return self::FLAG_FALLBACK[strtolower(trim($name))] ?? '🌍';
    }
}
