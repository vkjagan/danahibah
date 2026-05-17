<?php
/**
 * DanaHibah™ - Help Module
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$page_title  = 'Help & Documentation';
$active_menu = 'help';
$breadcrumbs = [['label' => 'Help']];

$modules_help = [
    ['icon'=>'bi-speedometer2','title'=>'Dashboard','desc'=>'View real-time donation statistics, charts, and recent transactions across all branches.','steps'=>['Log in to access the dashboard.','Review today\'s collection stats on the stat cards.','Monitor the 7-day trend chart.','Click any stat card to drill into the related module.']],
    ['icon'=>'bi-cash-stack','title'=>'Collections','desc'=>'Record and manage all donation transactions from cash boxes and QR payment terminals.','steps'=>['Navigate to Collections from the sidebar.','Click "Add Collection" to record a new transaction.','Fill in branch, channel, amount and category.','Submit to generate a transaction reference number.','Use the filter bar to search by date, branch, channel or status.']],
    ['icon'=>'bi-check2-circle','title'=>'Approvals','desc'=>'Manage the donation collection approval workflow: Collected → Verified → Approved → Banked.','steps'=>['Go to Collections and identify records with "Collected" status.','Click the record and select "Verify" to move to the next step.','Committee head can "Approve" verified records.','Mark as "Banked" once deposited at the bank.']],
    ['icon'=>'bi-building','title'=>'Branches','desc'=>'Register and manage all mosque and surau branch locations.','steps'=>['Go to Branches in the sidebar.','Click "Add Branch" and fill in the branch details.','Assign a PIC (Person in Charge) with contact info.','Monitor per-branch totals on the branch cards.']],
    ['icon'=>'bi-hdd-rack','title'=>'Devices','desc'=>'Register and monitor DanaHibah™ hardware devices linked to branches.','steps'=>['Go to Devices from the sidebar.','Click "Register Device" and enter the serial number.','Link the device to a branch.','Monitor device status (Online/Offline/Tampered).']],
    ['icon'=>'bi-people','title'=>'User Management','desc'=>'Manage system users, roles and access permissions.','steps'=>['Go to Users (Admin only).','Click "Add User" to create a new account.','Assign a role (Admin, Committee, Viewer).','Activate or deactivate users as required.']],
    ['icon'=>'bi-file-earmark-bar-graph','title'=>'Reports','desc'=>'Generate detailed collection reports with flexible filters and export options.','steps'=>['Navigate to Reports.','Set date range, branch, channel and status filters.','Click Filter to load the report.','Use Excel / CSV / PDF buttons to export.']],
    ['icon'=>'bi-shield-check','title'=>'Audit Trail','desc'=>'View a complete log of all system actions, user activities and configuration changes.','steps'=>['Navigate to Audit Trail (Admin only).','Use filters to search by date, module, action or user.','Every create, update, delete and login is recorded.','Export the log for compliance or authority reports.']],
    ['icon'=>'bi-gear','title'=>'Settings','desc'=>'Configure application preferences, email, SEO and security settings.','steps'=>['Go to Settings (Admin only).','Use tabs to navigate between General, Email, SEO and Security.','Update values and click "Save All Settings".']],
];

include INCLUDES_PATH . 'header.php';
?>
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-question-circle me-2" style="color:var(--gold);"></i>Help & Documentation</h1>
    <p class="page-subtitle">User guides and step-by-step instructions for all modules</p>
</div>

<!-- Quick Links -->
<div class="row g-3 mb-4">
    <?php foreach($modules_help as $m): ?>
    <div class="col-6 col-md-4 col-lg-3">
        <a href="#help-<?=slugify($m['title'])?>" class="card text-decoration-none h-100" style="transition:var(--transition);">
            <div class="card-body text-center py-4">
                <i class="bi <?=e($m['icon'])?>" style="font-size:1.8rem;color:var(--primary);"></i>
                <div class="mt-2 fw-600" style="font-size:.875rem;"><?=e($m['title'])?></div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Module Help Sections -->
<?php foreach($modules_help as $m): ?>
<div class="card mb-3" id="help-<?=slugify($m['title'])?>">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi <?=e($m['icon'])?>" style="color:var(--gold);font-size:1.1rem;"></i>
        <span class="card-title"><?=e($m['title'])?></span>
    </div>
    <div class="card-body">
        <p style="font-size:.875rem;color:var(--text-muted);"><?=e($m['desc'])?></p>
        <h6 class="fw-600 mb-2" style="font-size:.82rem;color:var(--primary);">HOW TO USE</h6>
        <ol style="font-size:.875rem;padding-left:20px;">
            <?php foreach($m['steps'] as $step): ?>
            <li class="mb-1"><?=e($step)?></li>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
<?php endforeach; ?>

<!-- FAQ -->
<div class="card mt-4">
    <div class="card-header"><i class="bi bi-chat-right-question me-2"></i><span class="card-title">Frequently Asked Questions</span></div>
    <div class="card-body">
        <div class="accordion" id="faqAccordion">

            <?php $faqs = [
                ['q'=>'What happens if I forget my password?','a'=>'Contact your system administrator to reset your password. If you are the admin, access the database directly and update the password hash.'],
                ['q'=>'Can I have multiple branches?','a'=>'Yes. DanaHibah™ supports unlimited branches. Add each mosque or surau as a separate branch and assign devices accordingly.'],
                ['q'=>'How does the approval workflow work?','a'=>'Collections go through 4 stages: Collected → Verified → Approved → Banked. Each stage requires action by an authorised committee member.'],
                ['q'=>'Who can access the Audit Trail?','a'=>'Only administrators can view the full audit trail. All user actions including logins, data changes and configuration updates are logged automatically.'],
                ['q'=>'How do I export reports?','a'=>'Go to Reports, apply your filters, then use the Excel, CSV, PDF or Print buttons above the data table to export.'],
                ['q'=>'How often does the dashboard update?','a'=>'The dashboard shows live data from the database. Refresh the page to see the latest figures.'],
            ]; ?>

            <?php foreach($faqs as $i=>$faq): ?>
            <div class="accordion-item border mb-2 rounded" style="border-radius:8px !important;">
                <h2 class="accordion-header">
                    <button class="accordion-button <?=$i>0?'collapsed':''?> rounded" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq<?=$i?>"
                            style="font-size:.875rem;font-weight:600;">
                        <?=e($faq['q'])?>
                    </button>
                </h2>
                <div id="faq<?=$i?>" class="accordion-collapse collapse <?=$i===0?'show':''?>" data-bs-parent="#faqAccordion">
                    <div class="accordion-body" style="font-size:.875rem;color:var(--text-muted);">
                        <?=e($faq['a'])?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Support -->
<div class="card mt-3" style="background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;border:none;">
    <div class="card-body d-flex align-items-center gap-4 flex-wrap py-4">
        <div>
            <h5 class="fw-700 mb-1" style="color:#fff;">Need more help?</h5>
            <p class="mb-0" style="font-size:.875rem;opacity:.85;">Contact Arisio Sdn Bhd support team</p>
        </div>
        <div class="ms-auto d-flex gap-3 flex-wrap">
            <a href="mailto:support@danahibah.com" class="btn btn-gold">
                <i class="bi bi-envelope me-2"></i>Email Support
            </a>
            <a href="tel:+601234567890" class="btn btn-outline-light">
                <i class="bi bi-telephone me-2"></i>+60 12-345 6789
            </a>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
