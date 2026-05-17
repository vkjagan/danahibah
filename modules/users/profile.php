<?php
/**
 * DanaHibah™ - User Profile
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'My Profile';
$active_menu = '';
$breadcrumbs = [['label' => 'Profile']];

$uid = (int)$_SESSION['user_id'];
$errors = [];
$success = '';

$user = db_fetch_one($conn, "SELECT * FROM users WHERE id=? AND deleted_at IS NULL", 'i', [$uid]);
if (!$user) {
    set_flash('danger', 'User not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $full_name = clean($_POST['full_name'] ?? '');
    $email     = clean($_POST['email'] ?? '');
    $phone     = clean($_POST['phone'] ?? '');

    if (!$full_name) $errors[] = "Full Name is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid Email is required.";

    // Check email uniqueness
    $exists = db_fetch_one($conn, "SELECT id FROM users WHERE email=? AND id!=? AND deleted_at IS NULL", 'si', [$email, $uid]);
    if ($exists) $errors[] = "Email is already taken by another account.";

    if (empty($errors)) {
        if (db_execute($conn, "UPDATE users SET full_name=?, email=?, phone=?, updated_at=NOW() WHERE id=?", 'sssi', [$full_name, $email, $phone, $uid]) !== false) {
            $_SESSION['user_name']  = $full_name;
            $_SESSION['user_email'] = $email;
            set_flash('success', 'Profile updated successfully.');
            redirect('modules/users/profile.php');
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-circle me-2" style="color:var(--gold);"></i>My Profile</h1>
</div>

<div class="row">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Edit Profile Details</h5>
            </div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $e): ?>
                                <li><?= e($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= e($user['username']) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($user['phone']) ?>">
                    </div>
                    <button type="submit" class="btn btn-gold w-100"><i class="bi bi-save me-2"></i>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
