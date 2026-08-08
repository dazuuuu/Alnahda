<?php
/**
 * Mirrors the live `applications` table exactly as it already exists in
 * production (see origin_db/uhwlqvsp_alnahda.sql) — this is a no-op if the
 * table is already present, so it never touches existing applicant data.
 * Portal/admin-only columns (applicant_id, status, updated_at) are added
 * separately in 0005_add_tracking_columns_to_applications_table.php.
 */
return [
    'up' => "CREATE TABLE IF NOT EXISTS applications (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        weight DECIMAL(5,2) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        phone2 VARCHAR(50) DEFAULT NULL,
        county VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        preferredRole VARCHAR(100) NOT NULL,
        gender VARCHAR(20) DEFAULT NULL,
        languages VARCHAR(255) NOT NULL,
        travelledSaudia ENUM('yes','no') DEFAULT NULL,
        returnYear VARCHAR(10) DEFAULT NULL,
        durationYears VARCHAR(10) DEFAULT NULL,
        finishedContract ENUM('yes','no') DEFAULT NULL,
        issueWithSponsor ENUM('yes','no') DEFAULT NULL,
        contractExplain TEXT,
        deported ENUM('yes','no') DEFAULT NULL,
        exitVisa ENUM('yes','no') DEFAULT NULL,
        reentryVisa ENUM('yes','no') DEFAULT NULL,
        lebanon ENUM('yes','no') DEFAULT NULL,
        jordan ENUM('yes','no') DEFAULT NULL,
        medicalFit ENUM('yes','no') DEFAULT NULL,
        willingToReturn ENUM('yes','no') DEFAULT NULL,
        validPassport ENUM('yes','no') DEFAULT NULL,
        validConduct ENUM('yes','no') DEFAULT NULL,
        appointmentPreference DATE DEFAULT NULL,
        consent TINYINT(1) DEFAULT 0,
        submitted_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    'down' => 'DROP TABLE IF EXISTS applications',
];
