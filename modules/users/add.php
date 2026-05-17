<?php
/**
 * DanaHibah™ - Add User
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

$page_title  = 'Add User';
$active_menu = 'users';
$breadcrumbs = [['label'=>'Users','url'=>APP_URL.'/modules/users/index.php'],['label'=>'Add']];
$roles  = db_fetch_all($conn, "SELECT * FROM roles ORDER BY id", '', []);
$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL ORDER BY name", '', []);
$errors = []; $data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'full_name' => clean($_POST['full_name'] ?? ''),
        'username'  => clean($_POST['username']  ?? ''),
        'email'     => clean($_POST['email']     ?? ''),
        'phone'     => clean($_POST['phone']     ?? ''),
        'role_id'   => (int)($_POST['role_id']  ?? 2),
        'branch_id' => !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null,
        'password'  => $_POST['password']        ?? '',
        'confirm'   => $_POST['confirm_password']?? '',
        'status'    => clean($_POST['status']    ?? 'active'),
    ];

    if (!$data['full_name'])  $errors[] = 'Full name is required.';
    if (!$data['username'])   $errors[] = 'Username is required.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (strlen($data['password']) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($data['password'] !== $data['confirm']) $errors[] = 'Passwords do not match.';

    // Unique checks
    if (!$errors) {
        $ex = db_fetch_one($conn, "SELECT id FROM users WHERE (username=? OR email=?) AND deleted_at IS NULL",
                           'ss', [$data['username'], $data['email']]);
        if ($ex) $errors[] = 'Username or email already exists.';
    }

    if (empty($errors)) {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost'=>12]);
        $uid  = (int)$_SESSION['user_id'];
        $id   = db_insert($conn,
            "INSERT INTO users (role_id, branch_id, full_name, username, email, phone, password, status, created_by, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            'iisssssis', [$data['role_id'], $data['branch_id'], $data['full_name'], $data['username'],
                           $data['email'], $data['phone'], $hash, $data['status'], $uid]);
        if ($id) {
            log_activity($conn, $uid, 'create', 'users', "Created user: ".$data['username'], $id);
            set_flash('success', 'User ' . $data['full_name'] . ' created successfully.');
            redirect('modules/users/index.php');
        } else {
            $errors[] = 'Failed to create user. Please try again.';
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>
<div class="page-header d-flex align-items-center gap-3 flex-wrap">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-person-plus me-2" style="color:var(--gold);"></i>Add User</h1>
        <p class="page-subtitle">Create a new system user account</p>
    </div>
    <div class="ms-auto">
        <button form="userForm" type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save User</button>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><i class="bi bi-x-circle-fill me-2"></i>
    <ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="POST" id="userForm" novalidate>
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-person me-2"></i><span class="card-title">Personal Information</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required
                                   placeholder="e.g. Ahmad bin Abdullah"
                                   value="<?= e($data['full_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required
                                   placeholder="Unique username"
                                   value="<?= e($data['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required
                                   placeholder="user@example.com"
                                   value="<?= e($data['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control"
                                   placeholder="+60 1X-XXXXXXX"
                                   value="<?= e($data['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required
                                   placeholder="Min. 8 characters" autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" required
                                   placeholder="Repeat password" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-shield me-2"></i><span class="card-title">Role & Status</span></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select" required>
                            <?php foreach ($roles as $r): ?>
                            <option value="<?= (int)$r['id'] ?>" <?= ($data['role_id'] ?? 2) == $r['id'] ? 'selected':'' ?>>
                                <?= e($r['label']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Controls what the user can access.</div>
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
                            <option value="active"   <?= ($data['status']??'active')==='active'  ?'selected':'' ?>>Active</option>
                            <option value="inactive" <?= ($data['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php include INCLUDES_PATH . 'footer.php'; ?>
