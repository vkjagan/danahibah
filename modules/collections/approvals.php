<?php
/**
 * DanaHibah™ - Collection Approvals
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_auth();
require_admin();

$page_title  = 'Approvals';
$active_menu = 'approvals';
$breadcrumbs = [['label'=>'Collections','url'=>APP_URL.'/modules/collections/index.php'],['label'=>'Approvals']];

// Handle approval action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $col_id  = (int)($_POST['collection_id'] ?? 0);
    $step    = clean($_POST['step'] ?? '');
    $remarks = clean($_POST['remarks'] ?? '');
    $uid     = (int)$_SESSION['user_id'];

    $valid_steps = ['verified','approved','banked','rejected'];
    if ($col_id && in_array($step, $valid_steps)) {
        $extra_sql = ""; $extra_types = ""; $extra_params = [];

        // Handle banking details
        if ($step === 'banked') {
            $bank_ref = clean($_POST['bank_ref'] ?? '');
            $receipt_file = '';

            if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/../../uploads/receipts/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $ext = pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION);
                $receipt_file = 'receipt_' . $col_id . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['receipt_file']['tmp_name'], $upload_dir . $receipt_file);
            }

            $extra_sql = ", bank_receipt_file=?, bank_ref_no=?";
            $extra_types = "ss";
            $extra_params = [$receipt_file, $bank_ref];
        }

        $sql = "UPDATE collections SET status=?, updated_by=?, updated_at=NOW() $extra_sql WHERE id=?";
        $types = "sii" . $extra_types;
        $params = array_merge([$step, $uid], $extra_params, [$col_id]);

        db_execute($conn, $sql, $types, $params);
        
        db_execute($conn,"INSERT INTO collection_approvals(collection_id,step,remarks,actioned_by,actioned_at) VALUES(?,?,?,?,NOW())",
                   "issi",[$col_id,$step,$remarks,$uid]);
        
        log_activity($conn,$uid,'update','collections',"Approval action: $step on collection #$col_id",$col_id);
        
        // Handle response for AJAX or Form
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            json_response(true,"Collection marked as ".ucfirst($step).".",['new_status'=>$step]);
        } else {
            set_flash('success', "Collection marked as ".ucfirst($step).".");
            redirect('modules/collections/approvals.php');
        }
    } else {
        json_response(false,'Invalid request.');
    }
}

// Fetch pending
$pending = db_fetch_all($conn,
    "SELECT c.*,b.name AS branch_name FROM collections c
     LEFT JOIN branches b ON b.id=c.branch_id
     WHERE c.status IN ('collected','verified','approved') AND c.deleted_at IS NULL AND " . get_branch_filter('c.') . "
     ORDER BY c.collected_at ASC",
    '', []);

include INCLUDES_PATH . 'header.php';
?>
<?= csrf_field() ?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-check2-circle me-2" style="color:var(--gold);"></i>Approvals</h1>
        <p class="page-subtitle">Review and approve collection workflow steps</p>
    </div>
    <span class="badge fs-6" style="background:var(--primary);padding:10px 18px;">
        <?=count($pending)?> Pending
    </span>
</div>

<!-- Workflow Info -->
<div class="alert alert-info d-flex gap-3 align-items-center mb-4">
    <i class="bi bi-info-circle-fill fs-5"></i>
    <div style="font-size:.875rem;">
        <strong>Approval Workflow:</strong>
        <span class="badge bg-info ms-1">Collected</span>
        <i class="bi bi-arrow-right mx-1"></i>
        <span class="badge bg-primary">Verified</span>
        <i class="bi bi-arrow-right mx-1"></i>
        <span class="badge bg-success">Approved</span>
        <i class="bi bi-arrow-right mx-1"></i>
        <span class="badge bg-success">Banked</span>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="approvalsTable">
                <thead>
                    <tr>
                        <th>Ref No.</th><th>Branch</th><th>Channel</th>
                        <th>Amount</th><th>Current Status</th><th>Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pending as $row): ?>
                    <tr id="row-<?=(int)$row['id']?>">
                        <td><code style="font-size:.78rem;"><?=e($row['txn_ref']??'—')?></code></td>
                        <td><?=e($row['branch_name']??'—')?></td>
                        <td class="text-capitalize"><?=e($row['channel'])?></td>
                        <td><strong><?=format_money($row['amount'])?></strong></td>
                        <td class="status-cell"><?=status_badge($row['status'])?></td>
                        <td style="font-size:.82rem;"><?=format_datetime($row['collected_at'])?></td>
                        <td>
                            <?php if($row['status']==='collected'): ?>
                            <button class="btn btn-sm btn-primary approval-btn"
                                data-id="<?=(int)$row['id']?>" data-step="verified"
                                title="Mark Verified"><i class="bi bi-check-circle me-1"></i>Verify</button>
                            <?php elseif($row['status']==='verified'): ?>
                            <button class="btn btn-sm btn-success approval-btn"
                                data-id="<?=(int)$row['id']?>" data-step="approved"
                                title="Mark Approved"><i class="bi bi-check2-circle me-1"></i>Approve</button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-danger approval-btn ms-1"
                                data-id="<?=(int)$row['id']?>" data-step="rejected"
                                title="Reject"><i class="bi bi-x-circle"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Banking Details Modal -->
<div class="modal fade" id="bankingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <form id="bankingForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="collection_id" id="bank_col_id">
                <input type="hidden" name="step" value="banked">
                <div class="modal-header" style="background:var(--primary);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-bank me-2"></i>Enter Banking Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bank Reference No. <span class="text-danger">*</span></label>
                        <input type="text" name="bank_ref" class="form-control" required placeholder="e.g. IBK12345678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Receipt (Photo/PDF) <span class="text-danger">*</span></label>
                        <input type="file" name="receipt_file" class="form-control" required accept="image/*,.pdf">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="bi bi-check-circle me-1"></i>Submit & Mark Banked</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extra_js = <<<'JS'
<script>
$(document).ready(function(){
    $('#approvalsTable').DataTable({order:[[5,'asc']]});

    $(document).on('click','.approval-btn', function(){
        const id   = $(this).data('id');
        const step = $(this).data('step');

        if (step === 'banked') {
            $('#bank_col_id').val(id);
            new bootstrap.Modal(document.getElementById('bankingModal')).show();
            return;
        }

        const title = step === 'rejected' ? 'Reject Collection?' : 'Confirm ' + step.charAt(0).toUpperCase() + step.slice(1);
        const text  = step === 'rejected' ? 'This cannot be undone.' : 'Proceed with the next workflow step?';
        const icon  = step === 'rejected' ? 'warning' : 'question';

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: step === 'rejected' ? '#dc3545' : '#1A3C34',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, proceed!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('approvals.php', {
                    method:'POST',
                    headers:{
                        'Content-Type':'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        csrf_token: document.querySelector('[name=csrf_token]')?.value || '',
                        collection_id: id,
                        step: step,
                        remarks: ''
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (!res.status) throw new Error(res.message);
                    return res;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.status) {
                Swal.fire({
                    title: 'Success!',
                    text: result.value.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                $('#row-' + id).fadeOut(400, function(){ $(this).remove(); });
            }
        });
    });

    // Handle Banking Form Submission
    $('#bankingForm').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...');

        fetch('approvals.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(res => {
            if(res.status) {
                Swal.fire({ title: 'Success!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false });
                bootstrap.Modal.getInstance(document.getElementById('bankingModal')).hide();
                $('#row-' + $('#bank_col_id').val()).fadeOut(400, function(){ $(this).remove(); });
            } else {
                Swal.fire({ title: 'Error', text: res.message, icon: 'error' });
            }
        })
        .catch(err => {
            Swal.fire({ title: 'Error', text: 'Request failed.', icon: 'error' });
        })
        .finally(() => {
            submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Submit & Mark Banked');
        });
    });
});
</script>
JS;
include INCLUDES_PATH . 'footer.php';
?>
