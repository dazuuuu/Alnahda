<?php

namespace App\Core;

use App\Models\Admin;

class AdminSession
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('alnahda_admin');
            session_start();
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        $admin = Admin::findByEmail($email);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $admin['id'];
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /** Re-fetched from the database on every call so role/permission edits apply immediately, no re-login needed. */
    public static function current(): ?array
    {
        if (empty($_SESSION['admin_id'])) {
            return null;
        }
        $admin = Admin::find((int) $_SESSION['admin_id']);
        if (!$admin) {
            unset($_SESSION['admin_id']);
            return null;
        }
        return $admin;
    }

    /** Redirects to the admin login route if no admin is signed in. */
    public static function require(): array
    {
        $admin = self::current();
        if (!$admin) {
            header('Location: ' . Url::to('/admin/login'));
            exit;
        }
        return $admin;
    }
}
