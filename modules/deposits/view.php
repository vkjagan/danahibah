<?php
/**
 * DanaHibah™ - View Bank Deposit
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT d.*, b.name AS branch_name, uc.full_name AS creator
        FROM bank_deposits d
        LEFT JOIN branches b ON b.id = d.branch_id
        LEFT JOIN users uc ON uc.id = d.created_by
        WHERE d.id = ? AND d.deleted_at IS NULL";

$deposit = db_fetch_one($conn, $sql, 'i', [$id]);

if (!$deposit) {
    set_flash('error', 'Deposit not found.');
    redirect('modules/deposits/index.php');
}

if (is_committee() || is_branch_admin()) {
    if ($deposit['branch_id'] != $_SESSION['user_branch_id']) {
        set_flash('error', 'Access denied.');
        redirect('modules/deposits/index.php');
    }
}

$page_title  = 'View Deposit';
$active_menu = 'deposits';
$breadcrumbs = [
    ['label' => 'Bank Deposits', 'url' => 'index.php'],
    ['label' => 'Deposit Details']
];

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h1 class="page-title"><i class="bi bi-bank me-2 text-info"></i>Deposit Details</h1>
            <p class="page-subtitle">View information regarding this bank deposit</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if(is_super_admin() || is_branch_admin()): ?>
            <a href="delete.php?id=<?= $deposit['id'] ?>&csrf=<?= csrf_token() ?>" class="btn btn-danger" data-confirm="Void this deposit? This will return the funds to Cash in Hand."><i class="bi bi-trash me-2"></i>Void Deposit</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle text-primary me-2"></i><span class="card-title">Deposit Information</span>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th style="width:200px;" class="bg-light">Branch</th>
                            <td><strong><?= e($deposit['branch_name'] ?? '—') ?></strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Deposit Date</th>
                            <td><?= date('d M Y', strtotime($deposit['deposit_date'])) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Amount Deposited</th>
                            <td><strong class="text-success fs-5"><?= format_money($deposit['amount']) ?></strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Reference / Txn No.</th>
                            <td><code><?= e($deposit['ref_no'] ?: '—') ?></code></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Remarks</th>
                            <td><?= nl2br(e($deposit['remarks'] ?: '—')) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Deposit Slip / Receipt</th>
                            <td>
                                <?php if ($deposit['receipt_file']): ?>
                                    <a href="<?= APP_URL ?>/uploads/receipts/<?= e($deposit['receipt_file']) ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-file-earmark-text me-1"></i> View Document
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No document uploaded</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-clock-history text-secondary me-2"></i><span class="card-title">Audit Info</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="font-size:0.85rem;">
                    <li class="mb-3">
                        <div class="text-muted mb-1">Recorded By</div>
                        <div class="fw-bold"><?= e($deposit['creator'] ?? 'System') ?></div>
                        <div class="text-muted small"><?= date('d M Y h:i A', strtotime($deposit['created_at'])) ?></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
