<?php
/**
 * DanaHibah™ - Settings Module
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth(); require_admin();

$page_title  = 'System Settings';
$active_menu = 'settings';
$breadcrumbs = [['label' => 'Settings']];

// Load all settings into key-value array
$settings_raw = db_fetch_all($conn,"SELECT * FROM settings ORDER BY `group`,`key`",'', []);
$settings = [];
foreach ($settings_raw as $s) { $settings[$s['key']] = $s['value']; }

function sv($settings, $key, $default='') {
    return htmlspecialchars($settings[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

// Save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $uid = (int)$_SESSION['user_id'];
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token') continue;
        $key   = clean($key);
        $value = is_array($value) ? implode(',', $value) : clean($value);
        db_execute($conn,
            "UPDATE settings SET value=?, updated_by=?, updated_at=NOW() WHERE `key`=?",
            'sis', [$value, $uid, $key]);
    }
    log_activity($conn,$uid,'update','settings','Updated system settings');
    set_flash('success','Settings saved successfully.');
    redirect('modules/settings/index.php');
}

include INCLUDES_PATH . 'header.php';
?>
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-gear me-2" style="color:var(--gold);"></i>System Settings</h1>
        <p class="page-subtitle">Configure application preferences and security options</p>
    </div>
    <button form="settingsForm" type="submit" class="btn btn-gold">
        <i class="bi bi-save me-2"></i>Save All Settings
    </button>
</div>

<form method="POST" id="settingsForm">
    <?= csrf_field() ?>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs custom-tabs mb-0" id="settingsTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-general">
            <i class="bi bi-sliders me-1"></i>General</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-email">
            <i class="bi bi-envelope me-1"></i>Email</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-seo">
            <i class="bi bi-globe me-1"></i>SEO</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-security">
            <i class="bi bi-shield me-1"></i>Security</a></li>
    </ul>

    <div class="tab-content">

        <!-- General Tab -->
        <div class="tab-pane fade show active" id="tab-general">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i><span class="card-title">General Settings</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Application Name</label>
                            <input type="text" name="app_name" class="form-control" value="<?=sv($settings,'app_name','DanaHibah™')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="app_tagline" class="form-control" value="<?=sv($settings,'app_tagline')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="<?=sv($settings,'contact_email')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="<?=sv($settings,'contact_phone')?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Records Per Page</label>
                            <select name="per_page" class="form-select">
                                <?php foreach([10,15,25,50,100] as $v): ?>
                                <option value="<?=$v?>" <?=($settings['per_page']??'25')==$v?'selected':''?>><?=$v?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Tab -->
        <div class="tab-pane fade" id="tab-email">
            <div class="card">
                <div class="card-header"><i class="bi bi-envelope me-2"></i><span class="card-title">Email / SMTP Settings</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" name="smtp_host" class="form-control" value="<?=sv($settings,'smtp_host')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" name="smtp_port" class="form-control" value="<?=sv($settings,'smtp_port','587')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Username</label>
                            <input type="text" name="smtp_user" class="form-control" value="<?=sv($settings,'smtp_user')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Password</label>
                            <input type="password" name="smtp_pass" class="form-control" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Name</label>
                            <input type="text" name="smtp_from_name" class="form-control" value="<?=sv($settings,'smtp_from_name')?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Email</label>
                            <input type="email" name="smtp_from_email" class="form-control" value="<?=sv($settings,'smtp_from_email')?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Tab -->
        <div class="tab-pane fade" id="tab-seo">
            <div class="card">
                <div class="card-header"><i class="bi bi-globe me-2"></i><span class="card-title">SEO Settings</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="<?=sv($settings,'meta_title')?>">
                            <div class="form-text">Recommended: 50–60 characters</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_desc" class="form-control" rows="3"><?=sv($settings,'meta_desc')?></textarea>
                            <div class="form-text">Recommended: 150–160 characters</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tab -->
        <div class="tab-pane fade" id="tab-security">
            <div class="card">
                <div class="card-header"><i class="bi bi-shield me-2"></i><span class="card-title">Security Settings</span></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Session Timeout (seconds)</label>
                            <input type="number" name="session_timeout" class="form-control"
                                   value="<?=sv($settings,'session_timeout','1800')?>" min="300" max="86400">
                            <div class="form-text">1800 = 30 minutes</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Login Attempts</label>
                            <input type="number" name="max_attempts" class="form-control"
                                   value="<?=sv($settings,'max_attempts','5')?>" min="3" max="20">
                            <div class="form-text">Lockout after this many failed logins</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
<?php include INCLUDES_PATH . 'footer.php'; ?>
