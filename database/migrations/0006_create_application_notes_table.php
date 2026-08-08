<?php
/**
 * Messages/notes attached to an application. Shown on the applicant's
 * portal dashboard, and optionally emailed to them at the same time
 * (see notified_at) — this is how admins "respond" to an application.
 */
return [
    'up' => "CREATE TABLE IF NOT EXISTS application_notes (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        application_id INT NOT NULL,
        applicant_id INT UNSIGNED NULL,
        admin_id INT UNSIGNED NULL,
        message TEXT NOT NULL,
        notified_at TIMESTAMP NULL DEFAULT NULL,
        read_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_notes_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
        CONSTRAINT fk_notes_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE SET NULL,
        CONSTRAINT fk_notes_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    'down' => 'DROP TABLE IF EXISTS application_notes',
];
