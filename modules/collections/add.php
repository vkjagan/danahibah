<?php
/**
 * DanaHibah™ - Add Collection
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Add Collection';
$active_menu = 'collections';
$breadcrumbs = [['label'=>'Collections','url'=>APP_URL.'/modules/collections/index.php'],['label'=>'Add']];

$user_branch_id = $_SESSION['user_branch_id'] ?? null;
$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE status='active' AND deleted_at IS NULL AND " . get_branch_filter('', 'id') . " ORDER BY name", '', []);
$errors = [];
$data   = [];

// Auto-select branch for branch users
if ($user_branch_id && empty($data['branch_id'])) {
    $data['branch_id'] = $user_branch_id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'branch_id'  => (int)($_POST['branch_id'] ?? 0),
        'device_id'  => (int)($_POST['device_id'] ?? 0),
        'channel'    => clean($_POST['channel']   ?? 'cash'),
        'amount'     => (float)($_POST['amount']  ?? 0),
        'category'   => clean($_POST['category']  ?? 'general'),
        'donor_name' => clean($_POST['donor_name']?? ''),
        'donor_phone'=> clean($_POST['donor_phone']?? ''),
        'notes'      => clean($_POST['notes']     ?? ''),
        'collected_at'=> clean($_POST['collected_at'] ?? date('Y-m-d H:i:s')),
    ];

    // Force branch_id for branch users
    if ($user_branch_id) {
        $data['branch_id'] = $user_branch_id;
    }

    if (!$data['branch_id'])    $errors[] = 'Branch is required.';
    if ($data['amount'] <= 0)   $errors[] = 'Amount must be greater than 0.';
    if (!$data['channel'])      $errors[] = 'Channel is required.';

    if (empty($errors)) {
        $txn_ref = 'TXN-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $receipt = 'RCP-' . date('Ymd') . '-' . rand(1000,9999);
        $uid     = (int)$_SESSION['user_id'];

        $id = db_insert($conn,
            "INSERT INTO collections (branch_id, device_id, channel, amount, category, donor_name, donor_phone,
             notes, txn_ref, receipt_no, status, collected_at, collected_by, created_by, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'collected', ?, ?, ?, NOW())",
            'iisssssssssii',
            [$data['branch_id'], $data['device_id'] ?: null, $data['channel'], $data['amount'], $data['category'],
             $data['donor_name'], $data['donor_phone'], $data['notes'],
             $txn_ref, $receipt, $data['collected_at'], $uid, $uid]
        );

        if ($id) {
            log_activity($conn, $uid, 'create', 'collections', "Added collection $txn_ref", $id);
            set_flash('success', "Collection {$txn_ref} added successfully.");
            redirect('modules/collections/index.php');
        } else {
            $errors[] = 'Failed to save. Please try again.';
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3 flex-wrap">
    <a href="index.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="page-title"><i class="bi bi-plus-circle me-2" style="color:var(--gold);"></i>Add Collection</h1>
        <p class="page-subtitle">Record a new donation collection</p>
    </div>
    <div class="ms-auto">
        <button form="collectionForm" type="submit" class="btn btn-gold">
            <i class="bi bi-save me-2"></i>Save Collection
        </button>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <i class="bi bi-x-circle-fill me-2"></i>
    <ul class="mb-0 ps-3">
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" id="collectionForm" novalidate>
    <?= csrf_field() ?>
    <div class="row g-3">

        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>
                    <span class="card-title">Collection Details</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Branch / Location <span class="text-danger">*</span></label>
                            <?php if ($user_branch_id): ?>
                                <input type="text" class="form-control" value="<?= e($branches[0]['name'] ?? 'Assigned Branch') ?>" readonly>
                                <input type="hidden" name="branch_id" id="branchSelect" value="<?= (int)$user_branch_id ?>">
                            <?php else: ?>
                                <select name="branch_id" class="form-select" required id="branchSelect">
                                    <option value="">— Select Branch —</option>
                                    <?php foreach ($branches as $b): ?>
                                    <option value="<?= (int)$b['id'] ?>"
                                        <?= ($data['branch_id'] ?? 0) == $b['id'] ? 'selected' : '' ?>>
                                        <?= e($b['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Device ID (Optional)</label>
                            <select name="device_id" class="form-select" id="deviceSelect">
                                <option value="">— Select Device —</option>
                            </select>
                            <div id="deviceLoad" class="spinner-border spinner-border-sm text-primary d-none mt-1"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Collection Channel <span class="text-danger">*</span></label>
                            <select name="channel" class="form-select" required>
                                <?php foreach (['cash'=>'Cash','qr'=>'QR Payment','manual'=>'Manual','online'=>'Online'] as $val=>$lbl): ?>
                                <option value="<?= $val ?>" <?= ($data['channel'] ?? 'cash') === $val ? 'selected' : '' ?>>
                                    <?= $lbl ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Amount (MYR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text fw-600">RM</span>
                                <input type="number" name="amount" class="form-control" required
                                       step="0.01" min="0.01" placeholder="0.00"
                                       value="<?= e($data['amount'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <?php foreach (['general'=>'General','friday'=>'Friday Prayer','zakat'=>'Zakat','wakaf'=>'Wakaf','special'=>'Special Campaign','sadaqah'=>'Sadaqah'] as $v=>$l): ?>
                                <option value="<?= $v ?>" <?= ($data['category'] ?? 'general') === $v ? 'selected' : '' ?>>
                                    <?= $l ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date & Time Collected</label>
                            <input type="datetime-local" name="collected_at" class="form-control"
                                   value="<?= e(str_replace(' ','T', $data['collected_at'] ?? date('Y-m-d\TH:i'))) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"
                                      placeholder="Optional remarks..."><?= e($data['notes'] ?? '') ?></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Donor Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person me-2"></i>
                    <span class="card-title">Donor Info (Optional)</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Donor Name</label>
                        <input type="text" name="donor_name" class="form-control"
                               placeholder="Anonymous if blank"
                               value="<?= e($data['donor_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Donor Phone</label>
                        <input type="tel" name="donor_phone" class="form-control"
                               placeholder="+60 1X-XXXXXXX"
                               value="<?= e($data['donor_phone'] ?? '') ?>">
                    </div>
                    <div class="alert alert-info py-2 mb-0" style="font-size:.78rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Donor info is optional. Leave blank to record as anonymous.
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<?php
$extra_js = <<<'JS'
<script>
$(document).ready(function(){
    const branchSelect = $('#branchSelect');
    const deviceSelect = $('#deviceSelect');
    const deviceLoad   = $('#deviceLoad');

    function loadDevices(branchId) {
        if (!branchId) {
            deviceSelect.html('<option value="">— Select Device —</option>');
            return;
        }
        deviceLoad.removeClass('d-none');
        $.ajax({
            url: 'get_branch_devices.php',
            data: { branch_id: branchId },
            dataType: 'json',
            success: function(res) {
                if (res && res.status) {
                    let html = '<option value="">— Select Device —</option>';
                    if (res.data && res.data.length > 0) {
                        res.data.forEach(d => {
                            html += `<option value="${d.id}">${d.serial_no} (${d.model})</option>`;
                        });
                        deviceSelect.html(html);
                        if (res.data.length === 1) {
                            deviceSelect.val(res.data[0].id);
                        }
                    } else {
                        deviceSelect.html('<option value="">— No Devices Found —</option>');
                    }
                }
            },
            complete: function() {
                deviceLoad.addClass('d-none');
            }
        });
    }

    // Initial load
    const initialBranch = branchSelect.val() || '<?= (int)$user_branch_id ?>';
    if (initialBranch) loadDevices(initialBranch);

    branchSelect.on('change', function(){
        loadDevices($(this).val());
    });
});
</script>
JS;
include INCLUDES_PATH . 'footer.php'; ?>
