<?php
/**
 * Adds portal/admin tracking columns on top of the original applications
 * schema — links a submission to a portal account, and tracks review status.
 */
return [
    'up' => "ALTER TABLE applications
        ADD COLUMN applicant_id INT UNSIGNED NULL AFTER id,
        ADD COLUMN status ENUM('pending','reviewing','shortlisted','interview','approved','placed','rejected') NOT NULL DEFAULT 'pending' AFTER submitted_at,
        ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER status,
        ADD INDEX idx_applications_applicant (applicant_id),
        ADD INDEX idx_applications_status (status),
        ADD CONSTRAINT fk_applications_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE SET NULL",
    'down' => 'ALTER TABLE applications
        DROP FOREIGN KEY fk_applications_applicant,
        DROP INDEX idx_applications_applicant,
        DROP INDEX idx_applications_status,
        DROP COLUMN applicant_id,
        DROP COLUMN status,
        DROP COLUMN updated_at',
];
