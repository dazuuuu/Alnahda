<?php

namespace App\Controllers\Portal;

use App\Core\ApplicantSession;
use App\Core\View;
use App\Models\Application;
use App\Models\ApplicationNote;

class DashboardController
{
    public function __construct()
    {
        ApplicantSession::start();
    }

    public function index(): void
    {
        $applicant = ApplicantSession::require();
        $applications = Application::forApplicant((int) $applicant['id']);

        $noteCounts = [];
        foreach ($applications as $app) {
            $noteCounts[$app['id']] = count(ApplicationNote::forApplication((int) $app['id']));
        }

        View::render('portal.dashboard', [
            'pageTitle' => 'My Applications',
            'applicant' => $applicant,
            'applications' => $applications,
            'noteCounts' => $noteCounts,
        ]);
    }

    public function show(string $id): void
    {
        $applicant = ApplicantSession::require();
        $application = Application::findForApplicant((int) $id, (int) $applicant['id']);
        if (!$application) {
            redirect('/portal');
        }

        ApplicationNote::markReadForApplicant((int) $application['id'], (int) $applicant['id']);

        View::render('portal.show', [
            'pageTitle' => 'Application #' . $application['id'],
            'applicant' => $applicant,
            'application' => $application,
            'notes' => ApplicationNote::forApplication((int) $application['id']),
        ]);
    }
}
