<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Testimonial;

class TestimonialController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('testimonials');
    }

    public function index(): void
    {
        $status = (string) Request::query('status', 'pending');
        View::render('admin.testimonials.index', [
            'pageTitle' => 'Testimonials',
            'activeNav' => 'testimonials',
            'testimonials' => Testimonial::all($status ?: null),
            'counts' => Testimonial::counts(),
            'statusFilter' => $status,
        ]);
    }

    public function updateStatus(string $id): void
    {
        if (csrfVerify(Request::post('csrf_token'))) {
            Testimonial::updateStatus((int) $id, (string) Request::post('status', ''), (int) $this->admin['id']);
            flashSuccess('Testimonial updated.');
        }
        redirect('/admin/testimonials?status=' . urlencode((string) Request::post('return_status', 'pending')));
    }
}
