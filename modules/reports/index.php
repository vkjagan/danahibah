<?php
/**
 * DanaHibah™ - Reports Module
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Reports';
$active_menu = 'reports';
$breadcrumbs = [['label' => 'Reports']];

$date_from = clean($_GET['date_from'] ?? date('Y-m-01'));
$date_to   = clean($_GET['date_to']   ?? date('Y-m-d'));
$branch_id = (int)($_GET['branch_id'] ?? 0);
$channel   = clean($_GET['channel']   ?? '');
$status    = clean($_GET['status']    ?? '');

$where  = "c.deleted_at IS NULL AND DATE(c.collected_at) BETWEEN ? AND ? AND " . get_branch_filter('c.');
$params = [$date_from, $date_to]; $types = 'ss';
if ($branch_id) { $where .= " AND c.branch_id=?"; $params[]=$branch_id; $types.='i'; }
if ($channel)   { $where .= " AND c.channel=?";   $params[]=$channel;   $types.='s'; }
if ($status)    { $where .= " AND c.status=?";     $params[]=$status;    $types.='s'; }

$rows      = db_fetch_all($conn,"SELECT c.*,b.name AS branch_name FROM collections c LEFT JOIN branches b ON b.id=c.branch_id WHERE $where ORDER BY c.collected_at DESC",$types,$params);
$summary   = db_fetch_one($conn,"SELECT COALESCE(SUM(amount),0) AS total,COUNT(*) AS cnt,AVG(amount) AS avg_amount,MAX(amount) AS max_amount FROM collections c WHERE $where",$types,$params);
$by_channel= db_fetch_all($conn,"SELECT channel,COALESCE(SUM(amount),0) AS total,COUNT(*) AS cnt FROM collections c WHERE $where GROUP BY channel",$types,$params);
$by_branch = db_fetch_all($conn,"SELECT b.name,COALESCE(SUM(c.amount),0) AS total,COUNT(c.id) AS cnt FROM collections c LEFT JOIN branches b ON b.id=c.branch_id WHERE $where GROUP BY c.branch_id ORDER BY total DESC",$types,$params);
$branches  = db_fetch_all($conn,"SELECT id,name FROM branches WHERE deleted_at IS NULL ORDER BY name",'', []);

include INCLUDES_PATH . 'header.php';
?>
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-file-earmark-bar-graph me-2" style="color:var(--gold);"></i>Reports</h1>
        <p class="page-subtitle">Generate and export donation collection reports</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control" value="<?=e($date_from)?>"></div>
            <div class="col-md-2"><label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control" value="<?=e($date_to)?>"></div>
            <div class="col-md-3"><label class="form-label">Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    <?php foreach($branches as $b): ?>
                    <option value="<?=(int)$b['id']?>" <?=$branch_id==$b['id']?'selected':''?>><?=e($b['name'])?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-2"><label class="form-label">Channel</label>
                <select name="channel" class="form-select"><option value="">All</option>
                    <?php foreach(['cash','qr','manual','online'] as $c): ?>
                    <option value="<?=$c?>" <?=$channel===$c?'selected':''?>><?=ucfirst($c)?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-2"><label class="form-label">Status</label>
                <select name="status" class="form-select"><option value="">All</option>
                    <?php foreach(['collected','verified','approved','banked','rejected'] as $s): ?>
                    <option value="<?=$s?>" <?=$status===$s?'selected':''?>><?=ucfirst($s)?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button></div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card">
        <div class="stat-icon gold"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-value" style="font-size:1.3rem;"><?=format_money($summary['total'])?></div>
        <div class="stat-label">Total Collected</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-receipt"></i></div>
        <div class="stat-value"><?=number_format((int)$summary['cnt'])?></div>
        <div class="stat-label">Transactions</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calculator"></i></div>
        <div class="stat-value" style="font-size:1.3rem;"><?=format_money($summary['avg_amount'])?></div>
        <div class="stat-label">Average Amount</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="stat-card">
        <div class="stat-icon gold"><i class="bi bi-trophy"></i></div>
        <div class="stat-value" style="font-size:1.3rem;"><?=format_money($summary['max_amount'])?></div>
        <div class="stat-label">Highest Transaction</div>
    </div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card h-100">
        <div class="card-header"><i class="bi bi-pie-chart me-2"></i><span class="card-title">By Channel</span></div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0"><thead><tr><th>Channel</th><th>Count</th><th>Total</th></tr></thead>
            <tbody><?php foreach($by_channel as $r): ?>
            <tr><td class="text-capitalize"><?=e($r['channel'])?></td><td><?=number_format((int)$r['cnt'])?></td><td><strong><?=format_money($r['total'])?></strong></td></tr>
            <?php endforeach; if(empty($by_channel)): ?><tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr><?php endif; ?></tbody></table>
        </div>
    </div></div>
    <div class="col-md-6"><div class="card h-100">
        <div class="card-header"><i class="bi bi-building me-2"></i><span class="card-title">By Branch</span></div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0"><thead><tr><th>Branch</th><th>Count</th><th>Total</th></tr></thead>
            <tbody><?php foreach($by_branch as $r): ?>
            <tr><td><?=e($r['name']??'Unknown')?></td><td><?=number_format((int)$r['cnt'])?></td><td><strong><?=format_money($r['total'])?></strong></td></tr>
            <?php endforeach; if(empty($by_branch)): ?><tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr><?php endif; ?></tbody></table>
        </div>
    </div></div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-table me-2"></i><span class="card-title">Transaction Detail</span>
        <span class="ms-2 badge" style="background:var(--primary);"><?=count($rows)?> records</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="reportTable">
                <thead><tr><th>#</th><th>Ref No.</th><th>Branch</th><th>Channel</th><th>Category</th><th>Amount</th><th>Status</th><th>Date</th><th style="width:80px;">Actions</th></tr></thead>
                <tbody>
                    <?php foreach($rows as $i=>$row): ?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td><code style="font-size:.75rem;"><?=e($row['txn_ref']??'—')?></code></td>
                        <td><?=e($row['branch_name']??'—')?></td>
                        <td class="text-capitalize"><?=e($row['channel'])?></td>
                        <td class="text-capitalize"><?=e($row['category'])?></td>
                        <td><strong><?=format_money($row['amount'])?></strong></td>
                        <td><?=status_badge($row['status'])?></td>
                        <td style="font-size:.78rem;"><?=format_datetime($row['collected_at'])?></td>
                        <td>
                            <a href="<?= APP_URL ?>/modules/collections/view.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; if(empty($rows)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No records found for the selected filters.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$extra_js = '<script>$(document).ready(function(){$("#reportTable").DataTable({pageLength:50});});</script>';
include INCLUDES_PATH . 'footer.php';
?>
