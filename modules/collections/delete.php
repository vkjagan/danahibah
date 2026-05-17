<?php
/**
 * DanaHibah™ - Delete Collection
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];
$collection = db_fetch_one($conn, "SELECT id, txn_ref, status FROM collections WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($collection) {
    if ($collection['status'] !== 'collected') {
        set_flash('error', 'Cannot delete this collection because it has been ' . $collection['status'] . '.');
    } else {
        db_execute($conn, "UPDATE collections SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
        log_activity($conn, $uid, 'delete', 'collections', 'Deleted collection: ' . $collection['txn_ref'], $id);
        set_flash('success', 'Collection deleted successfully.');
    }
} else {
    set_flash('error', 'Collection not found.');
}
redirect('modules/collections/index.php');
