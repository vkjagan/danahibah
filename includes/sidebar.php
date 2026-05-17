<?php
/**
 * DanaHibah™ - Sidebar Navigation Include
 */
$active_menu = $active_menu ?? '';
?>
<aside class="sidebar" id="sidebar">

    <!-- Brand -->
    <a href="<?= APP_URL ?>/index.php" class="sidebar-brand">
        <div class="sidebar-brand-icon">D</div>
        <div class="sidebar-brand-text">
            <div class="brand-name">DanaHibah™</div>
            <div class="brand-tagline">Secure · Amanah</div>
        </div>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column list-unstyled">

            <!-- MAIN -->
            <li class="sidebar-section-title">Main</li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/index.php"
                   class="nav-link <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 nav-icon"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            <!-- COLLECTIONS -->
            <li class="sidebar-section-title">Collections</li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/collections/index.php"
                   class="nav-link <?= $active_menu === 'collections' ? 'active' : '' ?>">
                    <i class="bi bi-cash-stack nav-icon"></i>
                    <span class="nav-label">Collections</span>
                </a>
            </li>

            <?php if (is_super_admin() || is_branch_admin()): ?>
            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/collections/approvals.php"
                   class="nav-link <?= $active_menu === 'approvals' ? 'active' : '' ?>">
                    <i class="bi bi-check2-circle nav-icon"></i>
                    <span class="nav-label">Approvals</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/expenses/index.php"
                   class="nav-link <?= $active_menu === 'expenses' ? 'active' : '' ?>">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    <span class="nav-label">Expenses</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/deposits/index.php"
                   class="nav-link <?= $active_menu === 'deposits' ? 'active' : '' ?>">
                    <i class="bi bi-bank nav-icon"></i>
                    <span class="nav-label">Bank Deposits</span>
                </a>
            </li>

            <!-- MANAGEMENT -->
            <?php if (!is_committee()): ?>
            <li class="sidebar-section-title">Management</li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/branches/index.php"
                   class="nav-link <?= $active_menu === 'branches' ? 'active' : '' ?>">
                    <i class="bi bi-building nav-icon"></i>
                    <span class="nav-label">Branches</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/devices/index.php"
                   class="nav-link <?= $active_menu === 'devices' ? 'active' : '' ?>">
                    <i class="bi bi-hdd-rack nav-icon"></i>
                    <span class="nav-label">Devices</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/users/index.php"
                   class="nav-link <?= $active_menu === 'users' ? 'active' : '' ?>">
                    <i class="bi bi-people nav-icon"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (is_committee()): ?>
            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/devices/index.php"
                   class="nav-link <?= $active_menu === 'devices' ? 'active' : '' ?>">
                    <i class="bi bi-hdd-rack nav-icon"></i>
                    <span class="nav-label">Devices</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- REPORTS -->
            <li class="sidebar-section-title">Reports</li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/reports/index.php"
                   class="nav-link <?= $active_menu === 'reports' && basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-bar-graph nav-icon"></i>
                    <span class="nav-label">Reports</span>
                </a>
            </li>

            <?php if (is_super_admin() || is_management() || is_branch_admin()): ?>
            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/reports/management.php"
                   class="nav-link <?= $active_menu === 'reports' && basename($_SERVER['PHP_SELF']) === 'management.php' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow nav-icon"></i>
                    <span class="nav-label">Insights Dashboard</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (is_super_admin() || is_branch_admin()): ?>
            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/audit/index.php"
                   class="nav-link <?= $active_menu === 'audit' ? 'active' : '' ?>">
                    <i class="bi bi-shield-check nav-icon"></i>
                    <span class="nav-label">Audit Trail</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- SYSTEM -->
            <?php if (is_super_admin()): ?>
            <li class="sidebar-section-title">System</li>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/settings/index.php"
                   class="nav-link <?= $active_menu === 'settings' ? 'active' : '' ?>">
                    <i class="bi bi-gear nav-icon"></i>
                    <span class="nav-label">Settings</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (is_super_admin()): ?>
            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/roles/index.php"
                   class="nav-link <?= $active_menu === 'roles' ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock nav-icon"></i>
                    <span class="nav-label">Permissions Guide</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="<?= APP_URL ?>/modules/help/index.php"
                   class="nav-link <?= $active_menu === 'help' ? 'active' : '' ?>">
                    <i class="bi bi-question-circle nav-icon"></i>
                    <span class="nav-label">Help</span>
                </a>
            </li>

        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <span>v<?= APP_VERSION ?> &nbsp;·&nbsp; <?= APP_NAME ?></span>
    </div>

</aside>
