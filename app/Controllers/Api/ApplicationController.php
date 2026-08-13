<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Models\Applicant;
use App\Models\Application;
use App\Services\MailerException;
use App\Services\MailerService;

/**
 * POST /api/applications — called by assets/site/js/script.js's registration
 * form (app/Views/site/apply.php). Persists the submission, links it to a
 * portal account (created automatically if this is a new phone number), and
 * emails a confirmation when an email is on file.
 */
class ApplicationController
{
    /** Required fields — matches the `required` inputs in app/Views/site/apply.php. */
    private const REQUIRED = [
        'fullname', 'age', 'validPassport', 'phone', 'county', 'travelledSaudia', 'appointmentPreference',
    ];

    private const YES_NO_FIELDS = [
        'travelledSaudia', 'validPassport',
    ];

    public function store(): void
    {
        header('Content-Type: application/json');

        foreach (self::REQUIRED as $field) {
            if (trim((string) Request::post($field, '')) === '') {
                $this->respond(['success' => false, 'message' => 'Please complete all required fields before submitting.'], 422);
            }
        }

        $age = (int) Request::post('age');
        if ($age < 18 || $age > 65) {
            $this->respond(['success' => false, 'message' => 'Please enter a valid age between 18 and 65.'], 422);
        }

        $data = [
            'fullname' => trim((string) Request::post('fullname')),
            'email' => '',
            'weight' => 0,
            'phone' => trim((string) Request::post('phone')),
            'phone2' => null,
            'county' => trim((string) Request::post('county')),
            'age' => $age,
            'preferredRole' => 'HOUSEMAID',
            'gender' => null,
            'languages' => '',
            'returnYear' => null,
            'durationYears' => null,
            'contractExplain' => null,
            'appointmentPreference' => trim((string) Request::post('appointmentPreference', '')) ?: null,
            'consent' => 1,
        ];
        foreach (self::YES_NO_FIELDS as $field) {
            $value = Request::post($field, '');
            $data[$field] = in_array($value, ['yes', 'no'], true) ? $value : null;
        }

        $applicantId = Applicant::findOrCreateFromApplication($data['email'], $data['phone'], $data['fullname']);
        $applicationId = Application::create($data, $applicantId);

        if ($data['email'] !== '' && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            try {
                MailerService::sendApplicationReceived($data['email'], $data['fullname'], $applicationId);
            } catch (MailerException $e) {
                // Non-fatal — the application is already saved even if the confirmation email fails to send.
            }
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
