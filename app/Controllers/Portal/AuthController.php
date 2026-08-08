<?php

namespace App\Controllers\Portal;

use App\Core\ApplicantSession;
use App\Core\Request;
use App\Core\View;
use App\Models\Applicant;
use App\Services\MailerException;
use App\Services\OtpService;

class AuthController
{
    public function __construct()
    {
        ApplicantSession::start();
    }

    public function showLogin(): void
    {
        if (ApplicantSession::current()) {
            redirect('/portal');
        }
        View::render('portal.login', ['error' => '', 'method' => 'email', 'old' => []]);
    }

    public function login(): void
    {
        if (ApplicantSession::current()) {
            redirect('/portal');
        }

        $method = Request::post('method', 'email');
        $error = '';

        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } elseif ($method === 'phone') {
            $phone = Applicant::normalizePhone((string) Request::post('phone', ''));
            $applicant = $phone ? Applicant::findByIdentifier('phone', $phone) : null;
            if ($applicant) {
                ApplicantSession::login((int) $applicant['id']);
                redirect('/portal');
            }
            $error = "We couldn't find an account for that phone number. Accounts are created automatically the first time you submit an application — apply first, then come back here to track it.";
        } else {
            $email = trim((string) Request::post('email', ''));
            $applicant = $email ? Applicant::findByIdentifier('email', $email) : null;
            if ($applicant) {
                try {
                    OtpService::issueAndSend((int) $applicant['id'], $applicant['email'], 'login');
                    $_SESSION['pending_applicant_id'] = (int) $applicant['id'];
                    redirect('/portal/verify');
                } catch (MailerException $e) {
                    $error = 'We could not send your login code right now. Please try again shortly, or contact Alnahdaagency@gmail.com.';
                } catch (\Throwable $e) {
                    $error = 'Something went wrong. Please try again shortly.';
                }
            } else {
                $error = "We couldn't find an account for that email address. Accounts are created automatically the first time you submit an application — apply first, then come back here to track it.";
            }
        }

        View::render('portal.login', [
            'error' => $error,
            'method' => $method,
            'old' => ['email' => Request::post('email', ''), 'phone' => Request::post('phone', '')],
        ]);
    }

    public function showVerify(): void
    {
        if (ApplicantSession::current()) {
            redirect('/portal');
        }
        $pending = $this->pendingApplicant();
        if (!$pending) {
            redirect('/portal/login');
        }
        View::render('portal.verify', ['error' => '', 'notice' => '', 'email' => $pending['email']]);
    }

    public function verify(): void
    {
        if (ApplicantSession::current()) {
            redirect('/portal');
        }
        $pending = $this->pendingApplicant();
        if (!$pending) {
            redirect('/portal/login');
        }

        $error = '';
        $notice = '';

        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } elseif (Request::post('action') === 'resend') {
            try {
                OtpService::issueAndSend((int) $pending['id'], $pending['email'], 'login');
                $notice = 'A new code has been sent to ' . $pending['email'] . '.';
            } catch (MailerException $e) {
                $error = 'We could not resend the code right now. Please try again shortly.';
            } catch (\Throwable $e) {
                $error = 'Something went wrong. Please try again shortly.';
            }
        } else {
            $code = trim((string) Request::post('code', ''));
            if (OtpService::verify((int) $pending['id'], $code, 'login')) {
                Applicant::markEmailVerified((int) $pending['id']);
                ApplicantSession::login((int) $pending['id']);
                unset($_SESSION['pending_applicant_id']);
                redirect('/portal');
            }
            $error = 'That code is incorrect or has expired. Please try again or request a new one.';
        }

        View::render('portal.verify', ['error' => $error, 'notice' => $notice, 'email' => $pending['email']]);
    }

    public function logout(): void
    {
        ApplicantSession::logout();
        redirect('/portal/login');
    }

    private function pendingApplicant(): ?array
    {
        if (empty($_SESSION['pending_applicant_id'])) {
            return null;
        }
        $applicant = Applicant::find((int) $_SESSION['pending_applicant_id']);
        if (!$applicant) {
            unset($_SESSION['pending_applicant_id']);
            return null;
        }
        return $applicant;
    }
}
