<?php
/**
 * DanaHibah™ - Edit Branch
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Edit Branch';
$active_menu = 'branches';
$breadcrumbs = [['label'=>'Branches','url'=>APP_URL.'/modules/branches/index.php'],['label'=>'Edit']];

$id = (int)($_GET['id'] ?? 0);
$branch = db_fetch_one($conn, "SELECT * FROM branches WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$branch) {
    set_flash('error', 'Branch not found.');
    redirect('modules/branches/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'name'      => clean($_POST['name']      ?? ''),
        'code'      => strtoupper(clean($_POST['code'] ?? '')),
        'type'      => clean($_POST['type']      ?? 'masjid'),
        'address'   => clean($_POST['address']   ?? ''),
        'city'      => clean($_POST['city']      ?? ''),
        'state'     => clean($_POST['state']     ?? ''),
        'postcode'  => clean($_POST['postcode']  ?? ''),
        'pic_name'  => clean($_POST['pic_name']  ?? ''),
        'pic_phone' => clean($_POST['pic_phone'] ?? ''),
        'pic_email' => clean($_POST['pic_email'] ?? ''),
        'status'    => clean($_POST['status']    ?? 'active'),
    ];

    if (!$data['name']) $errors[] = 'Branch name is required.';

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        db_execute($conn,
            "UPDATE branches SET name=?, code=?, type=?, address=?, city=?, state=?, postcode=?, pic_name=?, pic_phone=?, pic_email=?, status=?, updated_by=?, updated_at=NOW() WHERE id=?",
            'sssssssssssii',
            [$data['name'], $data['code'], $data['type'], $data['address'], $data['city'], $data['state'], $data['postcode'], $data['pic_name'], $data['pic_phone'], $data['pic_email'], $data['status'], $uid, $id]
        );
        
        log_activity($conn, $uid, 'update', 'branches', 'Updated branch: ' . $data['name'], $id);
        set_flash('success', 'Branch updated successfully.');
        redirect('modules/branches/index.php');
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3 flex-wrap">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-pencil me-2" style="color:var(--gold);"></i>Edit Branch</h1>
        <p class="page-subtitle">Update information for <?= e($branch['name']) ?></p>
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
                <div class="col-md-8">
                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= e($branch['name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Branch Code</label>
                    <input type="text" name="code" class="form-control" value="<?= e($branch['code']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <?php foreach(['masjid'=>'Masjid','surau'=>'Surau','wakaf'=>'Wakaf','ngo'=>'Islamic NGO','other'=>'Other'] as $v=>$l): ?>
                        <option value="<?=$v?>" <?= $branch['type'] == $v ? 'selected' : '' ?>><?=$l?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= $branch['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $branch['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-12"><hr class="my-2"></div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"><?= e($branch['address']) ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= e($branch['city']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <select name="state" class="form-select">
                        <option value="">— State —</option>
                        <?php foreach(['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu','W.P. Kuala Lumpur','W.P. Labuan','W.P. Putrajaya'] as $s): ?>
                        <option <?= $branch['state'] == $s ? 'selected' : '' ?>><?=$s?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Postcode</label>
                    <input type="text" name="postcode" class="form-control" value="<?= e($branch['postcode']) ?>">
                </div>
                <div class="col-12"><hr class="my-2"></div>
                <div class="col-md-4">
                    <label class="form-label">PIC Name</label>
                    <input type="text" name="pic_name" class="form-control" value="<?= e($branch['pic_name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">PIC Phone</label>
                    <input type="tel" name="pic_phone" class="form-control" value="<?= e($branch['pic_phone']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">PIC Email</label>
                    <input type="email" name="pic_email" class="form-control" value="<?= e($branch['pic_email']) ?>">
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
