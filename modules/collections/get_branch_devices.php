<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$branch_id = (int)($_GET['branch_id'] ?? 0);
if (!$branch_id) json_response(false, 'Invalid branch');

// Security check: Branch Admin can only fetch their own branch devices
if (!is_super_admin() && !is_management() && $_SESSION['user_branch_id'] != $branch_id) {
    json_response(false, 'Access denied');
}

$devices = db_fetch_all($conn, "SELECT id, serial_no, model FROM devices WHERE branch_id=? AND deleted_at IS NULL", 'i', [$branch_id]);
json_response(true, '', $devices);
