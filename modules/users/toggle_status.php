<?php
/**
 * DanaHibah™ - Toggle User Status
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];

if ($id === $uid) {
    set_flash('error', 'You cannot change your own status this way.');
    redirect('modules/users/index.php');
}

$user = db_fetch_one($conn, "SELECT id, username, status FROM users WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($user) {
    $new_status = $user['status'] === 'active' ? 'inactive' : 'active';
    db_execute($conn, "UPDATE users SET status=?, updated_by=?, updated_at=NOW() WHERE id=?", 'sii', [$new_status, $uid, $id]);
    log_activity($conn, $uid, 'update', 'users', "Changed status of {$user['username']} to $new_status", $id);
    set_flash('success', "User marked as $new_status.");
}
redirect('modules/users/index.php');
