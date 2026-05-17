<?php
/**
 * DanaHibah™ - Approve Expense
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

// Only Admins can approve
if (is_committee()) {
    set_flash('error', 'Access denied.');
    redirect('modules/expenses/index.php');
}

$id = (int)($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if (!isset($_GET['csrf']) || $_GET['csrf'] !== csrf_token()) {
    set_flash('error', 'Invalid security token.');
    redirect('modules/expenses/index.php');
}

$expense = db_fetch_one($conn, "SELECT * FROM expenses WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$expense) {
    set_flash('error', 'Expense not found.');
    redirect('modules/expenses/index.php');
}

// Branch Admin check
if (is_branch_admin() && $expense['branch_id'] != $_SESSION['user_branch_id']) {
    set_flash('error', 'Access denied.');
    redirect('modules/expenses/index.php');
}

if ($expense['status'] === 'approved') {
    set_flash('info', 'Expense is already approved.');
    redirect('modules/expenses/index.php');
}

// ── Strict Balance Safeguard ──
$balances = get_ledger_balances($conn, $expense['branch_id']);
if ($expense['payment_source'] === 'cash' && $expense['amount'] > $balances['cash']) {
    set_flash('danger', 'Insufficient Cash in Hand (RM ' . number_format($balances['cash'], 2) . '). Cannot approve this expense.');
    redirect('modules/expenses/index.php');
}
if ($expense['payment_source'] === 'bank' && $expense['amount'] > $balances['bank']) {
    set_flash('danger', 'Insufficient Bank Balance (RM ' . number_format($balances['bank'], 2) . '). Cannot approve this expense.');
    redirect('modules/expenses/index.php');
}

$uid = (int)$_SESSION['user_id'];
db_execute($conn, "UPDATE expenses SET status = 'approved', updated_by = ? WHERE id = ?", 'ii', [$uid, $id]);

log_activity($conn, $uid, 'approve', 'expenses', "Approved expense ID: $id", $id);

set_flash('success', 'Expense has been approved successfully.');
redirect('modules/expenses/index.php');
