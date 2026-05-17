<?php
/**
 * DanaHibah™ - Audit Trail
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

$page_title  = 'Audit Trail';
$active_menu = 'audit';
$breadcrumbs = [['label' => 'Audit Trail']];

$date_from = clean($_GET['date_from'] ?? date('Y-m-01'));
$date_to   = clean($_GET['date_to']   ?? date('Y-m-d'));
$module    = clean($_GET['module']    ?? '');
$action    = clean($_GET['action']    ?? '');
$user_id   = (int)($_GET['user_id']  ?? 0);

$where  = "DATE(a.created_at) BETWEEN ? AND ?";
$params = [$date_from, $date_to]; $types = 'ss';
if ($module)  { $where .= " AND a.module=?";  $params[]=$module;  $types.='s'; }
if ($action)  { $where .= " AND a.action=?";  $params[]=$action;  $types.='s'; }
if ($user_id) { $where .= " AND a.user_id=?"; $params[]=$user_id; $types.='i'; }
$where .= " AND " . get_branch_filter('a.');

$logs  = db_fetch_all($conn,
    "SELECT a.*, u.full_name FROM audit_logs a LEFT JOIN users u ON u.id=a.user_id
     WHERE $where ORDER BY a.created_at DESC LIMIT 1000", $types, $params);
$users = db_fetch_all($conn,"SELECT id,full_name FROM users WHERE deleted_at IS NULL ORDER BY full_name",'', []);
$modules_list = db_fetch_all($conn,"SELECT DISTINCT module FROM audit_logs ORDER BY module",'', []);

include INCLUDES_PATH . 'header.php';
?>
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-shield-check me-2" style="color:var(--gold);"></i>Audit Trail</h1>
        <p class="page-subtitle">Complete log of all system activities and changes</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control" value="<?=e($date_from)?>"></div>
            <div class="col-md-2"><label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control" value="<?=e($date_to)?>"></div>
            <div class="col-md-2"><label class="form-label">Module</label>
                <select name="module" class="form-select"><option value="">All Modules</option>
                    <?php foreach($modules_list as $m): ?>
                    <option value="<?=e($m['module'])?>" <?=$module===$m['module']?'selected':''?>><?=ucfirst(e($m['module']))?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-2"><label class="form-label">Action</label>
                <select name="action" class="form-select"><option value="">All Actions</option>
                    <?php foreach(['create','update','delete','login','logout','view'] as $a): ?>
                    <option value="<?=$a?>" <?=$action===$a?'selected':''?>><?=ucfirst($a)?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-2"><label class="form-label">User</label>
                <select name="user_id" class="form-select"><option value="">All Users</option>
                    <?php foreach($users as $u): ?>
                    <option value="<?=(int)$u['id']?>" <?=$user_id==$u['id']?'selected':''?>><?=e($u['full_name'])?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-list-check me-2"></i><span class="card-title">Activity Log</span>
        <span class="ms-2 badge" style="background:var(--primary);"><?=count($logs)?> entries</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="auditTable">
                <thead><tr><th>#</th><th>User</th><th>Action</th><th>Module</th><th>Description</th><th>IP Address</th><th>Date & Time</th></tr></thead>
                <tbody>
                    <?php foreach($logs as $i=>$log): ?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td><?= $log['full_name'] ? '<strong>'.e($log['full_name']).'</strong>' : '<span class="text-muted">System</span>' ?></td>
                        <td>
                            <?php
                            $amap = ['create'=>['success','plus-circle'],'update'=>['primary','pencil'],
                                     'delete'=>['danger','trash'],'login'=>['info','box-arrow-in-right'],
                                     'logout'=>['secondary','box-arrow-right'],'view'=>['light','eye']];
                            [$cls,$icon] = $amap[$log['action']] ?? ['secondary','dot'];
                            echo "<span class='badge bg-{$cls} text-".($cls==='light'?'dark':'white')."'><i class='bi bi-{$icon} me-1'></i>".ucfirst(e($log['action']))."</span>";
                            ?>
                        </td>
                        <td><span class="badge bg-light text-dark text-capitalize"><?=e($log['module'])?></span></td>
                        <td style="font-size:.82rem;max-width:300px;"><?=e(str_limit($log['description']??'',80))?></td>
                        <td><code style="font-size:.75rem;"><?=e($log['ip_address']??'—')?></code></td>
                        <td style="font-size:.78rem;white-space:nowrap;"><?=format_datetime($log['created_at'])?></td>
                    </tr>
                    <?php endforeach; if(empty($logs)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No audit entries found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$extra_js = '<script>$(document).ready(function(){$("#auditTable").DataTable({order:[[6,"desc"]],pageLength:50});});</script>';
include INCLUDES_PATH . 'footer.php';
?>
