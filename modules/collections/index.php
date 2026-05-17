<?php
/**
 * DanaHibah™ - Collections List
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Collections';
$active_menu = 'collections';
$breadcrumbs = [['label' => 'Collections']];

// Stats
$branch_where = get_branch_filter();
$stat_all  = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt FROM collections WHERE deleted_at IS NULL AND $branch_where", '', []);
$stat_pend = db_fetch_one($conn, "SELECT COUNT(*) AS cnt FROM collections WHERE status='collected' AND deleted_at IS NULL AND $branch_where", '', []);

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-cash-stack me-2" style="color:var(--gold);"></i>Collections</h1>
        <p class="page-subtitle">Manage and monitor all donation transactions</p>
    </div>
    <a href="add.php" class="btn btn-gold">
        <i class="bi bi-plus-circle me-2"></i>Add Collection
    </a>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-collection"></i></div>
            <div class="stat-value"><?= number_format((int)$stat_all['cnt']) ?></div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-value"><?= format_money($stat_all['total']) ?></div>
            <div class="stat-label">Total Collected</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value"><?= number_format((int)$stat_pend['cnt']) ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-bank"></i></div>
            <div class="stat-value">
                <?php
                $branch_where = get_branch_filter();

                $banked = db_fetch_one($conn, "SELECT COUNT(*) AS cnt FROM collections WHERE status='banked' AND deleted_at IS NULL AND $branch_where", '', []);
                echo number_format((int)$banked['cnt']);
                ?>
            </div>
            <div class="stat-label">Banked</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label mb-1" style="font-size:.78rem;">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="<?= e($_GET['date_from'] ?? date('Y-m-01')) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1" style="font-size:.78rem;">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="<?= e($_GET['date_to'] ?? date('Y-m-d')) ?>">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label mb-1" style="font-size:.78rem;">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <?php foreach (['collected','verified','approved','banked','rejected'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($_GET['status'] ?? '') === $s ? 'selected' : '' ?>>
                        <?= ucfirst($s) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label mb-1" style="font-size:.78rem;">Channel</label>
                <select name="channel" class="form-select form-select-sm">
                    <option value="">All Channels</option>
                    <?php foreach (['cash','qr','manual','online'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($_GET['channel'] ?? '') === $c ? 'selected' : '' ?>>
                        <?= ucfirst($c) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="collectionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Branch</th>
                        <th>Channel</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $where = "c.deleted_at IS NULL AND " . get_branch_filter('c.');
                    $params = []; $types = '';

                    if (!empty($_GET['date_from'])) {
                        $where .= " AND DATE(c.collected_at) >= ?";
                        $params[] = $_GET['date_from']; $types .= 's';
                    }
                    if (!empty($_GET['date_to'])) {
                        $where .= " AND DATE(c.collected_at) <= ?";
                        $params[] = $_GET['date_to']; $types .= 's';
                    }
                    if (!empty($_GET['status'])) {
                        $where .= " AND c.status = ?";
                        $params[] = $_GET['status']; $types .= 's';
                    }
                    if (!empty($_GET['channel'])) {
                        $where .= " AND c.channel = ?";
                        $params[] = $_GET['channel']; $types .= 's';
                    }

                    $rows = db_fetch_all($conn,
                        "SELECT c.*, b.name AS branch_name
                         FROM collections c
                         LEFT JOIN branches b ON b.id = c.branch_id
                         WHERE $where ORDER BY c.collected_at DESC LIMIT 500",
                        $types, $params);

                    foreach ($rows as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code style="font-size:.78rem;"><?= e($row['txn_ref'] ?? '—') ?></code></td>
                        <td><?= e($row['branch_name'] ?? '—') ?></td>
                        <td>
                            <span class="badge bg-light text-dark text-capitalize">
                                <?= e($row['channel']) ?>
                            </span>
                        </td>
                        <td class="text-capitalize"><?= e($row['category']) ?></td>
                        <td><strong><?= format_money($row['amount']) ?></strong></td>
                        <td><?= status_badge($row['status']) ?></td>
                        <td style="font-size:.82rem;"><?= format_datetime($row['collected_at']) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="view.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (!is_committee() || $row['status'] === 'collected'): ?>
                                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= (int)$row['id'] ?>&csrf=<?= csrf_token() ?>"
                                   class="btn btn-sm btn-outline-danger" title="Delete"
                                   data-confirm="Delete this collection record?">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Custom DataTables config removed to use global app.js defaults
include INCLUDES_PATH . 'footer.php';
?>
