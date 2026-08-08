<?php

namespace App\Controllers\Admin;

use App\Core\AdminSession;
use App\Models\Admin;

abstract class BaseAdminController
{
    protected array $admin;

    public function __construct()
    {
        AdminSession::start();
        $this->admin = AdminSession::require();
    }

    /** Redirects to the dashboard with an error if the signed-in admin lacks this feature. */
    protected function requirePermission(string $key): void
    {
        if (!Admin::hasPermission($this->admin, $key)) {
            flashError("You don't have access to that section. Ask a super admin to grant you access.");
            redirect('/admin');
        }
    }

    /** Redirects to the dashboard with an error unless the signed-in admin is a super admin. */
    protected function requireSuperAdmin(): void
    {
        if (!Admin::isSuperAdmin($this->admin)) {
            flashError("Only super admins can manage admin accounts.");
            redirect('/admin');
        }
    }
}
