<?php
/**
 * DanaHibah™ - Core Configuration
 * Secure. Transparent. Amanah.
 */

// ─── Environment ────────────────────────────────────────────────────────────
define('APP_ENV', 'development'); // 'development' | 'production'

// ─── Application ────────────────────────────────────────────────────────────
define('APP_NAME',    'DanaHibah™');
define('APP_TAGLINE', 'Secure. Transparent. Amanah.');
define('APP_VERSION', '1.0.0');
define('APP_URL',     'http://localhost/danahibah');
define('APP_ROOT',    dirname(__DIR__));

// ─── Database ────────────────────────────────────────────────────────────────
define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'danahibah');
define('DB_CHARSET',  'utf8mb4');

// ─── Paths ───────────────────────────────────────────────────────────────────
define('UPLOAD_PATH',   APP_ROOT . '/uploads/');
define('LOG_PATH',      APP_ROOT . '/logs/');
define('INCLUDES_PATH', APP_ROOT . '/includes/');

// ─── Session ─────────────────────────────────────────────────────────────────
define('SESSION_TIMEOUT',    1800);   // 30 minutes
define('SESSION_NAME',       'danahibah_session');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900);    // 15 minutes

// ─── Pagination ──────────────────────────────────────────────────────────────
define('DEFAULT_PER_PAGE', 25);

// ─── File Upload ─────────────────────────────────────────────────────────────
define('MAX_UPLOAD_SIZE',    5242880); // 5MB
define('ALLOWED_IMG_TYPES',  ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_DOC_TYPES',  ['application/pdf', 'application/vnd.ms-excel',
                               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

// ─── Timezone ────────────────────────────────────────────────────────────────
date_default_timezone_set('Asia/Kuala_Lumpur');

// ─── Error Reporting ─────────────────────────────────────────────────────────
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_PATH . 'php_errors.log');
}

// ─── SEO Defaults ────────────────────────────────────────────────────────────
define('META_TITLE',       'DanaHibah™ | Sistem Tadbir Urus & Kutipan Derma');
define('META_DESCRIPTION', 'Sistem pengurusan kutipan derma digital untuk masjid dan surau. Selamat, telus dan amanah.');
define('META_KEYWORDS',    'DanaHibah, kutipan derma, masjid, surau, sistem derma digital, Malaysia');
define('META_OG_IMAGE',    APP_URL . '/assets/images/og-image.png');
