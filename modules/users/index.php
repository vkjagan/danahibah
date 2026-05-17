<?php
/**
 * DanaHibah™ - Users Module - List
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();
require_admin();

$page_title  = 'User Management';
$active_menu = 'users';
$breadcrumbs = [['label' => 'Users']];

$users = db_fetch_all($conn,
    "SELECT u.*, r.label AS role_label, b.name AS branch_name
     FROM users u
     LEFT JOIN roles r ON r.id = u.role_id
     LEFT JOIN branches b ON b.id = u.branch_id
     WHERE u.deleted_at IS NULL AND " . get_branch_filter('u.') . "
     ORDER BY u.created_at DESC",
    '', []);

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-people me-2" style="color:var(--gold);"></i>User Management</h1>
        <p class="page-subtitle">Manage system users, roles, and access permissions</p>
    </div>
    <a href="add.php" class="btn btn-gold">
        <i class="bi bi-person-plus me-2"></i>Add User
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Branch</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Joined</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-placeholder" style="width:32px;height:32px;font-size:.75rem;">
                                    <?= strtoupper(substr($u['full_name'],0,1)) ?>
                                </div>
                                <strong><?= e($u['full_name']) ?></strong>
                            </div>
                        </td>
                        <td><code style="font-size:.82rem;"><?= e($u['username']) ?></code></td>
                        <td><?= e($u['email']) ?></td>
                        <td style="font-size:.82rem;"><?= e($u['branch_name'] ?? 'All / HQ') ?></td>
                        <td>
                            <span class="badge" style="background:var(--primary);font-size:.72rem;">
                                <?= e($u['role_label'] ?? '—') ?>
                            </span>
                        </td>
                        <td><?= status_badge($u['status']) ?></td>
                        <td style="font-size:.82rem;"><?= $u['last_login'] ? format_datetime($u['last_login']) : '<span class="text-muted">Never</span>' ?></td>
                        <td style="font-size:.82rem;"><?= format_date($u['created_at']) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="edit.php?id=<?= (int)$u['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="toggle_status.php?id=<?= (int)$u['id'] ?>&csrf=<?= csrf_token() ?>"
                                   class="btn btn-sm btn-outline-<?= $u['status']==='active'?'warning':'success' ?>"
                                   title="<?= $u['status']==='active'?'Deactivate':'Activate' ?>">
                                    <i class="bi bi-<?= $u['status']==='active'?'pause-circle':'play-circle' ?>"></i>
                                </a>

                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$extra_js = '<script>$(document).ready(function(){ $("#usersTable").DataTable({ order:[[7,"desc"]] }); });</script>';
include INCLUDES_PATH . 'footer.php';
?>
