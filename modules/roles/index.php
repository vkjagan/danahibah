<?php
/**
 * DanaHibah™ - Access Permissions Reference
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

// ONLY Super Admin
if (!is_super_admin()) {
    set_flash('error', 'Access denied.');
    redirect('index.php');
}

$page_title  = 'Access Permissions';
$active_menu = 'roles';
$breadcrumbs = [['label' => 'Access Permissions']];

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-shield-lock me-2" style="color:var(--primary);"></i>Access & Permissions Matrix</h1>
    <p class="page-subtitle">Reference guide for role-based access control across DanaHibah™ modules</p>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Note:</strong> DanaHibah™ uses a strict, hard-coded hierarchical role system to ensure absolute data integrity. Roles cannot be customized to prevent accidental privilege escalation.
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%">Module / Feature</th>
                        <th class="text-center text-primary"><i class="bi bi-shield-check me-1"></i>Super Admin</th>
                        <th class="text-center text-info"><i class="bi bi-eye me-1"></i>Management</th>
                        <th class="text-center text-success"><i class="bi bi-building me-1"></i>Branch Admin</th>
                        <th class="text-center text-secondary"><i class="bi bi-person-badge me-1"></i>Committee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">Data Scope</td>
                        <td class="text-center"><span class="badge bg-dark">All Branches</span></td>
                        <td class="text-center"><span class="badge bg-dark">All Branches</span></td>
                        <td class="text-center"><span class="badge bg-primary">Own Branch Only</span></td>
                        <td class="text-center"><span class="badge bg-primary">Own Branch Only</span></td>
                    </tr>
                    
                    <tr>
                        <td class="fw-bold bg-light" colspan="5">Collections & Finance</td>
                    </tr>
                    <tr>
                        <td>Record Collections</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>Verify & Approve (Workflow)</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>Record Expenses</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                    </tr>
                    
                    <tr>
                        <td class="fw-bold bg-light" colspan="5">Reporting</td>
                    </tr>
                    <tr>
                        <td>Standard Reports</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>Management Insights</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>

                    <tr>
                        <td class="fw-bold bg-light" colspan="5">System Administration</td>
                    </tr>
                    <tr>
                        <td>User Management</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i> (Branch users only)</td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>Branch Management</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>Audit Trails</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i> (Branch logs only)</td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>
                    <tr>
                        <td>System Settings</td>
                        <td class="text-center text-success"><i class="bi bi-check-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                        <td class="text-center text-danger"><i class="bi bi-x-circle-fill"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
