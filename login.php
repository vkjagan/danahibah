<?php
/**
 * DanaHibah™ - Login Page
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

start_session();

// Already logged in → redirect to dashboard
if (is_logged_in()) {
    redirect('index.php');
}

$error   = '';
$success = '';
$flash   = get_flash();

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please enter your username and password.';
    } else {
        [$ok, $msg, $user] = attempt_login($conn, $username, $password);
        if ($ok) {
            set_flash('success', 'Welcome back, ' . $user['full_name'] . '!');
            redirect('index.php');
        } else {
            $error = $msg;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= META_DESCRIPTION ?>">
    <meta name="robots" content="noindex, nofollow">
    <title>Login — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body>

<div class="login-page">

    <!-- Decorative blobs -->
    <div style="position:absolute;bottom:-80px;left:-80px;width:350px;height:350px;
                background:radial-gradient(circle,rgba(26,60,52,.4) 0%,transparent 70%);
                border-radius:50%;pointer-events:none;"></div>

    <div class="login-card fade-in-up">

        <!-- Logo -->
        <div class="login-logo">
            <div class="logo-icon"><i class="bi bi-moon-stars-fill"></i></div>
            <h1>DanaHibah™</h1>
            <p>Secure · Transparent · Amanah</p>
        </div>

        <h5 class="text-center mb-4" style="font-weight:600;color:var(--text);font-size:.95rem;">
            Sign in to your account
        </h5>

        <!-- Flash -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i>
                <span><?= e($flash['message']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Error -->
        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-x-circle-fill"></i>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" id="loginForm" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-person" style="color:var(--text-muted);"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0"
                           id="username" name="username"
                           value="<?= e($_POST['username'] ?? '') ?>"
                           placeholder="Enter username or email"
                           required autocomplete="username">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock" style="color:var(--text-muted);"></i>
                    </span>
                    <input type="password" class="form-control border-start-0 ps-0"
                           id="password" name="password"
                           placeholder="Enter password"
                           required autocomplete="current-password">
                    <button type="button" class="input-group-text bg-white border-start-0"
                            id="togglePwd" title="Show/Hide password">
                        <i class="bi bi-eye" id="eyeIcon" style="color:var(--text-muted);cursor:pointer;"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-600" id="loginBtn"
                    style="border-radius:10px;font-size:.95rem;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>

        </form>

        <hr class="divider">

        <p class="text-center mb-0" style="font-size:.78rem;color:var(--text-muted);">
            <i class="bi bi-shield-lock me-1"></i>
            Protected system. Authorised access only.
        </p>

        <div class="text-center mt-3" style="font-size:.75rem;color:var(--text-light);">
            &copy; <?= date('Y') ?> <?= APP_NAME ?> &mdash; Arisio Sdn Bhd
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle password visibility
document.getElementById('togglePwd').addEventListener('click', function() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});

// Loading state on submit
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
    btn.disabled = true;
});
</script>
</body>
</html>
