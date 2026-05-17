<?php
/**
 * DanaHibah™ - Edit Device
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$id = (int)($_GET['id'] ?? 0);
$device = db_fetch_one($conn, "SELECT * FROM devices WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$device) {
    set_flash('error', 'Device not found.');
    redirect('modules/devices/index.php');
}

$page_title  = 'Edit Device';
$active_menu = 'devices';
$breadcrumbs = [['label'=>'Devices','url'=>APP_URL.'/modules/devices/index.php'],['label'=>'Edit']];
$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL ORDER BY name", '', []);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'branch_id'   => (int)($_POST['branch_id']  ?? 0),
        'serial_no'   => strtoupper(clean($_POST['serial_no'] ?? '')),
        'model'       => clean($_POST['model']      ?? ''),
        'type'        => clean($_POST['type']       ?? 'hybrid'),
        'firmware_ver'=> clean($_POST['firmware_ver']?? ''),
        'status'      => clean($_POST['status']     ?? 'offline'),
    ];

    if (!$data['branch_id']) $errors[] = 'Branch is required.';
    if (!$data['serial_no']) $errors[] = 'Serial number is required.';

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        db_execute($conn,
            "UPDATE devices SET branch_id=?, serial_no=?, model=?, type=?, firmware_ver=?, status=?, updated_by=?, updated_at=NOW() WHERE id=?",
            'isssssii', [$data['branch_id'], $data['serial_no'], $data['model'], $data['type'], $data['firmware_ver'], $data['status'], $uid, $id]);
        
        log_activity($conn, $uid, 'update', 'devices', "Updated device: " . $data['serial_no'], $id);
        set_flash('success', 'Device updated successfully.');
        redirect('modules/devices/index.php');
    }
} else {
    $data = $device;
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-pencil me-2" style="color:var(--gold);"></i>Edit Device</h1>
        <p class="page-subtitle"><?= e($device['serial_no']) ?></p>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach($errors as $e): ?><li><?=e($e)?></li><?php endforeach;?></ul></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <?php foreach ($branches as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= $data['branch_id'] == $b['id'] ? 'selected':'' ?>><?= e($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Serial Number <span class="text-danger">*</span></label>
                    <input type="text" name="serial_no" class="form-control" required value="<?= e($data['serial_no']) ?>" style="text-transform:uppercase;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="<?= e($data['model']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="hybrid" <?= $data['type'] == 'hybrid' ? 'selected':'' ?>>Hybrid (Cash + QR)</option>
                        <option value="cash_box" <?= $data['type'] == 'cash_box' ? 'selected':'' ?>>Cash Box Only</option>
                        <option value="qr_terminal" <?= $data['type'] == 'qr_terminal' ? 'selected':'' ?>>QR Terminal Only</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Firmware Version</label>
                    <input type="text" name="firmware_ver" class="form-control" value="<?= e($data['firmware_ver']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="online" <?= $data['status'] == 'online' ? 'selected':'' ?>>Online</option>
                        <option value="offline" <?= $data['status'] == 'offline' ? 'selected':'' ?>>Offline</option>
                        <option value="tampered" <?= $data['status'] == 'tampered' ? 'selected':'' ?>>Tampered</option>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save Changes</button>
                    <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
