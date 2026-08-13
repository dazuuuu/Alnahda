<?php
/**
 * Al NAHDA Agency — single front controller (see .htaccess: every request
 * that isn't a real file/directory is routed through this file). All
 * application code lives outside the web root, in /app.
 */

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Router;
use App\Controllers\SiteController;
use App\Controllers\Api\ApplicationController as ApiApplicationController;
use App\Controllers\Admin\AuthController as AdminAuthController;
use App\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\CountryController as AdminCountryController;
use App\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Controllers\Portal\AuthController as PortalAuthController;
use App\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Controllers\Portal\TestimonialController as PortalTestimonialController;

$router = new Router();

// --- Public site ---
$router->get('/', [SiteController::class, 'home']);
$router->get('/apply', [SiteController::class, 'apply']);
$router->get('/testimonials', [SiteController::class, 'testimonials']);

// --- Public API ---
$router->post('/api/applications', [ApiApplicationController::class, 'store']);

// --- Admin: auth ---
$router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->get('/admin/logout', [AdminAuthController::class, 'logout']);
$router->get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPassword']);
$router->post('/admin/forgot-password', [AdminAuthController::class, 'forgotPassword']);
$router->get('/admin/reset-password', [AdminAuthController::class, 'showResetPassword']);
$router->post('/admin/reset-password', [AdminAuthController::class, 'resetPassword']);

// --- Admin: dashboard ---
$router->get('/admin', [AdminDashboardController::class, 'index']);

// --- Admin: applications ---
$router->get('/admin/applications', [AdminApplicationController::class, 'index']);
$router->get('/admin/applications/export', [AdminApplicationController::class, 'export']);
$router->get('/admin/applications/{id}', [AdminApplicationController::class, 'show']);
$router->get('/admin/applications/{id}/export', [AdminApplicationController::class, 'exportOne']);
$router->post('/admin/applications/{id}/status', [AdminApplicationController::class, 'updateStatus']);
$router->post('/admin/applications/{id}/notes', [AdminApplicationController::class, 'addNote']);

// --- Admin: countries ---
$router->get('/admin/countries', [AdminCountryController::class, 'index']);
$router->get('/admin/countries/create', [AdminCountryController::class, 'create']);
$router->post('/admin/countries', [AdminCountryController::class, 'store']);
$router->get('/admin/countries/{id}/edit', [AdminCountryController::class, 'edit']);
$router->post('/admin/countries/{id}', [AdminCountryController::class, 'update']);
$router->post('/admin/countries/{id}/delete', [AdminCountryController::class, 'destroy']);

// --- Admin: testimonials ---
$router->get('/admin/testimonials', [AdminTestimonialController::class, 'index']);
$router->post('/admin/testimonials/{id}/status', [AdminTestimonialController::class, 'updateStatus']);

// --- Admin: manage admins (super admin only) ---
$router->get('/admin/users', [AdminUserController::class, 'index']);
$router->get('/admin/users/create', [AdminUserController::class, 'create']);
$router->post('/admin/users', [AdminUserController::class, 'store']);
$router->get('/admin/users/{id}/edit', [AdminUserController::class, 'edit']);
$router->post('/admin/users/{id}', [AdminUserController::class, 'update']);
$router->post('/admin/users/{id}/delete', [AdminUserController::class, 'destroy']);

// --- Applicant portal ---
$router->get('/portal/login', [PortalAuthController::class, 'showLogin']);
$router->post('/portal/login', [PortalAuthController::class, 'login']);
$router->get('/portal/verify', [PortalAuthController::class, 'showVerify']);
$router->post('/portal/verify', [PortalAuthController::class, 'verify']);
$router->get('/portal/logout', [PortalAuthController::class, 'logout']);
$router->get('/portal', [PortalDashboardController::class, 'index']);
$router->get('/portal/applications/{id}', [PortalDashboardController::class, 'show']);
$router->get('/portal/testimonial', [PortalTestimonialController::class, 'create']);
$router->post('/portal/testimonial', [PortalTestimonialController::class, 'store']);

$router->dispatch();
