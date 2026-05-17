-- ============================================================
-- DanaHibah™ — Database Schema (MySQL, InnoDB, UTF8MB4)
-- Version: 1.0.0
-- ============================================================

CREATE DATABASE IF NOT EXISTS `danahibah`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `danahibah`;

-- ─── Roles ───────────────────────────────────────────────────
CREATE TABLE `roles` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(50) NOT NULL UNIQUE,
    `label`       VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `label`, `description`) VALUES
('super_admin',  'Super Administrator',        'Application-level full access'),
('management',   'Management Administrator',   'Organization-wide monitoring access'),
('branch_admin', 'Branch Administrator',       'Branch-level administration access'),
('committee',    'Branch Committee Member',    'Operational data entry access');

-- ─── Permissions ─────────────────────────────────────────────
CREATE TABLE `permissions` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `module`     VARCHAR(50) NOT NULL,
    `action`     VARCHAR(50) NOT NULL,
    `label`      VARCHAR(100) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_module_action` (`module`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Role Permissions ─────────────────────────────────────────
CREATE TABLE `role_permissions` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id`       INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`role_id`)       REFERENCES `roles`(`id`)       ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_role_perm` (`role_id`, `permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Users ───────────────────────────────────────────────────
CREATE TABLE `users` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id`      INT UNSIGNED NOT NULL DEFAULT 2,
    `branch_id`    INT UNSIGNED NULL,
    `full_name`    VARCHAR(150) NOT NULL,
    `username`     VARCHAR(80)  NOT NULL UNIQUE,
    `email`        VARCHAR(150) NOT NULL UNIQUE,
    `phone`        VARCHAR(20),
    `password`     VARCHAR(255) NOT NULL,
    `avatar`       VARCHAR(255),
    `status`       ENUM('active','inactive') DEFAULT 'active',
    `last_login`   DATETIME,
    `created_by`   INT UNSIGNED,
    `updated_by`   INT UNSIGNED,
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`   DATETIME DEFAULT NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`),
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin user (password: Admin@123)
INSERT INTO `users` (`role_id`, `full_name`, `username`, `email`, `password`, `status`) VALUES
(1, 'System Administrator', 'admin', 'admin@danahibah.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- ─── User Roles (many-to-many) ────────────────────────────────
CREATE TABLE `user_roles` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL,
    `role_id`    INT UNSIGNED NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_user_role` (`user_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Branches (Masjid/Surau) ─────────────────────────────────
CREATE TABLE `branches` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(200) NOT NULL,
    `code`        VARCHAR(20) UNIQUE,
    `type`        ENUM('masjid','surau','wakaf','ngo','other') DEFAULT 'masjid',
    `address`     TEXT,
    `city`        VARCHAR(100),
    `state`       VARCHAR(100),
    `postcode`    VARCHAR(10),
    `pic_name`    VARCHAR(150),
    `pic_phone`   VARCHAR(20),
    `pic_email`   VARCHAR(150),
    `status`      ENUM('active','inactive') DEFAULT 'active',
    `created_by`  INT UNSIGNED,
    `updated_by`  INT UNSIGNED,
    `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Devices ─────────────────────────────────────────────────
CREATE TABLE `devices` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `branch_id`    INT UNSIGNED NOT NULL,
    `serial_no`    VARCHAR(100) NOT NULL UNIQUE,
    `model`        VARCHAR(100),
    `type`         ENUM('cash_box','qr_terminal','hybrid') DEFAULT 'hybrid',
    `status`       ENUM('online','offline','tampered','maintenance') DEFAULT 'offline',
    `last_sync`    DATETIME,
    `firmware_ver` VARCHAR(50),
    `created_by`   INT UNSIGNED,
    `updated_by`   INT UNSIGNED,
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`   DATETIME DEFAULT NULL,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Collections (Transactions) ──────────────────────────────
CREATE TABLE `collections` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `branch_id`     INT UNSIGNED NOT NULL,
    `device_id`     INT UNSIGNED,
    `txn_ref`       VARCHAR(50) UNIQUE,
    `channel`       ENUM('cash','qr','manual','online') DEFAULT 'cash',
    `amount`        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `currency`      VARCHAR(5) DEFAULT 'MYR',
    `donor_name`    VARCHAR(150),
    `donor_phone`   VARCHAR(20),
    `category`      ENUM('general','friday','zakat','wakaf','special','sadaqah') DEFAULT 'general',
    `notes`         TEXT,
    `receipt_no`    VARCHAR(50),
    `bank_receipt_file` VARCHAR(255),
    `bank_ref_no`   VARCHAR(100),
    `status`        ENUM('collected','verified','approved','banked','rejected') DEFAULT 'collected',
    `collected_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    `collected_by`  INT UNSIGNED,
    `created_by`    INT UNSIGNED,
    `updated_by`    INT UNSIGNED,
    `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`    DATETIME DEFAULT NULL,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`),
    FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`),
    INDEX `idx_branch`  (`branch_id`),
    INDEX `idx_status`  (`status`),
    INDEX `idx_channel` (`channel`),
    INDEX `idx_date`    (`collected_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Collection Approvals (Workflow) ─────────────────────────
CREATE TABLE `collection_approvals` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `collection_id` INT UNSIGNED NOT NULL,
    `step`          ENUM('verified','approved','banked','rejected') NOT NULL,
    `remarks`       TEXT,
    `actioned_by`   INT UNSIGNED NOT NULL,
    `actioned_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`collection_id`) REFERENCES `collections`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`actioned_by`)   REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Bank Deposits (Ledger) ───────────────────────────────────
