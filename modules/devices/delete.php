<?php
/**
 * DanaHibah™ - Delete Device
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];
$device = db_fetch_one($conn, "SELECT id, serial_no FROM devices WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($device) {
    db_execute($conn, "UPDATE devices SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
    log_activity($conn, $uid, 'delete', 'devices', 'Deleted device: ' . $device['serial_no'], $id);
    set_flash('success', 'Device deleted successfully.');
} else {
    set_flash('error', 'Device not found.');
}
redirect('modules/devices/index.php');
