<?php
/**
 * DanaHibah™ - Edit Collection
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$id = (int)($_GET['id'] ?? 0);
$collection = db_fetch_one($conn, "SELECT * FROM collections WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$collection) {
    set_flash('error', 'Collection not found.');
    redirect('modules/collections/index.php');
}

// Lock Check: Committee cannot edit if status is NOT 'collected'
if (is_committee() && $collection['status'] !== 'collected') {
    set_flash('error', 'This collection has been ' . $collection['status'] . ' and is now locked for editing.');
    redirect('modules/collections/index.php');
}

$page_title  = 'Edit Collection';
$active_menu = 'collections';
$breadcrumbs = [['label'=>'Collections','url'=>APP_URL.'/modules/collections/index.php'],['label'=>'Edit']];

$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL ORDER BY name", '', []);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'branch_id'  => (int)($_POST['branch_id'] ?? 0),
        'channel'    => clean($_POST['channel']   ?? 'cash'),
        'amount'     => (float)($_POST['amount']  ?? 0),
        'category'   => clean($_POST['category']  ?? 'general'),
        'donor_name' => clean($_POST['donor_name']?? ''),
        'donor_phone'=> clean($_POST['donor_phone']?? ''),
        'notes'      => clean($_POST['notes']     ?? ''),
        'collected_at'=> clean($_POST['collected_at'] ?? ''),
    ];

    if (!$data['branch_id'])  $errors[] = 'Branch is required.';
    if ($data['amount'] <= 0) $errors[] = 'Amount must be greater than 0.';
    if (!$data['channel'])    $errors[] = 'Channel is required.';

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        db_execute($conn,
            "UPDATE collections SET branch_id=?, channel=?, amount=?, category=?, donor_name=?, donor_phone=?, notes=?, collected_at=?, updated_by=?, updated_at=NOW() WHERE id=?",
            'isdsssssii',
            [$data['branch_id'], $data['channel'], $data['amount'], $data['category'], $data['donor_name'], $data['donor_phone'], $data['notes'], $data['collected_at'], $uid, $id]
        );
        log_activity($conn, $uid, 'update', 'collections', 'Updated collection: ' . $collection['txn_ref'], $id);
        set_flash('success', 'Collection updated successfully.');
        redirect('modules/collections/index.php');
    }
} else {
    $data = $collection;
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-pencil me-2" style="color:var(--gold);"></i>Edit Collection</h1>
        <p class="page-subtitle"><?= e($collection['txn_ref']) ?></p>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach($errors as $e): ?><li><?=e($e)?></li><?php endforeach;?></ul></div>
<?php endif; ?>

<form method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <?php foreach($branches as $b): ?>
                            <option value="<?=$b['id']?>" <?= $data['branch_id']==$b['id']?'selected':'' ?>><?=e($b['name'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Channel</label>
                        <select name="channel" class="form-select" required>
                            <?php foreach(['cash'=>'Cash','qr'=>'QR Payment','manual'=>'Manual','online'=>'Online'] as $v=>$l): ?>
                            <option value="<?=$v?>" <?= $data['channel']==$v?'selected':'' ?>><?=$l?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Amount (RM)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" value="<?=e($data['amount'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <?php foreach(['general'=>'General','friday'=>'Friday Prayer','zakat'=>'Zakat','wakaf'=>'Wakaf','special'=>'Special Campaign','sadaqah'=>'Sadaqah'] as $v=>$l): ?>
                            <option value="<?=$v?>" <?= $data['category']==$v?'selected':'' ?>><?=$l?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date Collected</label>
                        <input type="datetime-local" name="collected_at" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['collected_at'])) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"><?=e($data['notes'])?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><span class="card-title">Donor Info</span></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="donor_name" class="form-control" value="<?=e($data['donor_name'])?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="donor_phone" class="form-control" value="<?=e($data['donor_phone'])?>">
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save</button>
            </div>
        </div>
    </div>
</form>

<?php include INCLUDES_PATH . 'footer.php'; ?>
