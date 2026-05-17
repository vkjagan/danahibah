<?php
/**
 * DanaHibah™ - Add Expense
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$page_title  = 'Record Expense';
$active_menu = 'expenses';
$breadcrumbs = [['label' => 'Expenses', 'url' => 'index.php'], ['label' => 'Record Expense']];

$branches = db_fetch_all($conn, "SELECT id, name FROM branches WHERE deleted_at IS NULL AND " . get_branch_filter('', 'id') . " ORDER BY name", '', []);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $branch_id      = (int)($_POST['branch_id'] ?? 0);
    $category       = clean($_POST['category'] ?? '');
    $payment_source = clean($_POST['payment_source'] ?? 'cash');
    $amount         = (float)($_POST['amount'] ?? 0);
    $title          = clean($_POST['title'] ?? '');
    $description    = clean($_POST['description'] ?? '');
    $expense_date   = clean($_POST['expense_date'] ?? date('Y-m-d'));
    
    if (!$branch_id) $errors[] = "Branch is required.";
    if (!$title)     $errors[] = "Title is required.";
    if ($amount <= 0) $errors[] = "Amount must be greater than 0.";
    if (!$expense_date) $errors[] = "Expense date is required.";
    if (!in_array($payment_source, ['cash', 'bank'])) $payment_source = 'cash';

    if (empty($errors)) {
        $receipt_file = null;
        if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../uploads/receipts/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION);
            $receipt_file = 'exp_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            move_uploaded_file($_FILES['receipt_file']['tmp_name'], $upload_dir . $receipt_file);
        }

        $uid = (int)$_SESSION['user_id'];
        
        $sql = "INSERT INTO expenses (branch_id, category, payment_source, amount, title, description, receipt_file, expense_date, status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
        
        $id = db_execute($conn, $sql, 'issdssssi', [
            $branch_id, $category, $payment_source, $amount, $title, $description, $receipt_file, $expense_date, $uid
        ]);
        
        log_activity($conn, $uid, 'create', 'expenses', "Recorded expense: $title", $id);
        
        set_flash('success', 'Expense recorded successfully.');
        redirect('modules/expenses/index.php');
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-plus-circle me-2" style="color:var(--gold);"></i>Record Expense</h1>
        <p class="page-subtitle">Add a new daily expenditure for the branch</p>
    </div>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <ul class="mb-0 ps-3">
        <?php foreach($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" required <?= is_committee() || is_branch_admin() ? 'disabled' : '' ?>>
                            <?php foreach($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(is_committee() || is_branch_admin()): ?>
                        <input type="hidden" name="branch_id" value="<?= $branches[0]['id'] ?? '' ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Title / Item <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Electric Bill, Maintenance" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="utilities">Utilities (Water, Electric)</option>
                            <option value="maintenance">Maintenance & Repairs</option>
                            <option value="staff">Staff / Allowances</option>
                            <option value="events">Events & Programs</option>
                            <option value="supplies">Office Supplies</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Payment Source <span class="text-danger">*</span></label>
                        <select name="payment_source" class="form-select">
                            <option value="cash">Paid via Cash in Hand</option>
                            <option value="bank">Paid via Bank Transfer / Cheque</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description / Remarks</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional details..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Upload Receipt/Invoice (Optional)</label>
                        <input type="file" name="receipt_file" class="form-control" accept="image/*,.pdf">
                    </div>
                </div>
            </div>
            <div class="mt-4 text-end">
                <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-2"></i>Save Expense</button>
            </div>
        </div>
    </div>
</form>

<?php include INCLUDES_PATH . 'footer.php'; ?>
