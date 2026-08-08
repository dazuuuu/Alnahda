<?php

namespace App\Controllers\Admin;

use App\Core\AdminSession;
use App\Core\Request;
use App\Core\View;
use App\Models\Admin;
use App\Models\AdminOtpCode;
use App\Services\MailerException;
use App\Services\MailerService;

class AuthController
{
    public function __construct()
    {
        AdminSession::start();
    }

    public function showLogin(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }
        View::render('admin.login', ['error' => '']);
    }

    public function login(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }

        $error = '';
        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } elseif (AdminSession::attempt(trim((string) Request::post('email', '')), (string) Request::post('password', ''))) {
            redirect('/admin');
        } else {
            $error = 'Incorrect email or password.';
        }

        View::render('admin.login', ['error' => $error]);
    }

    public function logout(): void
    {
        AdminSession::logout();
        redirect('/admin/login');
    }

    public function showForgotPassword(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }
        View::render('admin.forgot-password', ['error' => '', 'old' => []]);
    }

    /** Sends a 6-digit OTP to the admin's email, then moves to /admin/reset-password. */
    public function forgotPassword(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }

        $error = '';
        $email = trim((string) Request::post('email', ''));

        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } else {
            $admin = $email ? Admin::findByEmail($email) : null;
            if ($admin) {
                try {
                    $code = AdminOtpCode::generate((int) $admin['id']);
                    MailerService::sendOtp($admin['email'], $code, 'password_reset');
                    $_SESSION['pending_admin_reset_id'] = (int) $admin['id'];
                    flashSuccess('A 6-digit reset code has been sent to ' . $admin['email'] . '.');
                    redirect('/admin/reset-password');
                } catch (MailerException $e) {
                    $error = 'We could not send the reset code right now. Please try again shortly.';
                } catch (\Throwable $e) {
                    $error = 'Something went wrong. Please try again shortly.';
                }
            } else {
                $error = "We couldn't find an admin account for that email address.";
            }
        }

        View::render('admin.forgot-password', ['error' => $error, 'old' => ['email' => $email]]);
    }

    public function showResetPassword(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }
        $pending = $this->pendingAdmin();
        if (!$pending) {
            redirect('/admin/forgot-password');
        }
        View::render('admin.reset-password', ['error' => '', 'notice' => '', 'email' => $pending['email']]);
    }

    /** Verifies the OTP and, if valid alongside a matching new password, updates it. */
    public function resetPassword(): void
    {
        if (AdminSession::current()) {
            redirect('/admin');
        }
        $pending = $this->pendingAdmin();
        if (!$pending) {
            redirect('/admin/forgot-password');
        }

        $error = '';
        $notice = '';

        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } elseif (Request::post('action') === 'resend') {
            try {
                $code = AdminOtpCode::generate((int) $pending['id']);
                MailerService::sendOtp($pending['email'], $code, 'password_reset');
                $notice = 'A new code has been sent to ' . $pending['email'] . '.';
            } catch (MailerException $e) {
                $error = 'We could not resend the code right now. Please try again shortly.';
            } catch (\Throwable $e) {
                $error = 'Something went wrong. Please try again shortly.';
            }
        } else {
            $code = trim((string) Request::post('code', ''));
            $password = (string) Request::post('password', '');
            $confirm = (string) Request::post('password_confirmation', '');

            if (strlen($password) < 8) {
                $error = 'Your new password must be at least 8 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Those passwords do not match.';
            } elseif (!AdminOtpCode::verify((int) $pending['id'], $code)) {
                $error = 'That code is incorrect or has expired. Please try again or request a new one.';
            } else {
                Admin::updatePassword((int) $pending['id'], $password);
                unset($_SESSION['pending_admin_reset_id']);
                flashSuccess('Your password has been reset. Please sign in.');
                redirect('/admin/login');
            }
        }

        View::render('admin.reset-password', ['error' => $error, 'notice' => $notice, 'email' => $pending['email']]);
    }

    private function pendingAdmin(): ?array
    {
        if (empty($_SESSION['pending_admin_reset_id'])) {
            return null;
        }
        $admin = Admin::find((int) $_SESSION['pending_admin_reset_id']);
        if (!$admin) {
            unset($_SESSION['pending_admin_reset_id']);
            return null;
        }
        return $admin;
    }
}
