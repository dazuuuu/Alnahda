<?php

namespace App\Core;

use App\Models\Applicant;

/**
 * Session for the applicant-facing portal (job seekers tracking their
 * application). Mirrors AdminSession's shape but backed by the `applicants`
 * table and an OTP (no password) login flow — see Portal\AuthController.
 */
class ApplicantSession
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('alnahda_applicant');
            session_start();
        }
    }

    public static function login(int $applicantId): void
    {
        session_regenerate_id(true);
        $_SESSION['applicant_id'] = $applicantId;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function current(): ?array
    {
        if (empty($_SESSION['applicant_id'])) {
            return null;
        }
        return Applicant::find((int) $_SESSION['applicant_id']);
    }

    /** Redirects to the portal login route if no applicant is signed in. */
    public static function require(): array
    {
        $applicant = self::current();
        if (!$applicant) {
            header('Location: ' . Url::to('/portal/login'));
            exit;
        }
        return $applicant;
    }
}
