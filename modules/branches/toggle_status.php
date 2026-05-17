<?php
/**
 * DanaHibah™ - Toggle Branch Status
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];

$branch = db_fetch_one($conn, "SELECT id, name, status FROM branches WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($branch) {
    $new_status = $branch['status'] === 'active' ? 'inactive' : 'active';
    db_execute($conn, "UPDATE branches SET status=?, updated_by=?, updated_at=NOW() WHERE id=?", 'sii', [$new_status, $uid, $id]);
    log_activity($conn, $uid, 'update', 'branches', "Changed status of {$branch['name']} to $new_status", $id);
    set_flash('success', "Branch marked as $new_status.");
} else {
    set_flash('error', 'Branch not found.');
}
redirect('modules/branches/index.php');
