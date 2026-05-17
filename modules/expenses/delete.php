<?php
/**
 * DanaHibah™ - Delete Expense
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

verify_csrf_get();

if (!is_super_admin() && !is_branch_admin()) {
    set_flash('error', 'Only Branch Administrators or Super Admins can delete expenses.');
    redirect('modules/expenses/index.php');
}

$id = (int)($_GET['id'] ?? 0);
$uid = (int)$_SESSION['user_id'];
$expense = db_fetch_one($conn, "SELECT id, title FROM expenses WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if ($expense) {
    db_execute($conn, "UPDATE expenses SET deleted_at=NOW(), updated_by=? WHERE id=?", 'ii', [$uid, $id]);
    log_activity($conn, $uid, 'delete', 'expenses', 'Deleted expense: ' . $expense['title'], $id);
    set_flash('success', 'Expense deleted successfully.');
} else {
    set_flash('error', 'Expense not found.');
}
redirect('modules/expenses/index.php');
