<?php
/**
 * DanaHibah™ - Expenses Management
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$page_title  = 'Expenses';
$active_menu = 'expenses';
$breadcrumbs = [['label' => 'Expenses']];

// Build filter
$where = "e.deleted_at IS NULL AND " . get_branch_filter('e.');
$params = [];
$types = '';

// Fetch expenses
$sql = "SELECT e.*, b.name AS branch_name, u.full_name AS creator
        FROM expenses e
        LEFT JOIN branches b ON b.id = e.branch_id
        LEFT JOIN users u ON u.id = e.created_by
        WHERE $where
        ORDER BY e.expense_date DESC, e.id DESC";
$rows = db_fetch_all($conn, $sql, $types, $params);

// Calculate Total Expenses
$total_expense = 0;
foreach ($rows as $row) {
    $total_expense += (float)$row['amount'];
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-wallet2 me-2" style="color:var(--danger);"></i>Expenses</h1>
        <p class="page-subtitle">Manage mosque daily expenditures and bills</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="text-end">
            <span class="text-muted d-block" style="font-size:0.75rem;">Total Expenses</span>
            <strong class="text-danger fs-5"><?= format_money($total_expense) ?></strong>
        </div>
        <a href="add.php" class="btn btn-gold"><i class="bi bi-plus-circle me-2"></i>Record Expense</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="expensesTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Title & Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Recorded By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><strong><?= date('d M Y', strtotime($row['expense_date'])) ?></strong></td>
                        <td><?= e($row['branch_name'] ?? '—') ?></td>
                        <td>
                            <div class="fw-bold"><?= e($row['title']) ?></div>
                            <span class="badge bg-secondary-subtle text-secondary"><?= e(ucfirst($row['category'])) ?></span>
                        </td>
                        <td><strong class="text-danger"><?= format_money($row['amount']) ?></strong></td>
                        <td>
                            <?php if (($row['status'] ?? 'pending') === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:.82rem;"><?= e($row['creator'] ?? '—') ?></td>
                        <td class="text-end text-nowrap">
                            <?php if (!is_committee() && ($row['status'] ?? 'pending') === 'pending'): ?>
                            <a href="approve.php?id=<?= $row['id'] ?>&csrf=<?= csrf_token() ?>" class="btn btn-sm btn-outline-success" data-confirm="Proceed with the next workflow step?" title="Approve Expense">
                                <i class="bi bi-check-circle"></i>
                            </a>
                            <?php endif; ?>
                            <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (($row['status'] ?? 'pending') !== 'approved'): ?>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit Expense">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (is_super_admin() || is_branch_admin()): ?>
                            <a href="delete.php?id=<?= $row['id'] ?>&csrf=<?= csrf_token() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this expense record?" title="Delete Expense">
                                <i class="bi bi-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$extra_js = <<<'JS'
<script>
$(document).ready(function() {
    $('#expensesTable').DataTable({
        order: [[0, 'desc']]
    });
});
</script>
JS;
include INCLUDES_PATH . 'footer.php';
?>
