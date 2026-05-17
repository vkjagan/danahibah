<?php
/**
 * DanaHibah™ - Database Connection (MySQLi Procedural)
 * DO NOT use PDO or OOP MySQLi
 */

require_once __DIR__ . '/config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    if (APP_ENV === 'development') {
        die('<div style="font-family:monospace;color:red;padding:20px;">Database connection failed: ' . mysqli_connect_error() . '</div>');
    } else {
        error_log('DB Connection failed: ' . mysqli_connect_error());
        die('<div style="font-family:sans-serif;padding:20px;">System unavailable. Please try again later.</div>');
    }
}

mysqli_set_charset($conn, DB_CHARSET);
