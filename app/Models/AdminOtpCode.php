<?php

namespace App\Models;

use App\Core\Database;

/** OTP codes for the admin "forgot password" flow — see Admin\AuthController. */
class AdminOtpCode
{
    public static function generate(int $adminId, string $purpose = 'password_reset'): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Database::connection()
            ->prepare('INSERT INTO admin_otp_codes (admin_id, code, purpose, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))')
            ->execute([$adminId, password_hash($code, PASSWORD_DEFAULT), $purpose]);
        return $code;
    }

    public static function verify(int $adminId, string $code, string $purpose = 'password_reset'): bool
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM admin_otp_codes WHERE admin_id = ? AND purpose = ? AND used_at IS NULL AND expires_at > NOW() ORDER BY id DESC LIMIT 5'
        );
        $stmt->execute([$adminId, $purpose]);
        foreach ($stmt->fetchAll() as $row) {
            if (password_verify($code, $row['code'])) {
                Database::connection()->prepare('UPDATE admin_otp_codes SET used_at = NOW() WHERE id = ?')->execute([$row['id']]);
                return true;
            }
        }
        return false;
    }
}
