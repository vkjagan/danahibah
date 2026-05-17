<?php
/**
 * DanaHibah™ - Edit Expense
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

$id = (int)($_GET['id'] ?? 0);
$expense = db_fetch_one($conn, "SELECT * FROM expenses WHERE id=? AND deleted_at IS NULL", 'i', [$id]);

if (!$expense) {
    set_flash('error', 'Expense not found.');
    redirect('modules/expenses/index.php');
}

// Ensure user has access to this branch
if (is_committee() || is_branch_admin()) {
    if ($expense['branch_id'] != $_SESSION['user_branch_id']) {
        set_flash('error', 'Access denied.');
        redirect('modules/expenses/index.php');
    }
}

// Lock Check: NO ONE can edit if status is approved (Strict Accounting)
if ($expense['status'] === 'approved') {
    set_flash('error', 'This expense has been approved and is permanently locked for editing. If there is an error, please delete and recreate it.');
    redirect('modules/expenses/index.php');
}

$page_title  = 'Edit Expense';
$active_menu = 'expenses';
$breadcrumbs = [
    ['label' => 'Expenses', 'url' => 'index.php'],
    ['label' => 'Edit Expense']
];

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
    $expense_date   = clean($_POST['expense_date'] ?? '');
    
    if (!$branch_id) $errors[] = "Branch is required.";
    if (!$title)     $errors[] = "Title is required.";
    if ($amount <= 0) $errors[] = "Amount must be greater than 0.";
    if (!$expense_date) $errors[] = "Expense date is required.";
    if (!in_array($payment_source, ['cash', 'bank'])) $payment_source = 'cash';

    if (empty($errors)) {
        $receipt_file = $expense['receipt_file'];
        if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../uploads/receipts/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION);
            $new_file = 'exp_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $upload_dir . $new_file)) {
                $receipt_file = $new_file;
            }
        }

        $uid = (int)$_SESSION['user_id'];
        
        $sql = "UPDATE expenses SET branch_id=?, category=?, payment_source=?, amount=?, title=?, description=?, receipt_file=?, expense_date=?, updated_by=? WHERE id=?";
        db_execute($conn, $sql, 'issdssssii', [
            $branch_id, $category, $payment_source, $amount, $title, $description, $receipt_file, $expense_date, $uid, $id
        ]);
        
        log_activity($conn, $uid, 'update', 'expenses', "Updated expense: $title", $id);
        
        set_flash('success', 'Expense updated successfully.');
        redirect('modules/expenses/index.php');
    }
}

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Expense</h1>
        <p class="page-subtitle">Update daily expenditure information</p>
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
                            <option value="<?= $b['id'] ?>" <?= $expense['branch_id'] == $b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(is_committee() || is_branch_admin()): ?>
                        <input type="hidden" name="branch_id" value="<?= $expense['branch_id'] ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="<?= e($expense['expense_date']) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Title / Item <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= e($expense['title']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="utilities" <?= $expense['category'] === 'utilities' ? 'selected' : '' ?>>Utilities (Water, Electric)</option>
                            <option value="maintenance" <?= $expense['category'] === 'maintenance' ? 'selected' : '' ?>>Maintenance & Repairs</option>
                            <option value="staff" <?= $expense['category'] === 'staff' ? 'selected' : '' ?>>Staff / Allowances</option>
                            <option value="events" <?= $expense['category'] === 'events' ? 'selected' : '' ?>>Events & Programs</option>
                            <option value="supplies" <?= $expense['category'] === 'supplies' ? 'selected' : '' ?>>Office Supplies</option>
                            <option value="other" <?= $expense['category'] === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" value="<?= e($expense['amount']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Payment Source <span class="text-danger">*</span></label>
                        <select name="payment_source" class="form-select">
                            <option value="cash" <?= ($expense['payment_source'] ?? 'cash') === 'cash' ? 'selected' : '' ?>>Paid via Cash in Hand</option>
                            <option value="bank" <?= ($expense['payment_source'] ?? 'cash') === 'bank' ? 'selected' : '' ?>>Paid via Bank Transfer / Cheque</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description / Remarks</label>
                        <textarea name="description" class="form-control" rows="3"><?= e($expense['description']) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Upload New Receipt/Invoice (Optional)</label>
                        <?php if ($expense['receipt_file']): ?>
                            <div class="mb-2">
                                <a href="<?= APP_URL ?>/uploads/receipts/<?= e($expense['receipt_file']) ?>" target="_blank" class="badge bg-info text-decoration-none">
                                    <i class="bi bi-file-earmark-text"></i> View Current Receipt
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="receipt_file" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Uploading a new file will replace the current one.</small>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-end">
                <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update Expense</button>
            </div>
        </div>
    </div>
</form>

<?php include INCLUDES_PATH . 'footer.php'; ?>
