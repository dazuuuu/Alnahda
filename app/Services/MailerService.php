<?php

namespace App\Services;

use App\Core\Env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailerException extends \Exception {}

/**
 * SMTP mailer (PHPMailer) for portal login codes, application-received
 * confirmations, and admin responses to an application. Reads credentials
 * from .env — see .env.example.
 */
class MailerService
{
    /**
     * A misconfigured/unreachable SMTP host must fail fast rather than hang
     * the request for PHPMailer's 300s default — matters most right after
     * .env is first set up with placeholder credentials.
     */
    private static function configured(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Timeout = 10;
        $mail->SMTPKeepAlive = false;
        $mail->Host = Env::get('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = Env::get('MAIL_USERNAME');
        $mail->Password = Env::get('MAIL_PASSWORD');
        $encryption = Env::get('MAIL_ENCRYPTION', 'tls');
        $mail->SMTPSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) Env::get('MAIL_PORT', 587);
        $mail->setFrom(Env::get('MAIL_FROM_ADDRESS', 'no-reply@alnahdaagency.com'), Env::get('MAIL_FROM_NAME', 'Al Nahda Agency'));
        return $mail;
    }

    public static function sendOtp(string $toEmail, string $code, string $purpose = 'login'): void
    {
        $mail = self::configured();
        try {
            $mail->addAddress($toEmail);
            $isReset = $purpose === 'password_reset';
            $mail->isHTML(true);
            $mail->Subject = $isReset ? 'Your Al Nahda Agency password reset code' : 'Your Al Nahda Agency portal login code';
            $mail->Body = self::otpHtml($code, $isReset);
            $mail->AltBody = ($isReset ? 'Your password reset code is: ' : 'Your login code is: ') . $code . ' (expires in 10 minutes).';
            $mail->send();
        } catch (PHPMailerException $e) {
            throw new MailerException('Could not send email: ' . $mail->ErrorInfo);
        }
    }

    /** Sent the moment a public application form submission is saved. */
    public static function sendApplicationReceived(string $toEmail, string $fullName, int $applicationId): void
    {
        $mail = self::configured();
        try {
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'We received your Al Nahda Agency application';
            $mail->Body = self::applicationReceivedHtml($fullName, $applicationId);
            $mail->AltBody = "Thank you, {$fullName}. Your application (Ref #{$applicationId}) has been received by Al Nahda Agency and is being reviewed. We will contact you with updates.";
            $mail->send();
        } catch (PHPMailerException $e) {
            throw new MailerException('Could not send confirmation email: ' . $mail->ErrorInfo);
        }
    }

    /** Sent when an admin adds a note to an application and chooses to notify the applicant by email. */
    public static function sendApplicationMessage(string $toEmail, string $fullName, string $message, string $status): void
    {
        $mail = self::configured();
        try {
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Update on your Al Nahda Agency application';
            $mail->Body = self::applicationMessageHtml($fullName, $message, $status);
            $mail->AltBody = "Hello {$fullName}, you have a new update on your Al Nahda Agency application: {$message}";
            $mail->send();
        } catch (PHPMailerException $e) {
            throw new MailerException('Could not send message email: ' . $mail->ErrorInfo);
        }
    }

    private static function emailShell(string $heading, string $bodyHtml): string
    {
        return '
        <div style="font-family: Arial, sans-serif; background:#f5f7fb; padding:32px;">
          <div style="max-width:460px;margin:0 auto;background:#ffffff;border:1px solid #e3e8f2;border-radius:12px;overflow:hidden;">
            <div style="background:#1c3d7a;padding:20px 24px;">
              <span style="color:#f6a623;font-weight:bold;letter-spacing:2px;font-size:14px;">AL NAHDA AGENCY</span>
            </div>
            <div style="padding:28px 24px;">
              <h1 style="font-size:18px;color:#0f2852;margin:0 0 8px;">' . htmlspecialchars($heading) . '</h1>
              ' . $bodyHtml . '
            </div>
          </div>
        </div>';
    }

    private static function otpHtml(string $code, bool $isReset): string
    {
        $blurb = $isReset
            ? 'Use the code below to verify it\'s you and set a new password.'
            : 'Use the code below to sign in and track your Al Nahda Agency application.';
        return self::emailShell($isReset ? 'Reset your password' : 'Your one-time login code', '
            <p style="font-size:13px;color:#5f6b7a;line-height:1.5;margin:0 0 20px;">' . htmlspecialchars($blurb) . '</p>
            <div style="background:#f5f7fb;border:1px solid #dfe6f5;border-radius:8px;padding:16px;text-align:center;margin-bottom:20px;">
              <span style="font-size:28px;letter-spacing:8px;font-weight:bold;color:#0f2852;">' . htmlspecialchars($code) . '</span>
            </div>
            <p style="font-size:12px;color:#8a93a3;margin:0;">This code expires in 10 minutes. If you didn\'t request this, you can safely ignore this email.</p>');
    }

    private static function applicationReceivedHtml(string $fullName, int $applicationId): string
    {
        return self::emailShell('Application received', '
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 16px;">
              Thank you, <strong>' . htmlspecialchars($fullName) . '</strong>. Your application
              (Ref #' . $applicationId . ') has been received and our recruitment team will review it shortly.
            </p>
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0;">
              You can sign in to your applicant dashboard any time with this email address to track its status
              and read updates from our team.
            </p>');
    }

    private static function applicationMessageHtml(string $fullName, string $message, string $status): string
    {
        return self::emailShell('Update on your application', '
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 6px;">Hello <strong>' . htmlspecialchars($fullName) . '</strong>,</p>
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 16px;">Current status: <strong style="color:#1c3d7a;">' . htmlspecialchars($status) . '</strong></p>
            <div style="background:#f5f7fb;border-left:3px solid #f6a623;border-radius:6px;padding:14px 16px;font-size:13px;color:#273140;line-height:1.6;white-space:pre-line;">' . nl2br(htmlspecialchars($message)) . '</div>
            <p style="font-size:12px;color:#8a93a3;margin-top:18px;">Sign in to your applicant dashboard any time to see the full history of updates.</p>');
    }
}
