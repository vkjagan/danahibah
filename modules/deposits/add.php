<?php
/**
 * DanaHibah™ - Record Bank Deposit
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$page_title  = 'Record Bank Deposit';
$active_menu = 'deposits';
$breadcrumbs = [
    ['label' => 'Bank Deposits', 'url' => 'index.php'],
    ['label' => 'Record Deposit']
];

$filter = get_branch_filter('', 'id');
$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL AND $filter ORDER BY name", '', []);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $branch_id    = (int)($_POST['branch_id'] ?? 0);
    $amount       = (float)($_POST['amount'] ?? 0);
    $deposit_date = clean($_POST['deposit_date'] ?? date('Y-m-d'));
    $ref_no       = clean($_POST['ref_no'] ?? '');
    $remarks      = clean($_POST['remarks'] ?? '');
    
    if (!$branch_id) $errors[] = "Branch is required.";
    if ($amount <= 0) $errors[] = "Amount must be greater than RM 0.";
    if (!$deposit_date) $errors[] = "Deposit date is required.";

    if ($branch_id && $amount > 0) {
        $balances = get_ledger_balances($conn, $branch_id);
        if ($amount > $balances['cash']) {
            $errors[] = "Insufficient Cash in Hand (Only RM " . number_format($balances['cash'], 2) . " available). Cannot deposit RM " . number_format($amount, 2) . ".";
        }
    }

    // File upload
    $receipt_file = null;
    if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/receipts/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        
        $ext = strtolower(pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','pdf'];
        
        if (in_array($ext, $allowed)) {
            $new_file = 'dep_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $upload_dir . $new_file)) {
                $receipt_file = $new_file;
            } else {
                $errors[] = "Failed to save the uploaded receipt.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, PNG, and PDF are allowed.";
        }
    }

    if (empty($errors)) {
        $uid = (int)$_SESSION['user_id'];
        $sql = "INSERT INTO bank_deposits (branch_id, amount, ref_no, receipt_file, deposit_date, remarks, created_by, updated_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $insert_id = db_insert($conn, $sql, 'idssssii', [
            $branch_id, $amount, $ref_no, $receipt_file, $deposit_date, $remarks, $uid, $uid
        ]);
        
        if ($insert_id) {
            log_activity($conn, $uid, 'create', 'bank_deposits', "Recorded bank deposit of RM " . format_money($amount), $insert_id);
            set_flash('success', 'Bank deposit recorded successfully.');
            redirect('modules/deposits/index.php');
        } else {
            $errors[] = "A database error occurred while saving the deposit.";
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Record Bank Deposit</h1>
        <p class="page-subtitle">Transfer funds from Cash in Hand to Bank Balance</p>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <ul class="mb-0 ps-3">
        <?php foreach($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                <strong>Ledger Note:</strong> Recording a deposit will automatically deduct the amount from your <strong>Cash in Hand</strong> and add it to your <strong>Total Bank Balance</strong>.
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="card">
            <?= csrf_field() ?>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required <?= is_committee() || is_branch_admin() ? 'disabled' : '' ?>>
                        <option value="">-- Select Branch --</option>
                        <?php foreach($branches as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= (is_committee() || is_branch_admin()) && $_SESSION['user_branch_id'] == $b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(is_committee() || is_branch_admin()): ?>
                    <input type="hidden" name="branch_id" value="<?= $_SESSION['user_branch_id'] ?>">
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
                    <input type="date" name="deposit_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Amount Deposited (RM) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required placeholder="0.00">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Bank Reference / Txn No.</label>
                    <input type="text" name="ref_no" class="form-control" placeholder="e.g. TRF-99812">
                </div>
                
                <div class="col-12">
                    <label class="form-label">Remarks / Note</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Optional details about this deposit..."></textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Upload Deposit Slip / Receipt</label>
                    <input type="file" name="receipt_file" class="form-control" accept="image/*,.pdf">
                    <small class="text-muted">Recommended for auditing purposes.</small>
                </div>
            </div>
            <div class="card-footer text-end bg-transparent py-3">
                <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-bank me-2"></i>Record Deposit</button>
            </div>
        </form>
    </div>
</div>

<?php include INCLUDES_PATH . 'footer.php'; ?>
