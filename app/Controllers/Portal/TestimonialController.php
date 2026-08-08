<?php

namespace App\Controllers\Portal;

use App\Core\ApplicantSession;
use App\Core\Request;
use App\Core\View;
use App\Models\Testimonial;

class TestimonialController
{
    public function __construct()
    {
        ApplicantSession::start();
    }

    public function create(): void
    {
        $applicant = ApplicantSession::require();
        View::render('portal.testimonial', [
            'pageTitle' => 'Leave a Testimonial',
            'applicant' => $applicant,
            'testimonials' => Testimonial::forApplicant((int) $applicant['id']),
            'error' => '',
        ]);
    }

    public function store(): void
    {
        $applicant = ApplicantSession::require();
        $error = '';

        $message = trim((string) Request::post('message', ''));
        $rating = (int) Request::post('rating', 5);
        $authorRole = trim((string) Request::post('author_role', ''));

        if (!csrfVerify(Request::post('csrf_token'))) {
            $error = 'Your session expired. Please try again.';
        } elseif ($message === '') {
            $error = 'Please write a short testimonial before submitting.';
        } else {
            Testimonial::create(
                (int) $applicant['id'],
                trim($applicant['full_name'] ?? '') ?: 'Al NAHDA Applicant',
                $authorRole ?: null,
                $rating,
                $message
            );
            flashSuccess("Thank you! Your testimonial has been submitted and will appear on our website once it's approved.");
            redirect('/portal/testimonial');
        }

        View::render('portal.testimonial', [
            'pageTitle' => 'Leave a Testimonial',
            'applicant' => $applicant,
            'testimonials' => Testimonial::forApplicant((int) $applicant['id']),
            'error' => $error,
        ]);
    }
}
