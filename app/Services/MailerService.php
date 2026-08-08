<?php

namespace App\Services;

use App\Core\Env;
use App\Core\Url;
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
     * Build a PHPMailer instance from .env. Throws early with a clear message
     * when SMTP is not configured, instead of failing deep inside a connect.
     */
    private static function configured(): PHPMailer
    {
        $host = trim((string) Env::get('MAIL_HOST', ''));
        $username = trim((string) Env::get('MAIL_USERNAME', ''));
        $password = self::normalizePassword((string) Env::get('MAIL_PASSWORD', ''));
        $encryption = strtolower(trim((string) Env::get('MAIL_ENCRYPTION', 'tls')));
        $port = (int) Env::get('MAIL_PORT', 0);

        if ($host === '' || $username === '' || $password === '') {
            throw new MailerException(
                'SMTP is not configured. Set MAIL_HOST, MAIL_USERNAME, and MAIL_PASSWORD in .env '
                . '(for Gmail use smtp.gmail.com + an App Password; quote passwords that contain spaces).'
            );
        }

        // Sensible defaults when only encryption is set.
        if ($port <= 0) {
            $port = $encryption === 'ssl' || $encryption === 'smtps' ? 465 : 587;
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $mail->Timeout = 20;
        $mail->SMTPKeepAlive = false;
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->AuthType = 'LOGIN';

        if ($encryption === 'ssl' || $encryption === 'smtps') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAutoTLS = true;
        } elseif ($encryption === 'none' || $encryption === 'false' || $encryption === 'off') {
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
        } else {
            // tls / starttls / anything else → STARTTLS (Gmail port 587)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAutoTLS = true;
        }

        // Local AMPPS / broken CA stores: set MAIL_SSL_VERIFY=0 in .env
        if (Env::get('MAIL_SSL_VERIFY', '1') === '0') {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
        }

        if (Env::get('MAIL_DEBUG', '0') === '1') {
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = static function (string $str): void {
                error_log('[smtp] ' . trim($str));
            };
        }

        // Gmail requires From to be the authenticated account (or a verified alias).
        $fromAddress = trim((string) Env::get('MAIL_FROM_ADDRESS', ''));
        if ($fromAddress === '' || !filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            $fromAddress = $username;
        }
        $fromName = trim((string) Env::get('MAIL_FROM_NAME', Env::get('APP_NAME', 'Al Nahda Agency')));

        $mail->setFrom($fromAddress, $fromName);
        $mail->Sender = $username;
        $mail->addReplyTo($fromAddress, $fromName);

        return $mail;
    }

    /**
     * Gmail App Passwords are often pasted with spaces ("xxxx xxxx xxxx xxxx").
     * Strip those spaces so auth works whether or not the value was quoted.
     */
    private static function normalizePassword(string $password): string
    {
        $password = trim($password);
        $compact = preg_replace('/\s+/', '', $password) ?? $password;
        if (preg_match('/^[a-zA-Z0-9]{16}$/', $compact)) {
            return $compact;
        }
        return $password;
    }

    private static function sendConfigured(PHPMailer $mail, string $failurePrefix): void
    {
        try {
            $mail->send();
        } catch (PHPMailerException $e) {
            $detail = trim($mail->ErrorInfo !== '' ? $mail->ErrorInfo : $e->getMessage());
            error_log('[mailer] ' . $failurePrefix . ': ' . $detail);
            throw new MailerException($failurePrefix . ': ' . $detail, 0, $e);
        }
    }

    public static function sendOtp(string $toEmail, string $code, string $purpose = 'login'): void
    {
        $mail = self::configured();
        $isReset = $purpose === 'password_reset';
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = $isReset ? 'Your Al Nahda Agency password reset code' : 'Your Al Nahda Agency portal login code';
        $mail->Body = self::otpHtml($code, $isReset);
        $mail->AltBody = ($isReset ? 'Your password reset code is: ' : 'Your login code is: ') . $code . ' (expires in 10 minutes).';
        self::sendConfigured($mail, 'Could not send email');
    }

    /** Sent the moment a public application form submission is saved. */
    public static function sendApplicationReceived(string $toEmail, string $fullName, int $applicationId): void
    {
        $mail = self::configured();
        $portalUrl = Url::absolute('/portal/login');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'We received your Al Nahda Agency application';
        $mail->Body = self::applicationReceivedHtml($fullName, $applicationId, $portalUrl);
        $mail->AltBody = "Thank you, {$fullName}. Your application (Ref #{$applicationId}) has been received by Al Nahda Agency. "
            . "Sign in at {$portalUrl} with this email to track your status — we will email you a one-time login code.";
        self::sendConfigured($mail, 'Could not send confirmation email');
    }

    /** Sent when an admin adds a note to an application and chooses to notify the applicant by email. */
    public static function sendApplicationMessage(string $toEmail, string $fullName, string $message, string $status): void
    {
        $mail = self::configured();
        $portalUrl = Url::absolute('/portal/login');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Update on your Al Nahda Agency application';
        $mail->Body = self::applicationMessageHtml($fullName, $message, $status, $portalUrl);
        $mail->AltBody = "Hello {$fullName}, you have a new update on your Al Nahda Agency application: {$message} "
            . "Sign in at {$portalUrl} to see full history.";
        self::sendConfigured($mail, 'Could not send message email');
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

    private static function applicationReceivedHtml(string $fullName, int $applicationId, string $portalUrl): string
    {
        return self::emailShell('Application received', '
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 16px;">
              Thank you, <strong>' . htmlspecialchars($fullName) . '</strong>. Your application
              (Ref #' . (int) $applicationId . ') has been received and our recruitment team will review it shortly.
            </p>
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 16px;">
              An applicant account was created for this email. Sign in any time to track status and read updates from our team:
            </p>
            <p style="margin:0 0 8px;">
              <a href="' . htmlspecialchars($portalUrl) . '" style="display:inline-block;background:#1c3d7a;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:6px;font-size:13px;font-weight:bold;">
                Open applicant portal
              </a>
            </p>
            <p style="font-size:12px;color:#8a93a3;margin:12px 0 0;">
              Use this same email address — we will send you a one-time login code when you sign in.
            </p>');
    }

    private static function applicationMessageHtml(string $fullName, string $message, string $status, string $portalUrl): string
    {
        return self::emailShell('Update on your application', '
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 6px;">Hello <strong>' . htmlspecialchars($fullName) . '</strong>,</p>
            <p style="font-size:13px;color:#5f6b7a;line-height:1.6;margin:0 0 16px;">Current status: <strong style="color:#1c3d7a;">' . htmlspecialchars($status) . '</strong></p>
            <div style="background:#f5f7fb;border-left:3px solid #f6a623;border-radius:6px;padding:14px 16px;font-size:13px;color:#273140;line-height:1.6;white-space:pre-line;">' . nl2br(htmlspecialchars($message)) . '</div>
            <p style="font-size:12px;color:#8a93a3;margin-top:18px;">
              <a href="' . htmlspecialchars($portalUrl) . '" style="color:#1c3d7a;">Sign in to your applicant dashboard</a>
              any time to see the full history of updates.
            </p>');
    }
}
