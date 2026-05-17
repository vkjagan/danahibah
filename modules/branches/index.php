<?php
/**
 * DanaHibah™ - Branches Module
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Branch Management';
$active_menu = 'branches';
$breadcrumbs = [['label' => 'Branches']];
$errors = []; $data = [];

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
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
        $id  = db_insert($conn,
            "INSERT INTO branches (name,code,type,address,city,state,postcode,pic_name,pic_phone,pic_email,status,created_by,created_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())",
            'sssssssssssi',
            [$data['name'],$data['code'],$data['type'],$data['address'],$data['city'],
             $data['state'],$data['postcode'],$data['pic_name'],$data['pic_phone'],
             $data['pic_email'],$data['status'],$uid]);
        if ($id) {
            log_activity($conn, $uid, 'create', 'branches', 'Added branch: '.$data['name'], $id);
            set_flash('success', 'Branch "'.$data['name'].'" added successfully.');
            redirect('modules/branches/index.php');
        }
    }
}

$branches = db_fetch_all($conn,
    "SELECT b.*, (SELECT COUNT(*) FROM devices d WHERE d.branch_id=b.id AND d.deleted_at IS NULL) AS device_count,
            (SELECT COALESCE(SUM(amount),0) FROM collections c WHERE c.branch_id=b.id AND c.deleted_at IS NULL) AS total_collected
     FROM branches b WHERE b.deleted_at IS NULL AND " . get_branch_filter('b.', 'id') . " ORDER BY b.name",
    '', []);

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-building me-2" style="color:var(--gold);"></i>Branch Management</h1>
        <p class="page-subtitle">Manage mosque and surau branches</p>
    </div>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addBranchModal">
        <i class="bi bi-plus-circle me-2"></i>Add Branch
    </button>
</div>

<!-- Branches Grid -->
<div class="row g-3 mb-4">
    <?php foreach ($branches as $b): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon green" style="width:42px;height:42px;font-size:1rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <?= status_badge($b['status']) ?>
                </div>
                <h6 class="fw-700 mb-1"><?= e($b['name']) ?></h6>
                <?php if ($b['code']): ?>
                <div style="font-size:.75rem;" class="text-muted mb-2"><?= e($b['code']) ?></div>
                <?php endif; ?>
                <div class="d-flex gap-3 mb-3" style="font-size:.82rem;color:var(--text-muted);">
                    <span><i class="bi bi-geo-alt me-1"></i><?= e($b['city'] ?: '—') ?></span>
                    <span><i class="bi bi-hdd-rack me-1"></i><?= (int)$b['device_count'] ?> device(s)</span>
                </div>
                <div class="p-2 rounded mb-3" style="background:var(--bg);font-size:.82rem;">
                    <strong>Total Collected:</strong> <?= format_money($b['total_collected']) ?>
                </div>
                <?php if ($b['pic_name']): ?>
                <div style="font-size:.78rem;color:var(--text-muted);">
                    <i class="bi bi-person me-1"></i><?= e($b['pic_name']) ?>
                    <?php if ($b['pic_phone']): ?> &middot; <?= e($b['pic_phone']) ?><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent border-top py-2 d-flex gap-2">
                <a href="edit.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-primary flex-fill">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <?php if (is_admin()): ?>
                <a href="toggle_status.php?id=<?= (int)$b['id'] ?>&csrf=<?= csrf_token() ?>"
                   class="btn btn-sm btn-outline-<?= $b['status']==='active'?'warning':'success' ?>"
                   title="<?= $b['status']==='active'?'Deactivate':'Activate' ?>">
                    <i class="bi bi-<?= $b['status']==='active'?'pause-circle':'play-circle' ?>"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($branches)): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-building fs-1 d-block mb-3"></i>
                No branches yet. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add the first branch</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;">
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add">
                <div class="modal-header" style="background:var(--primary);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-building me-2"></i>Add New Branch</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($errors): ?>
                    <div class="alert alert-danger"><ul class="mb-0 ps-3">
                        <?php foreach($errors as $e): ?><li><?=e($e)?></li><?php endforeach;?>
                    </ul></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Masjid Al-Falah Petaling Jaya">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Branch Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. MSJ-PJ-001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <?php foreach(['masjid'=>'Masjid','surau'=>'Surau','wakaf'=>'Wakaf','ngo'=>'Islamic NGO','other'=>'Other'] as $v=>$l): ?>
                                <option value="<?=$v?>"><?=$l?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <select name="state" class="form-select">
                                <option value="">— State —</option>
                                <?php foreach(['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu','W.P. Kuala Lumpur','W.P. Labuan','W.P. Putrajaya'] as $s): ?>
                                <option><?=$s?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Full address"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PIC Name</label>
                            <input type="text" name="pic_name" class="form-control" placeholder="Person in Charge">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PIC Phone</label>
                            <input type="tel" name="pic_phone" class="form-control" placeholder="+60...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PIC Email</label>
                            <input type="email" name="pic_email" class="form-control" placeholder="pic@example.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if ($errors) echo "<script>document.addEventListener('DOMContentLoaded',()=>{ new bootstrap.Modal(document.getElementById('addBranchModal')).show(); });</script>";
include INCLUDES_PATH . 'footer.php';
?>
