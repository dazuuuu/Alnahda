<?php
/**
 * Adds admin roles: 'super_admin' has full, unrestricted access (equal to
 * the account owner). 'junior_admin' is limited to whatever feature keys
 * are listed in `permissions` (JSON array — see App\Models\Admin::PERMISSIONS),
 * assigned by a super admin from /admin/users.
 */
return [
    'up' => "ALTER TABLE admins
        ADD COLUMN name VARCHAR(150) NULL AFTER email,
        ADD COLUMN role ENUM('super_admin','junior_admin') NOT NULL DEFAULT 'super_admin' AFTER password_hash,
        ADD COLUMN permissions JSON NULL AFTER role,
        ADD COLUMN created_by INT UNSIGNED NULL AFTER permissions,
        ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
        ADD CONSTRAINT fk_admins_created_by FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL",
    'down' => 'ALTER TABLE admins
        DROP FOREIGN KEY fk_admins_created_by,
        DROP COLUMN name,
        DROP COLUMN role,
        DROP COLUMN permissions,
        DROP COLUMN created_by,
        DROP COLUMN updated_at',
];
