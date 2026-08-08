<?php
/**
 * Testimonials submitted by applicants from their portal dashboard (or
 * seeded as official quotes) — only shown publicly once an admin approves
 * them. See Portal\TestimonialController and Admin\TestimonialController.
 */
return [
    'up' => "CREATE TABLE IF NOT EXISTS testimonials (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        applicant_id INT UNSIGNED NULL,
        author_name VARCHAR(150) NOT NULL,
        author_role VARCHAR(150) NULL,
        rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
        message TEXT NOT NULL,
        status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
        reviewed_by INT UNSIGNED NULL,
        approved_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_testimonials_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE SET NULL,
        CONSTRAINT fk_testimonials_admin FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    'down' => 'DROP TABLE IF EXISTS testimonials',
];
