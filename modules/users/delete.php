<?php
/**
 * DanaHibah™ - Delete User
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
    set_flash('error', 'You cannot delete your own account.');
    redirect('modules/users/index.php');
}

$user = db_fetch_one($conn, "SELECT id, username FROM users WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($user) {
    db_execute($conn, "UPDATE users SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
    log_activity($conn, $uid, 'delete', 'users', 'Deleted user: ' . $user['username'], $id);
    set_flash('success', 'User deleted successfully.');
} else {
    set_flash('error', 'User not found.');
}
redirect('modules/users/index.php');
