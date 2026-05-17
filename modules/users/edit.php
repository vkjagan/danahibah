<?php
/**
 * DanaHibah™ - Edit User
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

$id = (int)($_GET['id'] ?? 0);
$user = db_fetch_one($conn, "SELECT * FROM users WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$user) {
    set_flash('error', 'User not found.');
    redirect('modules/users/index.php');
}

$page_title  = 'Edit User';
$active_menu = 'users';
$breadcrumbs = [['label'=>'Users','url'=>APP_URL.'/modules/users/index.php'],['label'=>'Edit']];
$roles  = db_fetch_all($conn, "SELECT * FROM roles ORDER BY id", '', []);
$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL ORDER BY name", '', []);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'full_name' => clean($_POST['full_name'] ?? ''),
        'email'     => clean($_POST['email']     ?? ''),
        'phone'     => clean($_POST['phone']     ?? ''),
        'role_id'   => (int)($_POST['role_id']  ?? 2),
        'branch_id' => !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null,
        'status'    => clean($_POST['status']    ?? 'active'),
        'password'  => $_POST['password']        ?? '',
    ];

    if (!$data['full_name'])  $errors[] = 'Full name is required.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

    // Unique checks
    if (!$errors) {
        $ex = db_fetch_one($conn, "SELECT id FROM users WHERE email=? AND id!=? AND deleted_at IS NULL",
                           'si', [$data['email'], $id]);
        if ($ex) $errors[] = 'Email already in use by another account.';
    }

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        
        if ($data['password']) {
            if (strlen($data['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            } else {
                $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost'=>12]);
                db_execute($conn,
                    "UPDATE users SET role_id=?, branch_id=?, full_name=?, email=?, phone=?, password=?, status=?, updated_by=?, updated_at=NOW() WHERE id=?",
                    'iisssssii', [$data['role_id'], $data['branch_id'], $data['full_name'], $data['email'], $data['phone'], $hash, $data['status'], $uid, $id]);
            }
        } else {
            db_execute($conn,
                "UPDATE users SET role_id=?, branch_id=?, full_name=?, email=?, phone=?, status=?, updated_by=?, updated_at=NOW() WHERE id=?",
                'iissssii', [$data['role_id'], $data['branch_id'], $data['full_name'], $data['email'], $data['phone'], $data['status'], $uid, $id]);
        }
        
        if (empty($errors)) {
            log_activity($conn, $uid, 'update', 'users', "Updated user: " . $user['username'], $id);
            set_flash('success', 'User updated successfully.');
            redirect('modules/users/index.php');
        }
    }
} else {
    $data = $user;
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-pencil me-2" style="color:var(--gold);"></i>Edit User</h1>
        <p class="page-subtitle"><?= e($user['username']) ?></p>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach($errors as $e): ?><li><?=e($e)?></li><?php endforeach;?></ul></div>
<?php endif; ?>

<form method="POST" id="userForm">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span class="card-title">Personal Information</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required value="<?= e($data['full_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" disabled value="<?= e($user['username']) ?>">
                            <div class="form-text">Username cannot be changed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required value="<?= e($data['email']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?= e($data['phone']) ?>">
                        </div>
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2">Change Password</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><span class="card-title">Role & Status</span></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select" required>
                            <?php foreach ($roles as $r): ?>
                            <option value="<?= (int)$r['id'] ?>" <?= $data['role_id'] == $r['id'] ? 'selected':'' ?>><?= e($r['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Association</label>
                        <select name="branch_id" class="form-select">
                            <option value="">— No Specific Branch (HQ) —</option>
                            <?php foreach ($branches as $b): ?>
                            <option value="<?= (int)$b['id'] ?>" <?= ($data['branch_id'] ?? '') == $b['id'] ? 'selected':'' ?>>
                                <?= e($b['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Assign to a branch for local management.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $data['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $data['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save Changes</button>
            </div>
        </div>
    </div>
</form>

<?php include INCLUDES_PATH . 'footer.php'; ?>
