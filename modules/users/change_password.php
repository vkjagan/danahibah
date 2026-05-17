<?php
/**
 * DanaHibah™ - Change Password
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Change Password';
$active_menu = '';
$breadcrumbs = [['label' => 'Change Password']];

$uid = (int)$_SESSION['user_id'];
$errors = [];

$user = db_fetch_one($conn, "SELECT password FROM users WHERE id=? AND deleted_at IS NULL", 'i', [$uid]);
if (!$user) {
    set_flash('danger', 'User not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!password_verify($current_password, $user['password'])) {
        $errors[] = "Current password is incorrect.";
    }
    if (strlen($new_password) < 8) {
        $errors[] = "New password must be at least 8 characters long.";
    }
    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    }

    if (empty($errors)) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        if (db_execute($conn, "UPDATE users SET password=?, updated_at=NOW() WHERE id=?", 'si', [$hash, $uid]) !== false) {
            set_flash('success', 'Password updated successfully. Please log in again.');
            logout_user();
            redirect('login.php');
        } else {
            $errors[] = "Failed to update password. Please try again.";
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-shield-lock me-2" style="color:var(--gold);"></i>Change Password</h1>
</div>

<div class="row">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Secure Your Account</h5>
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
                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required minlength="8">
                        <div class="form-text">Must be at least 8 characters long.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-gold w-100"><i class="bi bi-check2-circle me-2"></i>Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
