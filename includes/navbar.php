<?php
/**
 * DanaHibah™ - Top Navbar Include
 */
$user_name   = $_SESSION['user_name']   ?? 'User';
$user_avatar = $_SESSION['user_avatar'] ?? '';
$initials    = strtoupper(substr($user_name, 0, 1));
?>
<header class="topbar">

    <!-- Sidebar Toggle -->
    <button class="topbar-toggle" id="sidebarToggle" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="topbar-breadcrumb d-none d-md-block">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= APP_URL ?>/index.php"><i class="bi bi-house"></i></a>
            </li>
            <?php if (!empty($breadcrumbs)): ?>
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <?php if ($i === count($breadcrumbs) - 1): ?>
                        <li class="breadcrumb-item active"><?= e($crumb['label']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item">
                            <a href="<?= e($crumb['url'] ?? '#') ?>"><?= e($crumb['label']) ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="breadcrumb-item active"><?= e($page_title ?? 'Dashboard') ?></li>
            <?php endif; ?>
        </ol>
    </nav>

    <!-- Topbar Actions -->
    <div class="topbar-actions">

        <!-- Notifications -->
        <button class="topbar-btn position-relative" title="Notifications"
                data-bs-toggle="dropdown" id="notifBtn">
            <i class="bi bi-bell"></i>
            <span class="badge-dot"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width:300px;border-radius:12px;">
            <li class="px-3 py-2 border-bottom">
                <strong style="font-size:.85rem;">Notifications</strong>
            </li>
            <li>
                <a class="dropdown-item py-2" href="#">
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-cash-stack text-success mt-1"></i>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">New collection received</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">RM 50.00 — Masjid Al-Falah</div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="text-center py-2">
                <a href="#" style="font-size:.8rem;color:var(--gold-dark);">View all notifications</a>
            </li>
        </ul>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm d-flex align-items-center gap-2 border-0 bg-transparent px-2"
                    data-bs-toggle="dropdown" id="userMenuBtn">
                <?php if ($user_avatar && file_exists(UPLOAD_PATH . 'profiles/' . $user_avatar)): ?>
                    <img src="<?= APP_URL ?>/uploads/profiles/<?= e($user_avatar) ?>"
                         alt="<?= e($user_name) ?>" class="user-avatar">
                <?php else: ?>
                    <div class="user-avatar-placeholder"><?= $initials ?></div>
                <?php endif; ?>
                <span class="d-none d-lg-inline" style="font-size:.85rem;font-weight:600;">
                    <?= e($user_name) ?>
                </span>
                <i class="bi bi-chevron-down d-none d-lg-inline" style="font-size:.7rem;color:var(--text-muted);"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="border-radius:12px;min-width:200px;">
                <li class="px-3 py-2 border-bottom">
                    <div style="font-size:.85rem;font-weight:600;"><?= e($user_name) ?></div>
                    <div style="font-size:.75rem;color:var(--text-muted);"><?= e($_SESSION['user_email'] ?? '') ?></div>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= APP_URL ?>/modules/users/profile.php">
                        <i class="bi bi-person me-2"></i>My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= APP_URL ?>/modules/users/change_password.php">
                        <i class="bi bi-shield-lock me-2"></i>Change Password
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= APP_URL ?>/modules/settings/index.php">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="<?= APP_URL ?>/logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>
