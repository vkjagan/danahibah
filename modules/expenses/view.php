<?php
/**
 * DanaHibah™ - View Expense
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT e.*, b.name AS branch_name, uc.full_name AS creator, uu.full_name AS updater
        FROM expenses e
        LEFT JOIN branches b ON b.id = e.branch_id
        LEFT JOIN users uc ON uc.id = e.created_by
        LEFT JOIN users uu ON uu.id = e.updated_by
        WHERE e.id = ? AND e.deleted_at IS NULL";

$expense = db_fetch_one($conn, $sql, 'i', [$id]);

if (!$expense) {
    set_flash('error', 'Expense not found.');
    redirect('modules/expenses/index.php');
}

// Ensure user has access to this branch
if (is_committee() || is_branch_admin()) {
    if ($expense['branch_id'] != $_SESSION['user_branch_id']) {
        set_flash('error', 'Access denied.');
        redirect('modules/expenses/index.php');
    }
}

$page_title  = 'View Expense';
$active_menu = 'expenses';
$breadcrumbs = [
    ['label' => 'Expenses', 'url' => 'index.php'],
    ['label' => 'Expense Details']
];

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h1 class="page-title"><i class="bi bi-wallet2 me-2 text-info"></i>Expense Details</h1>
            <p class="page-subtitle">View detailed breakdown of this expenditure</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="edit.php?id=<?= $expense['id'] ?>" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Edit</a>
        <?php if(is_super_admin() || is_branch_admin()): ?>
            <a href="delete.php?id=<?= $expense['id'] ?>&csrf=<?= csrf_token() ?>" class="btn btn-danger" data-confirm="Delete this expense record?"><i class="bi bi-trash me-2"></i>Delete</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle text-primary me-2"></i><span class="card-title">Expense Information</span>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th style="width:200px;" class="bg-light">Branch</th>
                            <td><strong><?= e($expense['branch_name'] ?? '—') ?></strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Title / Item</th>
                            <td><?= e($expense['title']) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Category</th>
                            <td><span class="badge bg-secondary"><?= e(ucfirst($expense['category'])) ?></span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Amount</th>
                            <td><strong class="text-danger fs-5"><?= format_money($expense['amount']) ?></strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Payment Source</th>
                            <td><span class="badge <?= ($expense['payment_source']??'cash') === 'cash' ? 'bg-success' : 'bg-info text-dark' ?>"><i class="bi <?= ($expense['payment_source']??'cash') === 'cash' ? 'bi-cash-stack' : 'bi-bank' ?> me-1"></i><?= ucfirst($expense['payment_source']??'cash') ?></span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Expense Date</th>
                            <td><?= date('d M Y', strtotime($expense['expense_date'])) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Description / Remarks</th>
                            <td><?= nl2br(e($expense['description'] ?: '—')) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Receipt / Invoice</th>
                            <td>
                                <?php if ($expense['receipt_file']): ?>
                                    <a href="<?= APP_URL ?>/uploads/receipts/<?= e($expense['receipt_file']) ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-file-earmark-text me-1"></i> View Document
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No receipt uploaded</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Audit -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-clock-history text-secondary me-2"></i><span class="card-title">Audit Info</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="font-size:0.85rem;">
                    <li class="mb-3">
                        <div class="text-muted mb-1">Recorded By</div>
                        <div class="fw-bold"><?= e($expense['creator'] ?? 'System') ?></div>
                        <div class="text-muted small"><?= date('d M Y h:i A', strtotime($expense['created_at'])) ?></div>
                    </li>
                    <?php if ($expense['updated_by']): ?>
                    <li>
                        <div class="text-muted mb-1">Last Updated By</div>
                        <div class="fw-bold"><?= e($expense['updater'] ?? 'Unknown') ?></div>
                        <div class="text-muted small"><?= date('d M Y h:i A', strtotime($expense['updated_at'])) ?></div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
