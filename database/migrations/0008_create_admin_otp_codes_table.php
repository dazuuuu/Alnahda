<?php
/** OTP codes for the admin "forgot password" flow — see Admin\AuthController. */
return [
    'up' => "CREATE TABLE IF NOT EXISTS admin_otp_codes (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        admin_id INT UNSIGNED NOT NULL,
        code VARCHAR(255) NOT NULL,
        purpose ENUM('password_reset') NOT NULL DEFAULT 'password_reset',
        expires_at TIMESTAMP NOT NULL,
        used_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_admin_otp_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    'down' => 'DROP TABLE IF EXISTS admin_otp_codes',
];
