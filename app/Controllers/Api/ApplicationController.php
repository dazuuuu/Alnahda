<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Models\Applicant;
use App\Models\Application;
use App\Services\MailerException;
use App\Services\MailerService;

/**
 * POST /api/applications — called by assets/site/js/script.js's registration
 * form (public/views/site/apply.php). Persists the submission, links it to a
 * portal account (created automatically if this is a new email/phone), and
 * emails a confirmation. Mirrors the legacy process.php's column list, see
 * origin_db/uhwlqvsp_alnahda.sql.
 */
class ApplicationController
{
    /** Required fields — matches the `required` inputs in public/views/site/apply.php. */
    private const REQUIRED = [
        'fullname', 'email', 'weight', 'phone', 'county', 'age', 'preferredRole',
        'gender', 'languages', 'travelledSaudia', 'lebanon', 'jordan', 'medicalFit',
        'willingToReturn', 'validPassport', 'validConduct', 'appointmentPreference',
    ];

    private const YES_NO_FIELDS = [
        'travelledSaudia', 'finishedContract', 'issueWithSponsor', 'deported', 'exitVisa',
        'reentryVisa', 'lebanon', 'jordan', 'medicalFit', 'willingToReturn', 'validPassport', 'validConduct',
    ];

    public function store(): void
    {
        header('Content-Type: application/json');

        foreach (self::REQUIRED as $field) {
            if (trim((string) Request::post($field, '')) === '') {
                $this->respond(['success' => false, 'message' => 'Please complete all required fields before submitting.'], 422);
            }
        }
        if (!Request::post('consent')) {
            $this->respond(['success' => false, 'message' => 'Please agree to be contacted before submitting.'], 422);
        }

        $email = trim((string) Request::post('email', ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->respond(['success' => false, 'message' => 'Please enter a valid email address.'], 422);
        }

        $data = [
            'fullname' => trim((string) Request::post('fullname')),
            'email' => $email,
            'weight' => (float) Request::post('weight'),
            'phone' => trim((string) Request::post('phone')),
            'phone2' => trim((string) Request::post('phone2', '')) ?: null,
            'county' => trim((string) Request::post('county')),
            'age' => (int) Request::post('age'),
            'preferredRole' => trim((string) Request::post('preferredRole')),
            'gender' => trim((string) Request::post('gender')),
            'languages' => trim((string) Request::post('languages')),
            'returnYear' => trim((string) Request::post('returnYear', '')) ?: null,
            'durationYears' => trim((string) Request::post('durationYears', '')) ?: null,
            'contractExplain' => trim((string) Request::post('contractExplain', '')) ?: null,
            'appointmentPreference' => trim((string) Request::post('appointmentPreference', '')) ?: null,
            'consent' => Request::post('consent') ? 1 : 0,
        ];
        foreach (self::YES_NO_FIELDS as $field) {
            $value = Request::post($field, '');
            $data[$field] = in_array($value, ['yes', 'no'], true) ? $value : null;
        }

        $applicantId = Applicant::findOrCreateFromApplication($data['email'], $data['phone'], $data['fullname']);
        $applicationId = Application::create($data, $applicantId);

        try {
            MailerService::sendApplicationReceived($data['email'], $data['fullname'], $applicationId);
        } catch (MailerException $e) {
            // Non-fatal — the application is already saved even if the confirmation email fails to send.
        }

        $this->respond([
            'success' => true,
            'message' => 'Application submitted successfully! Our recruiters will be in touch.',
            'applicationId' => $applicationId,
        ]);
    }

    private function respond(array $payload, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($payload);
        exit;
    }
}
