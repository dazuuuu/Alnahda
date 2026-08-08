<?php

namespace App\Services;

use App\Models\OtpCode;

/**
 * Business-logic layer over OtpCode (pure data access) + MailerService
 * (delivery) — used by Portal\AuthController for email-based portal login.
 */
class OtpService
{
    /** @throws MailerException */
    public static function issueAndSend(int $applicantId, string $email, string $purpose = 'login'): void
    {
        $code = OtpCode::generate($applicantId, $purpose);
        MailerService::sendOtp($email, $code, $purpose);
    }

    public static function verify(int $applicantId, string $code, string $purpose = 'login'): bool
    {
        return OtpCode::verify($applicantId, $code, $purpose);
    }
}
