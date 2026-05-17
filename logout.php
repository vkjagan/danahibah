<?php
/**
 * DanaHibah™ - Logout
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

start_session();
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

if (!headers_sent()) {
    header("Location: " . APP_URL . "/login.php");
}
echo '<script>window.location.href="' . APP_URL . '/login.php";</script>';
exit;
