<?php
/**
 * DanaHibah™ - Bank Deposits (Ledger)
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$page_title  = 'Bank Deposits';
$active_menu = 'deposits';
$breadcrumbs = [['label' => 'Bank Deposits']];

$where = "d.deleted_at IS NULL AND " . get_branch_filter('d.');

$sql = "SELECT d.*, b.name AS branch_name, u.full_name AS creator
        FROM bank_deposits d
        LEFT JOIN branches b ON b.id = d.branch_id
        LEFT JOIN users u ON u.id = d.created_by
        WHERE $where
        ORDER BY d.deposit_date DESC, d.id DESC";

$rows = db_fetch_all($conn, $sql, '', []);

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-bank me-2 text-info"></i>Bank Deposits</h1>
        <p class="page-subtitle">Manage bulk cash deposits into the bank account</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Record Deposit</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="depositsTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Ref No.</th>
                        <th>Amount</th>
                        <th>Recorded By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><strong><?= date('d M Y', strtotime($row['deposit_date'])) ?></strong></td>
                        <td><?= e($row['branch_name'] ?? '—') ?></td>
                        <td><code><?= e($row['ref_no'] ?: '—') ?></code></td>
                        <td><strong class="text-success"><?= format_money($row['amount']) ?></strong></td>
                        <td style="font-size:.82rem;"><?= e($row['creator'] ?? '—') ?></td>
                        <td class="text-end text-nowrap">
                            <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (is_super_admin() || is_branch_admin()): ?>
                            <a href="delete.php?id=<?= $row['id'] ?>&csrf=<?= csrf_token() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Void this deposit? This will return the amount to Cash in Hand." title="Void Deposit">
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
    $('#depositsTable').DataTable({
        order: [[0, 'desc']]
    });
});
</script>
JS;
include INCLUDES_PATH . 'footer.php';
?>
