<?php
/**
 * DanaHibah™ - Void Bank Deposit
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();
verify_csrf_get();

if (!is_super_admin() && !is_branch_admin()) {
    set_flash('error', 'Only Branch Administrators or Super Admins can void deposits.');
    redirect('modules/deposits/index.php');
}

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];
$deposit = db_fetch_one($conn, "SELECT id, amount, branch_id FROM bank_deposits WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($deposit) {
    if (is_branch_admin() && $deposit['branch_id'] != $_SESSION['user_branch_id']) {
        set_flash('error', 'Access denied.');
        redirect('modules/deposits/index.php');
    }

    db_execute($conn, "UPDATE bank_deposits SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
    log_activity($conn, $uid, 'delete', 'bank_deposits', 'Voided bank deposit of RM ' . format_money($deposit['amount']), $id);
    set_flash('success', 'Deposit successfully voided. The amount has been returned to Cash in Hand.');
} else {
    set_flash('error', 'Deposit not found.');
}
redirect('modules/deposits/index.php');
