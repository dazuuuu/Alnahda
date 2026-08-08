<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\Testimonial;

/**
 * The public marketing site — home page, the job-application form, and the
 * testimonials page. All share the same header/footer chrome
 * (app/Views/site/layout-*).
 */
class SiteController
{
    public function home(): void
    {
        $active = 'home';
        View::render('site.layout-header', ['pageTitle' => 'Al NAHDA Agency | Recruitment Partner for the Middle East', 'active' => $active]);
        View::render('site.home', [
            'countries' => Country::active(),
            'testimonials' => Testimonial::approved(6),
        ]);
        View::render('site.layout-footer', ['active' => $active]);
    }

    public function apply(): void
    {
        $active = 'apply';
        View::render('site.layout-header', ['pageTitle' => 'Register | Al NAHDA Agency', 'active' => $active]);
        View::render('site.apply');
        View::render('site.layout-footer', ['active' => $active]);
    }

    public function testimonials(): void
    {
        $active = 'testimonials';
        View::render('site.layout-header', ['pageTitle' => 'Testimonials | Al NAHDA Agency', 'active' => $active]);
        View::render('site.testimonials', ['testimonials' => Testimonial::approved(100)]);
        View::render('site.layout-footer', ['active' => $active]);
    }
}
