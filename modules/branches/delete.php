<?php
/**
 * DanaHibah™ - Delete Branch
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];

$branch = db_fetch_one($conn, "SELECT id, name FROM branches WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($branch) {
    db_execute($conn, "UPDATE branches SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
    log_activity($conn, $uid, 'delete', 'branches', 'Deleted branch: ' . $branch['name'], $id);
    set_flash('success', 'Branch deleted successfully.');
} else {
    set_flash('error', 'Branch not found or already deleted.');
}

redirect('modules/branches/index.php');
