<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Admin;

/** Super-admin-only: create co-admins (equal rights) or junior admins (limited to allocated features). */
class AdminUserController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireSuperAdmin();
    }

    public function index(): void
    {
        View::render('admin.users.index', [
            'pageTitle' => 'Manage Admins',
            'activeNav' => 'admin-users',
            'admins' => Admin::all(),
        ]);
    }

    public function create(): void
    {
        View::render('admin.users.form', [
            'pageTitle' => 'Add Admin',
            'activeNav' => 'admin-users',
            'mode' => 'create',
            'permissionOptions' => Admin::PERMISSIONS,
            'errors' => [],
            'form' => ['email' => '', 'name' => '', 'role' => 'junior_admin', 'permissions' => []],
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate(null);

        if (!$errors) {
            Admin::create(
                trim((string) Request::post('email')),
                (string) Request::post('password'),
                trim((string) Request::post('name', '')),
                (string) Request::post('role', 'junior_admin'),
                (array) Request::post('permissions', []),
                (int) $this->admin['id']
            );
            flashSuccess('Admin account created.');
            redirect('/admin/users');
        }

        View::render('admin.users.form', [
            'pageTitle' => 'Add Admin',
            'activeNav' => 'admin-users',
            'mode' => 'create',
            'permissionOptions' => Admin::PERMISSIONS,
            'errors' => $errors,
            'form' => [
                'email' => Request::post('email', ''),
                'name' => Request::post('name', ''),
                'role' => Request::post('role', 'junior_admin'),
                'permissions' => (array) Request::post('permissions', []),
            ],
        ]);
    }

    public function edit(string $id): void
    {
        $target = Admin::find((int) $id);
        if (!$target) {
            redirect('/admin/users');
        }

        View::render('admin.users.form', [
            'pageTitle' => 'Edit Admin',
            'activeNav' => 'admin-users',
            'mode' => 'edit',
            'targetId' => $target['id'],
            'permissionOptions' => Admin::PERMISSIONS,
            'errors' => [],
            'form' => [
                'email' => $target['email'],
                'name' => $target['name'] ?? '',
                'role' => $target['role'],
                'permissions' => $target['permissions'],
            ],
        ]);
    }

    public function update(string $id): void
    {
        $target = Admin::find((int) $id);
        if (!$target) {
            redirect('/admin/users');
        }

        $errors = $this->validate($target);

        if (!$errors) {
            Admin::updateRoleAndProfile(
                (int) $target['id'],
                trim((string) Request::post('email')),
                trim((string) Request::post('name', '')),
                (string) Request::post('role', 'junior_admin'),
                (array) Request::post('permissions', [])
            );

            $newPassword = (string) Request::post('password', '');
            if ($newPassword !== '') {
                Admin::updatePassword((int) $target['id'], $newPassword);
            }

            flashSuccess('Admin account updated.');
            redirect('/admin/users');
        }

        View::render('admin.users.form', [
            'pageTitle' => 'Edit Admin',
            'activeNav' => 'admin-users',
            'mode' => 'edit',
            'targetId' => $target['id'],
            'permissionOptions' => Admin::PERMISSIONS,
            'errors' => $errors,
            'form' => [
                'email' => Request::post('email', ''),
                'name' => Request::post('name', ''),
                'role' => Request::post('role', 'junior_admin'),
                'permissions' => (array) Request::post('permissions', []),
            ],
        ]);
    }

    public function destroy(string $id): void
    {
        $target = Admin::find((int) $id);
        if (!$target) {
            redirect('/admin/users');
        }

        if ((int) $target['id'] === (int) $this->admin['id']) {
            flashError('You cannot remove your own account.');
        } elseif ($target['role'] === 'super_admin' && Admin::countSuperAdmins() <= 1) {
            flashError('At least one super admin must remain.');
        } elseif (!csrfVerify(Request::post('csrf_token'))) {
            flashError('Your session expired. Please try again.');
        } else {
            Admin::delete((int) $target['id']);
            flashSuccess('Admin account removed.');
        }

        redirect('/admin/users');
    }

    /** @return string[] */
    private function validate(?array $target): array
    {
        $errors = [];

        if (!csrfVerify(Request::post('csrf_token'))) {
            $errors[] = 'Your session expired. Please resubmit the form.';
            return $errors;
        }

        $email = trim((string) Request::post('email', ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } else {
            $existing = Admin::findByEmail($email);
            if ($existing && (!$target || (int) $existing['id'] !== (int) $target['id'])) {
                $errors[] = 'That email is already used by another admin.';
            }
        }

        $role = (string) Request::post('role', 'junior_admin');
        if (!in_array($role, Admin::ROLES, true)) {
            $errors[] = 'Please choose a valid role.';
        }
        if ($target && (int) $target['id'] === (int) $this->admin['id'] && $role !== 'super_admin') {
            $errors[] = 'You cannot demote your own account.';
        }
        if ($target && $target['role'] === 'super_admin' && $role !== 'super_admin' && Admin::countSuperAdmins() <= 1) {
            $errors[] = 'At least one super admin must remain — promote another admin first.';
        }

        $password = (string) Request::post('password', '');
        if (!$target && strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        } elseif ($target && $password !== '' && strlen($password) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }

        return $errors;
    }
}
