<?php
/**
 * DanaHibah™ - Toggle Device Status
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

verify_csrf_get();

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];

$device = db_fetch_one($conn, "SELECT id, serial_no, status FROM devices WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($device) {
    // For devices, maybe cycle through online/offline/tampered? Or just active/inactive
    // The user requirement says: "make it inactive .. only admin and super can make it active and inactive"
    // For devices, the status enum is 'online','offline','tampered'
    $new_status = $device['status'] === 'online' ? 'offline' : 'online';
    
    db_execute($conn, "UPDATE devices SET status=?, updated_by=?, updated_at=NOW() WHERE id=?", 'sii', [$new_status, $uid, $id]);
    log_activity($conn, $uid, 'update', 'devices', "Changed status of {$device['serial_no']} to $new_status", $id);
    set_flash('success', "Device marked as $new_status.");
} else {
    set_flash('error', 'Device not found.');
}
redirect('modules/devices/index.php');