CREATE TABLE `bank_deposits` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `branch_id`    INT UNSIGNED NOT NULL,
    `amount`       DECIMAL(10,2) NOT NULL,
    `ref_no`       VARCHAR(100),
    `receipt_file` VARCHAR(255),
    `deposit_date` DATE NOT NULL,
    `remarks`      TEXT,
    `created_by`   INT UNSIGNED,
    `updated_by`   INT UNSIGNED,
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`   DATETIME DEFAULT NULL,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Expenses ─────────────────────────────────────────────────
CREATE TABLE `expenses` (
    `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `branch_id`      INT UNSIGNED NOT NULL,
    `category`       VARCHAR(100) NOT NULL,
    `amount`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status`         ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `payment_source` ENUM('cash', 'bank') DEFAULT 'cash',
    `title`          VARCHAR(150) NOT NULL,
    `description`    TEXT,
    `receipt_file`   VARCHAR(255),
    `expense_date`   DATE NOT NULL,
    `created_by`     INT UNSIGNED,
    `updated_by`     INT UNSIGNED,
    `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`     DATETIME DEFAULT NULL,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Audit Logs ───────────────────────────────────────────────
CREATE TABLE `audit_logs` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT UNSIGNED,
    `action`      VARCHAR(50)  NOT NULL,
    `module`      VARCHAR(50)  NOT NULL,
    `description` TEXT,
    `record_id`   INT UNSIGNED,
    `ip_address`  VARCHAR(45),
    `user_agent`  VARCHAR(255),
    `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_module`  (`module`),
    INDEX `idx_user`    (`user_id`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Login Logs ───────────────────────────────────────────────
CREATE TABLE `login_logs` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED,
    `ip_address` VARCHAR(45),
    `user_agent` VARCHAR(255),
    `status`     ENUM('success','failed') DEFAULT 'success',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_status`  (`status`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Login Attempts (Rate Limiting) ──────────────────────────
CREATE TABLE `login_attempts` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username`     VARCHAR(150),
    `ip_address`   VARCHAR(45),
    `attempted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_username` (`username`),
    INDEX `idx_ip`       (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Settings ─────────────────────────────────────────────────
CREATE TABLE `settings` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key`        VARCHAR(100) NOT NULL UNIQUE,
    `value`      TEXT,
    `group`      VARCHAR(50) DEFAULT 'general',
    `label`      VARCHAR(150),
    `updated_by` INT UNSIGNED,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`, `group`, `label`) VALUES
('app_name',        'DanaHibah™',                          'general',  'Application Name'),
('app_tagline',     'Secure. Transparent. Amanah.',        'general',  'Tagline'),
('app_logo',        '',                                    'general',  'Logo Path'),
('contact_email',   'hello@danahibah.com',                 'general',  'Contact Email'),
('contact_phone',   '+60 12-345 6789',                     'general',  'Contact Phone'),
('smtp_host',       'smtp.mailtrap.io',                    'email',    'SMTP Host'),
('smtp_port',       '587',                                 'email',    'SMTP Port'),
('smtp_user',       '',                                    'email',    'SMTP Username'),
('smtp_pass',       '',                                    'email',    'SMTP Password'),
('smtp_from_name',  'DanaHibah™',                          'email',    'From Name'),
('smtp_from_email', 'noreply@danahibah.com',               'email',    'From Email'),
('meta_title',      'DanaHibah™ | Sistem Tadbir Derma',   'seo',      'Meta Title'),
('meta_desc',       'Sistem kutipan derma digital masjid', 'seo',      'Meta Description'),
('per_page',        '25',                                  'general',  'Records Per Page'),
('session_timeout', '1800',                                'security', 'Session Timeout (seconds)'),
('max_attempts',    '5',                                   'security', 'Max Login Attempts');
