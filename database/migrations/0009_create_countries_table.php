<?php
/** Destinations shown in the public "Countries We Recruit To" section — managed from /admin/countries. */
return [
    'up' => "CREATE TABLE IF NOT EXISTS countries (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        flag_image VARCHAR(255) NULL,
        cover_image VARCHAR(255) NULL,
        description TEXT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    'down' => 'DROP TABLE IF EXISTS countries',
];
