<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Services\ApplicationExportService;
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
        $filters = $this->filters();

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

    public function export(): void
    {
        $applications = Application::all($this->filters());
        $this->sendExport($applications, 'alnahda-applications-' . date('Y-m-d'));
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

    public function exportOne(string $id): void
    {
        $application = Application::find((int) $id);
        if (!$application) {
            redirect('/admin/applications');
        }

        $slug = preg_replace('/[^a-z0-9]+/i', '-', (string) $application['fullname']) ?: 'application';
        $this->sendExport([$application], 'alnahda-application-' . (int) $application['id'] . '-' . strtolower(trim($slug, '-')));
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
            $email = trim((string) ($application['email'] ?? ''));

            if ($message !== '') {
                $notified = false;
                if ($sendEmail && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        MailerService::sendApplicationMessage(
                            $email,
                            $application['fullname'],
                            $message,
                            Application::STATUS_LABELS[$application['status']] ?? $application['status']
                        );
                        $notified = true;
                    } catch (MailerException $e) {
                        error_log('[applications/notes] email to ' . $email . ' failed: ' . $e->getMessage());
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
                    flashSuccess($notified ? 'Note saved and emailed to the applicant.' : 'Note added to the applicant dashboard.');
                }
            }
        }

        redirect('/admin/applications/' . urlencode($id));
    }

    /** @return array{status:string,county:string,search:string} */
    private function filters(): array
    {
        return [
            'status' => (string) Request::query('status', ''),
            'county' => (string) Request::query('county', ''),
            'search' => trim((string) Request::query('q', '')),
        ];
    }

    /** @param array<int,array<string,mixed>> $applications */
    private function sendExport(array $applications, string $filename): void
    {
        $format = strtolower((string) Request::query('format', 'excel'));
        if ($format === 'pdf') {
            ApplicationExportService::downloadPdf($applications, $filename);
        }
        ApplicationExportService::downloadExcel($applications, $filename);
    }
}
