<?php
/**
 * DanaHibah™ - Devices Module
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Device Management';
$active_menu = 'devices';
$breadcrumbs = [['label' => 'Devices']];
$errors = []; $data = [];

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    verify_csrf();
    $data = [
        'branch_id'  => (int)($_POST['branch_id']  ?? 0),
        'serial_no'  => strtoupper(clean($_POST['serial_no']  ?? '')),
        'model'      => clean($_POST['model']      ?? ''),
        'type'       => clean($_POST['type']       ?? 'hybrid'),
        'firmware_ver'=> clean($_POST['firmware_ver']?? ''),
    ];
    if (!$data['branch_id']) $errors[] = 'Branch is required.';
    if (!$data['serial_no']) $errors[] = 'Serial number is required.';

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        $id  = db_insert($conn,
            "INSERT INTO devices (branch_id,serial_no,model,type,status,firmware_ver,created_by,created_at)
             VALUES (?,?,?,?,'offline',?,?,NOW())",
            'issssi', [$data['branch_id'],$data['serial_no'],$data['model'],$data['type'],$data['firmware_ver'],$uid]);
        if ($id) {
            log_activity($conn,$uid,'create','devices','Registered device: '.$data['serial_no'],$id);
            set_flash('success','Device '.$data['serial_no'].' registered successfully.');
            redirect('modules/devices/index.php');
        }
    }
}

$branches = db_fetch_all($conn,"SELECT id,name FROM branches WHERE status='active' AND deleted_at IS NULL ORDER BY name",'', []);
$devices  = db_fetch_all($conn,
    "SELECT d.*,b.name AS branch_name FROM devices d
     LEFT JOIN branches b ON b.id=d.branch_id
     WHERE d.deleted_at IS NULL AND " . get_branch_filter('d.') . " ORDER BY d.created_at DESC",'', []);

// Device status summary
$online  = count(array_filter($devices, fn($d)=>$d['status']==='online'));
$offline = count(array_filter($devices, fn($d)=>$d['status']==='offline'));
$tampered= count(array_filter($devices, fn($d)=>$d['status']==='tampered'));

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-hdd-rack me-2" style="color:var(--gold);"></i>Device Management</h1>
        <p class="page-subtitle">Monitor and manage DanaHibah™ hardware devices</p>
    </div>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
        <i class="bi bi-plus-circle me-2"></i>Register Device
    </button>
</div>

<!-- Status Summary -->
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card text-center">
            <div class="stat-icon green mx-auto mb-2"><i class="bi bi-wifi"></i></div>
            <div class="stat-value"><?= $online ?></div>
            <div class="stat-label">Online</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card text-center">
            <div class="stat-icon" style="background:rgba(100,116,139,.1);color:var(--text-muted);" class="mx-auto mb-2">
                <i class="bi bi-wifi-off"></i>
            </div>
            <div class="stat-value"><?= $offline ?></div>
            <div class="stat-label">Offline</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card text-center">
            <div class="stat-icon red mx-auto mb-2"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-value"><?= $tampered ?></div>
            <div class="stat-label">Tampered</div>
        </div>
    </div>
</div>

<!-- Devices Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="devicesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serial No.</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Firmware</th>
                        <th>Last Sync</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devices as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code style="font-size:.82rem;"><?= e($d['serial_no']) ?></code></td>
                        <td><?= e($d['model'] ?: '—') ?></td>
                        <td><span class="badge bg-light text-dark text-capitalize"><?= e($d['type']) ?></span></td>
                        <td><?= e($d['branch_name'] ?? '—') ?></td>
                        <td><?= status_badge($d['status']) ?></td>
                        <td><small><?= e($d['firmware_ver'] ?: '—') ?></small></td>
                        <td style="font-size:.82rem;">
                            <?= $d['last_sync'] ? format_datetime($d['last_sync']) : '<span class="text-muted">Never</span>' ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="edit.php?id=<?= (int)$d['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if (is_admin()): ?>
                                <a href="toggle_status.php?id=<?= (int)$d['id'] ?>&csrf=<?= csrf_token() ?>"
                                   class="btn btn-sm btn-outline-<?= $d['status']==='online'?'warning':'success' ?>" title="Toggle Status">
                                    <i class="bi bi-<?= $d['status']==='online'?'power':'plug' ?>"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($devices)): ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-hdd-rack fs-1 d-block mb-2"></i>
                        No devices registered yet.
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Device Modal -->
<div class="modal fade" id="addDeviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add">
                <div class="modal-header" style="background:var(--primary);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-hdd-rack me-2"></i>Register Device</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($errors): ?>
                    <div class="alert alert-danger"><ul class="mb-0 ps-3">
                        <?php foreach($errors as $e): ?><li><?=e($e)?></li><?php endforeach;?></ul></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" required>
                                <option value="">— Select Branch —</option>
                                <?php foreach ($branches as $b): ?>
                                <option value="<?= (int)$b['id'] ?>"><?= e($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" name="serial_no" class="form-control" required
                                   placeholder="e.g. DH-2024-001" style="text-transform:uppercase;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" placeholder="Device model">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="hybrid">Hybrid (Cash + QR)</option>
                                <option value="cash_box">Cash Box Only</option>
                                <option value="qr_terminal">QR Terminal Only</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Firmware Version</label>
                            <input type="text" name="firmware_ver" class="form-control" placeholder="e.g. v1.2.3">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extra_js = '<script>$(document).ready(function(){ $("#devicesTable").DataTable({order:[[7,"desc"]]}); });</script>';
if ($errors) echo "<script>document.addEventListener('DOMContentLoaded',()=>{ new bootstrap.Modal(document.getElementById('addDeviceModal')).show(); });</script>";
include INCLUDES_PATH . 'footer.php';
?>
