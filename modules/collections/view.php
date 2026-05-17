<?php
/**
 * DanaHibah™ - View Collection
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();

$id = (int)($_GET['id'] ?? 0);
$collection = db_fetch_one($conn, 
    "SELECT c.*, b.name AS branch_name, u.full_name AS collected_by_name 
     FROM collections c 
     LEFT JOIN branches b ON b.id = c.branch_id
     LEFT JOIN users u ON u.id = c.collected_by
     WHERE c.id=? AND c.deleted_at IS NULL", 'i', [$id]);

if (!$collection) {
    set_flash('error', 'Collection not found.');
    redirect('modules/collections/index.php');
}

$page_title  = 'View Collection';
$active_menu = 'collections';
$breadcrumbs = [['label'=>'Collections','url'=>APP_URL.'/modules/collections/index.php'],['label'=>'View']];

include INCLUDES_PATH . 'header.php';
?>

<div class="page-header d-flex align-items-center gap-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h1 class="page-title"><i class="bi bi-eye me-2" style="color:var(--gold);"></i>View Collection</h1>
        <p class="page-subtitle">Transaction Reference: <?= e($collection['txn_ref']) ?></p>
    </div>
    <div class="ms-auto">
        <button onclick="window.print()" class="btn btn-outline-dark"><i class="bi bi-printer me-2"></i>Print Receipt</button>
        <?php if (!is_committee() || $collection['status'] === 'collected'): ?>
        <a href="edit.php?id=<?=$id?>" class="btn btn-primary ms-2"><i class="bi bi-pencil me-2"></i>Edit</a>
        <?php endif; ?>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card" id="receipt-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 style="color:var(--primary); font-weight:700;">DanaHibah™</h3>
                    <p class="text-muted mb-0">Official Donation Receipt</p>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted mb-1">Receipt No:</h6>
                        <div><strong><?= e($collection['receipt_no']) ?></strong></div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <h6 class="text-muted mb-1">Date & Time:</h6>
                        <div><?= format_datetime($collection['collected_at']) ?></div>
                    </div>
                </div>
                
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr><th style="width:150px; background:var(--bg);">Branch</th><td><?= e($collection['branch_name'] ?? 'Unknown') ?></td></tr>
                        <tr><th style="background:var(--bg);">Channel</th><td class="text-capitalize"><?= e($collection['channel']) ?></td></tr>
                        <tr><th style="background:var(--bg);">Category</th><td class="text-capitalize"><?= e($collection['category']) ?></td></tr>
                        <tr><th style="background:var(--bg);">Status</th><td><?= status_badge($collection['status']) ?></td></tr>
                        <?php if($collection['donor_name']): ?>
                        <tr><th style="background:var(--bg);">Donor Name</th><td><?= e($collection['donor_name']) ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="p-3 text-center rounded mb-4" style="background:var(--gold); color:var(--primary);">
                    <h5 class="mb-0 fw-700">Total Amount: <?= format_money($collection['amount']) ?></h5>
                </div>
                
                <?php if($collection['notes']): ?>
                <div class="mb-4">
                    <h6 class="text-muted">Notes:</h6>
                    <p class="mb-0"><?= nl2br(e($collection['notes'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if($collection['status'] === 'banked'): ?>
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-700 text-primary mb-3"><i class="bi bi-bank me-2"></i>Banking Details</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Bank Ref No:</small>
                                <strong><?= e($collection['bank_ref_no'] ?: '—') ?></strong>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Deposit Receipt:</small>
                                <?php if ($collection['bank_receipt_file']): ?>
                                    <a href="<?= APP_URL ?>/uploads/receipts/<?= e($collection['bank_receipt_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="bi bi-file-earmark-text me-1"></i>View Receipt
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No receipt uploaded</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="text-center text-muted" style="font-size:0.8rem;">
                    Collected by: <?= e($collection['collected_by_name'] ?? 'System') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #receipt-card, #receipt-card * { visibility: visible; }
    #receipt-card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
}
</style>

<?php include INCLUDES_PATH . 'footer.php'; ?>
