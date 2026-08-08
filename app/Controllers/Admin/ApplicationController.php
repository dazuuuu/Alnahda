<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Services\MailerException;
use App\Services\MailerService;

class ApplicationController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('applications');
    }

    public function index(): void
    {
        $filters = [
            'status' => (string) Request::query('status', ''),
            'county' => (string) Request::query('county', ''),
            'search' => trim((string) Request::query('q', '')),
        ];

        View::render('admin.applications.index', [
            'pageTitle' => 'Applications',
            'activeNav' => 'applications',
            'applications' => Application::all($filters),
            'noteCounts' => ApplicationNote::countsByApplication(),
            'statuses' => Application::STATUSES,
            'statusLabels' => Application::STATUS_LABELS,
            'filters' => $filters,
        ]);
    }

    public function show(string $id): void
    {
        $application = Application::find((int) $id);
        if (!$application) {
            redirect('/admin/applications');
        }

        View::render('admin.applications.show', [
            'pageTitle' => $application['fullname'] . ' — Application #' . $application['id'],
            'activeNav' => 'applications',
            'application' => $application,
            'notes' => ApplicationNote::forApplication((int) $application['id']),
            'statuses' => Application::STATUSES,
            'statusLabels' => Application::STATUS_LABELS,
        ]);
    }

    public function updateStatus(string $id): void
    {
        if (csrfVerify(Request::post('csrf_token'))) {
            Application::updateStatus((int) $id, (string) Request::post('status', ''));
            flashSuccess('Application status updated.');
        }
        redirect('/admin/applications/' . urlencode($id));
    }

    /** Adds a note to the application; optionally emails it to the applicant as a response. */
    public function addNote(string $id): void
    {
        $application = Application::find((int) $id);
        if (!$application) {
            redirect('/admin/applications');
        }

        if (csrfVerify(Request::post('csrf_token'))) {
            $message = trim((string) Request::post('message', ''));
            $sendEmail = (bool) Request::post('notify_email');

            if ($message !== '') {
                $notified = false;
                if ($sendEmail) {
                    try {
                        MailerService::sendApplicationMessage(
                            $application['email'],
                            $application['fullname'],
                            $message,
                            Application::STATUS_LABELS[$application['status']] ?? $application['status']
                        );
                        $notified = true;
                    } catch (MailerException $e) {
                        error_log('[applications/notes] email to ' . $application['email'] . ' failed: ' . $e->getMessage());
                        flashError('The note was saved, but the email could not be sent — check the SMTP settings in .env (MAIL_HOST/MAIL_USERNAME/MAIL_PASSWORD).');
                    }
                }

                ApplicationNote::create(
                    (int) $application['id'],
                    $application['applicant_id'] ? (int) $application['applicant_id'] : null,
                    (int) $this->admin['id'],
                    $message,
                    $notified
                );

                if (!isset($_SESSION['flash_error'])) {
                    flashSuccess($sendEmail ? 'Note saved and emailed to the applicant.' : 'Note added to the applicant dashboard.');
                }
            }
        }

        redirect('/admin/applications/' . urlencode($id));
    }
}
