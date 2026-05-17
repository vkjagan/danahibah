<?php
/**
 * DanaHibah™ - Global Helper Functions
 */

// ─── Output Sanitisation ────────────────────────────────────────────────────
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function clean($str) {
    return trim(strip_tags($str));
}

// ─── CSRF Tokens ─────────────────────────────────────────────────────────────
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || !hash_equals(csrf_token(), $_POST['csrf_token'])) {
        json_response(false, 'Invalid security token. Please refresh and try again.');
        exit;
    }
}

function verify_csrf_get() {
    if (!isset($_GET['csrf']) || !hash_equals(csrf_token(), $_GET['csrf'])) {
        set_flash('error', 'Invalid security token. Please try again.');
        redirect_back();
        exit;
    }
}

// ─── JSON Responses ──────────────────────────────────────────────────────────
function json_response($status, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => $status,
        'message' => $message,
        'data'    => $data
    ]);
    exit;
}

// ─── Redirect ─────────────────────────────────────────────────────────────────
function redirect($url) {
    header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
    exit;
}

function redirect_back() {
    $ref = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/index.php';
    header('Location: ' . $ref);
    exit;
}

// ─── Flash Messages ──────────────────────────────────────────────────────────
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function show_flash() {
    $flash = get_flash();
    if ($flash) {
        $type = $flash['type']; // success | danger | warning | info
        $icon = match($type) {
            'success' => 'bi-check-circle-fill',
            'danger'  => 'bi-x-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            default   => 'bi-info-circle-fill'
        };
        echo '<div class="alert alert-' . e($type) . ' alert-dismissible fade show d-flex align-items-center gap-2 flash-message" role="alert">';
        echo '<i class="bi ' . e($icon) . '"></i>';
        echo '<span>' . e($flash['message']) . '</span>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

// ─── Database Helpers ────────────────────────────────────────────────────────
function db_query($conn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('DB Prepare failed: ' . mysqli_error($conn) . ' | SQL: ' . $sql);
        return false;
    }
    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return $stmt;
}

function db_fetch_all($conn, $sql, $types = '', $params = []) {
    $stmt = db_query($conn, $sql, $types, $params);
    if (!$stmt) return [];
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function db_fetch_one($conn, $sql, $types = '', $params = []) {
    $stmt = db_query($conn, $sql, $types, $params);
    if (!$stmt) return null;
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function db_insert($conn, $sql, $types = '', $params = []) {
    $stmt = db_query($conn, $sql, $types, $params);
    if (!$stmt) return false;
    return mysqli_insert_id($conn);
}

function db_execute($conn, $sql, $types = '', $params = []) {
    $stmt = db_query($conn, $sql, $types, $params);
    if (!$stmt) return false;
    return mysqli_stmt_affected_rows($stmt);
}

// ─── Activity Logging ────────────────────────────────────────────────────────
function log_activity($conn, $user_id, $action, $module, $description = '', $record_id = null) {
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '';
    $agent     = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $branch_id = $_SESSION['user_branch_id'] ?? null;
    
    $sql    = "INSERT INTO audit_logs (user_id, branch_id, action, module, description, record_id, ip_address, user_agent, created_at)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    db_query($conn, $sql, 'iisssiss', [$user_id, $branch_id, $action, $module, $description, $record_id, $ip, $agent]);
}

// ─── Format Helpers ──────────────────────────────────────────────────────────
function format_money($amount, $currency = 'RM') {
    return $currency . ' ' . number_format((float)$amount, 2);
}

function format_date($datetime, $format = 'd M Y') {
    if (!$datetime) return '—';
    return date($format, strtotime($datetime));
}

function format_datetime($datetime) {
    return format_date($datetime, 'd M Y, h:i A');
}

function time_ago($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'Just now';
    if ($diff < 3600)   return floor($diff/60) . ' min ago';
    if ($diff < 86400)  return floor($diff/3600) . ' hr ago';
    if ($diff < 604800) return floor($diff/86400) . ' days ago';
    return format_date($datetime);
}

// ─── Pagination ───────────────────────────────────────────────────────────────
function paginate($conn, $sql, $count_sql, $types, $params, $page, $per_page = DEFAULT_PER_PAGE) {
    $offset     = ($page - 1) * $per_page;
    $count_stmt = db_query($conn, $count_sql, $types, $params);
    $count_res  = mysqli_stmt_get_result($count_stmt);
    $total      = (int)mysqli_fetch_row($count_res)[0];
    $total_pages = (int)ceil($total / $per_page);

    $params[]  = $per_page;
    $params[]  = $offset;
    $data_sql  = $sql . ' LIMIT ? OFFSET ?';
    $rows      = db_fetch_all($conn, $data_sql, $types . 'ii', $params);

    return [
        'rows'        => $rows,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => $total_pages,
    ];
}

// ─── String Helpers ───────────────────────────────────────────────────────────
function str_limit($str, $limit = 50) {
    return strlen($str) > $limit ? substr($str, 0, $limit) . '…' : $str;
}

function slugify($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return preg_replace('/-+/', '-', $str);
}

function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

// ─── Status Badge ─────────────────────────────────────────────────────────────
function status_badge($status) {
    $map = [
        'active'    => ['success', 'Active'],
        'inactive'  => ['secondary', 'Inactive'],
        'pending'   => ['warning', 'Pending'],
        'approved'  => ['success', 'Approved'],
        'rejected'  => ['danger', 'Rejected'],
        'collected' => ['info', 'Collected'],
        'verified'  => ['primary', 'Verified'],
        'banked'    => ['success', 'Banked'],
        'online'    => ['success', 'Online'],
        'offline'   => ['danger', 'Offline'],
        'tampered'  => ['danger', 'Tampered'],
    ];
    $s = strtolower($status);
    [$cls, $label] = $map[$s] ?? ['secondary', ucfirst($s)];
    return '<span class="badge bg-' . $cls . '">' . $label . '</span>';
}

// ─── Permission Check ─────────────────────────────────────────────────────────
function has_permission($conn, $user_id, $module, $action = 'view') {
    $row = db_fetch_one($conn,
        "SELECT p.id FROM permissions p
         JOIN role_permissions rp ON rp.permission_id = p.id
         JOIN user_roles ur ON ur.role_id = rp.role_id
         WHERE ur.user_id = ? AND p.module = ? AND p.action = ? LIMIT 1",
        'iss', [$user_id, $module, $action]
    );
    return !empty($row);
}

/**
 * Get branch-level SQL filter based on user role
 * @param string $prefix Table alias prefix (e.g. 'u.')
 * @param string $column Column name (e.g. 'branch_id' or 'id')
 * @return string SQL WHERE fragment
 */
function get_branch_filter($prefix = '', $column = 'branch_id') {
    // Super Admins and Management see everything
    if (is_super_admin() || is_management()) {
        return " 1=1 ";
    }
    
    $branch_id = (int)($_SESSION['user_branch_id'] ?? 0);
    return " {$prefix}{$column} = $branch_id ";
}

/**
 * Calculate current ledger balances (Cash and Bank) for validation.
 * @param mysqli $conn
 * @param int $branch_id
 * @return array ['cash' => float, 'bank' => float]
 */
function get_ledger_balances($conn, $branch_id) {
    $b_filter = $branch_id ? "branch_id = " . (int)$branch_id : "1=1";
    
    // Cash In (Total Verified/Approved/Banked)
    $cash_in_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM collections WHERE status IN ('verified','approved','banked') AND deleted_at IS NULL AND $b_filter", '', []);
    $cash_in = (float)$cash_in_row['total'];

    // Bank Deposits (Total)
    $bank_dep_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM bank_deposits WHERE deleted_at IS NULL AND $b_filter", '', []);
    $bank_deposit_total = (float)$bank_dep_row['total'];

    // Expenses Cash vs Bank
    $exp_cash_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE payment_source='cash' AND status='approved' AND deleted_at IS NULL AND $b_filter", '', []);
    $exp_cash = (float)$exp_cash_row['total'];

    $exp_bank_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE payment_source='bank' AND status='approved' AND deleted_at IS NULL AND $b_filter", '', []);
    $exp_bank = (float)$exp_bank_row['total'];

    return [
        'cash' => $cash_in - $bank_deposit_total - $exp_cash,
        'bank' => $bank_deposit_total - $exp_bank
    ];
}
