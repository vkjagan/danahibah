<?php
/**
 * DanaHibah™ - Authentication System
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Start secure session
function start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => (APP_ENV === 'production'),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

// Require authenticated user, redirect to login if not
function require_auth() {
    start_session();
    if (!is_logged_in()) {
        set_flash('warning', 'Please log in to continue.');
        redirect('login.php');
    }
    check_session_timeout();
}

// Check if user is logged in
function is_logged_in() {
    return !empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in']);
}

// Session timeout check
function check_session_timeout() {
    if (isset($_SESSION['last_activity'])) {
        if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            logout_user();
            set_flash('warning', 'Your session has expired. Please log in again.');
            redirect('login.php');
        }
    }
    $_SESSION['last_activity'] = time();
}

// Attempt login — returns [success, message, user_data]
function attempt_login($conn, $username, $password) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    // Check lockout
    if (is_locked_out($conn, $username, $ip)) {
        return [false, 'Account temporarily locked due to too many failed attempts. Try again in 15 minutes.', null];
    }

    $user = db_fetch_one($conn,
        "SELECT * FROM users WHERE (username = ? OR email = ?) AND deleted_at IS NULL LIMIT 1",
        'ss', [$username, $username]
    );

    if (!$user || !password_verify($password, $user['password'])) {
        record_failed_login($conn, $username, $ip);
        return [false, 'Invalid username or password.', null];
    }

    if ($user['status'] !== 'active') {
        return [false, 'Your account is inactive. Please contact the administrator.', null];
    }

    // Clear failed attempts
    db_execute($conn,
        "DELETE FROM login_attempts WHERE username = ? OR ip_address = ?",
        'ss', [$username, $ip]
    );

    // Set session
    session_regenerate_id(true);
    $_SESSION['user_id']      = $user['id'];
    $_SESSION['user_name']    = $user['full_name'];
    $_SESSION['user_email']   = $user['email'];
    $_SESSION['user_username']= $user['username'];
    $_SESSION['user_role']    = $user['role_id'];
    $_SESSION['user_branch_id'] = $user['branch_id'];
    $_SESSION['user_avatar']  = $user['avatar'] ?? '';
    $_SESSION['user_logged_in'] = true;
    $_SESSION['last_activity']  = time();
    $_SESSION['csrf_token']     = bin2hex(random_bytes(32));

    // Log login
    $ip_addr = $ip;
    $agent   = $_SERVER['HTTP_USER_AGENT'] ?? '';
    db_execute($conn,
        "INSERT INTO login_logs (user_id, ip_address, user_agent, status, created_at) VALUES (?, ?, ?, 'success', NOW())",
        'iss', [$user['id'], $ip_addr, $agent]
    );

    // Update last login
    db_execute($conn,
        "UPDATE users SET last_login = NOW() WHERE id = ?",
        'i', [$user['id']]
    );

    return [true, 'Login successful.', $user];
}

// Logout
function logout_user() {
    start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

// Failed login tracking
function record_failed_login($conn, $username, $ip) {
    db_execute($conn,
        "INSERT INTO login_attempts (username, ip_address, attempted_at) VALUES (?, ?, NOW())",
        'ss', [$username, $ip]
    );
    // Log attempt
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    db_execute($conn,
        "INSERT INTO login_logs (user_id, ip_address, user_agent, status, created_at) VALUES (NULL, ?, ?, 'failed', NOW())",
        'ss', [$ip, $agent]
    );
}

function is_locked_out($conn, $username, $ip) {
    $cutoff = date('Y-m-d H:i:s', time() - LOGIN_LOCKOUT_TIME);
    $count  = db_fetch_one($conn,
        "SELECT COUNT(*) as cnt FROM login_attempts
         WHERE (username = ? OR ip_address = ?) AND attempted_at > ?",
        'sss', [$username, $ip, $cutoff]
    );
    return ((int)($count['cnt'] ?? 0)) >= MAX_LOGIN_ATTEMPTS;
}

// Get current logged-in user data
function get_logged_in_user($conn) {
    if (!is_logged_in()) return null;
    return db_fetch_one($conn,
        "SELECT * FROM users WHERE id = ? AND deleted_at IS NULL",
        'i', [$_SESSION['user_id']]
    );
}

// Role Checks
function is_super_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1 && empty($_SESSION['user_branch_id']);
}
function is_management() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 4;
}
function is_branch_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1 && !empty($_SESSION['user_branch_id']);
}
function is_committee() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2;
}

// Admin-level functions (Super Admin or Branch Admin)
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

function require_super_admin() {
    if (!is_super_admin()) {
        set_flash('danger', 'Super Administrator access required.');
        redirect('index.php');
    }
}

function require_admin() {
    if (!is_admin()) {
        set_flash('danger', 'Administrator access required.');
        redirect('index.php');
    }
}
