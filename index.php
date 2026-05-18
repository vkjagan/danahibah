<?php
/**
 * DanaHibah™ - Dashboard (index.php)
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

start_session();
if (!is_logged_in()) {
    if ((isset($_GET['lang']) && $_GET['lang'] === 'ms') || (isset($_COOKIE['lang']) && $_COOKIE['lang'] === 'ms')) {
        require __DIR__ . '/home_ms.php';
    } else {
        require __DIR__ . '/home.php';
    }
    exit;
}

check_session_timeout();

$page_title  = 'Dashboard';
$active_menu = 'dashboard';
$breadcrumbs = [];

// ── Stats ────────────────────────────────────────────────────
$today     = date('Y-m-d');
$month_s   = date('Y-m-01');
$month_e   = date('Y-m-t');
$year_s    = date('Y-01-01');

// Total today
$stat_today = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt
     FROM collections WHERE DATE(collected_at)=? AND deleted_at IS NULL AND " . get_branch_filter(),
    's', [$today]);

// Total this month
$stat_month = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt
     FROM collections WHERE collected_at BETWEEN ? AND ? AND deleted_at IS NULL AND " . get_branch_filter(),
    'ss', [$month_s . ' 00:00:00', $month_e . ' 23:59:59']);

// Total this year
$stat_year = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total FROM collections
     WHERE collected_at >= ? AND deleted_at IS NULL AND " . get_branch_filter(),
    's', [$year_s . ' 00:00:00']);

// Pending approvals
$stat_pending = db_fetch_one($conn,
    "SELECT COUNT(*) AS cnt FROM collections
     WHERE status='collected' AND deleted_at IS NULL AND " . get_branch_filter(),
    '', []);

// ── Ledger Calculation ─────────────────────────────────────────
$filter = get_branch_filter();

// Cash In (Total Verified/Approved/Banked)
$cash_in_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM collections WHERE status IN ('verified','approved','banked') AND deleted_at IS NULL AND $filter", '', []);
$cash_in = (float)$cash_in_row['total'];

// Bank Deposits (Total)
$bank_dep_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM bank_deposits WHERE deleted_at IS NULL AND $filter", '', []);
$bank_deposit_total = (float)$bank_dep_row['total'];

// Expenses Cash vs Bank
$exp_cash_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE payment_source='cash' AND status='approved' AND deleted_at IS NULL AND $filter", '', []);
$exp_cash = (float)$exp_cash_row['total'];

$exp_bank_row = db_fetch_one($conn, "SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE payment_source='bank' AND status='approved' AND deleted_at IS NULL AND $filter", '', []);
$exp_bank = (float)$exp_bank_row['total'];

$cash_in_hand = $cash_in - $bank_deposit_total - $exp_cash;
$bank_balance = $bank_deposit_total - $exp_bank;

// This Month Deposit
$stat_dep_month = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt FROM bank_deposits
     WHERE deposit_date BETWEEN ? AND ? AND deleted_at IS NULL AND $filter",
    'ss', [$month_s, $month_e]);

// Today's Expense
$stat_expense_today = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt FROM expenses
     WHERE expense_date=? AND deleted_at IS NULL AND $filter",
    's', [$today]);

// This Month Expense
$stat_expense_month = db_fetch_one($conn,
    "SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt FROM expenses
     WHERE expense_date BETWEEN ? AND ? AND deleted_at IS NULL AND $filter",
    'ss', [$month_s, $month_e]);

// Recent transactions
$recent = db_fetch_all($conn,
    "SELECT c.*, b.name AS branch_name
     FROM collections c
     LEFT JOIN branches b ON b.id = c.branch_id
     WHERE c.deleted_at IS NULL AND " . get_branch_filter('c.') . "
     ORDER BY c.collected_at DESC LIMIT 10",
    '', []);

// Chart: last 7 days
$chart_labels = [];
$chart_data   = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('D, d M', strtotime($day));
    $row = db_fetch_one($conn,
        "SELECT COALESCE(SUM(amount),0) AS total FROM collections
         WHERE DATE(collected_at) = ? AND deleted_at IS NULL AND " . get_branch_filter(), 's', [$day]);
    $chart_data[] = (float)$row['total'];
}

// Collection by channel (this month)
$by_channel = db_fetch_all($conn,
    "SELECT channel, COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt
     FROM collections
     WHERE collected_at BETWEEN ? AND ? AND deleted_at IS NULL AND " . get_branch_filter() . "
     GROUP BY channel",
    'ss', [$month_s . ' 00:00:00', $month_e . ' 23:59:59']);

include INCLUDES_PATH . 'header.php';
?>

<!-- Page Header -->
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">
            <i class="bi bi-speedometer2 me-2" style="color:var(--gold);"></i>Dashboard
        </h1>
        <p class="page-subtitle">
            Welcome back, <strong><?= e($_SESSION['user_name']) ?></strong> &mdash;
            <?= date('l, d F Y') ?>
        </p>
    </div>
    <a href="modules/collections/add.php" class="btn btn-gold">
        <i class="bi bi-plus-circle me-2"></i>New Collection
    </a>
</div>

<!-- Stat Cards (Ledger View) -->
<div class="row g-3 mb-4 align-items-stretch">

    <!-- Cash In Hand -->
    <div class="col-12 col-md">
        <div class="stat-card h-100" style="border-bottom: 4px solid var(--success);">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">RM <span data-count="<?= $cash_in_hand ?>">0</span></div>
            <div class="stat-label">Total Cash in Hand</div>
            <div class="stat-change up">
                <i class="bi bi-safe2"></i> Ready for deposit
            </div>
        </div>
    </div>

    <!-- Bank Balance -->
    <div class="col-12 col-md">
        <div class="stat-card h-100" style="border-bottom: 4px solid var(--info);">
            <div class="stat-icon blue"><i class="bi bi-bank"></i></div>
            <div class="stat-value">RM <span data-count="<?= $bank_balance ?>">0</span></div>
            <div class="stat-label">Total Bank Balance</div>
            <div class="stat-change up">
                <i class="bi bi-graph-up"></i> Verified balance
            </div>
        </div>
    </div>

    <!-- This Month Deposit -->
    <div class="col-12 col-md">
        <div class="stat-card h-100">
            <div class="stat-icon blue"><i class="bi bi-arrow-down-square"></i></div>
            <div class="stat-value">RM <span data-count="<?= (float)$stat_dep_month['total'] ?>">0</span></div>
            <div class="stat-label">This Month Deposits</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i>
                <?= number_format((int)$stat_dep_month['cnt']) ?> transaction(s)
            </div>
        </div>
    </div>

    <!-- This Month Expense -->
    <div class="col-12 col-md">
        <div class="stat-card h-100">
            <div class="stat-icon red"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">RM <span data-count="<?= (float)$stat_expense_month['total'] ?>">0</span></div>
            <div class="stat-label">This Month Expense</div>
            <div class="stat-change down">
                <i class="bi bi-arrow-down-short"></i>
                <?= number_format((int)$stat_expense_month['cnt']) ?> record(s)
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="col-12 col-md">
        <div class="stat-card h-100">
            <div class="stat-icon gold"><i class="bi bi-clock-history"></i></div>
            <div class="stat-value"><span data-count="<?= (int)$stat_pending['cnt'] ?>">0</span></div>
            <div class="stat-label">Pending Approvals</div>
            <div class="stat-change <?= (int)$stat_pending['cnt'] > 0 ? 'down' : 'up' ?>">
                <i class="bi bi-exclamation-circle"></i>
                <?= (int)$stat_pending['cnt'] > 0 ? 'Requires action' : 'All clear' ?>
            </div>
        </div>
    </div>

</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">

    <!-- 7-Day Collection Trend -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up text-gold me-2"></i>
                <span class="card-title">7-Day Collection Trend</span>
                <span class="ms-auto badge" style="background:var(--primary);font-size:.72rem;">Last 7 Days</span>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Collection by Channel -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart text-gold me-2"></i>
                <span class="card-title">By Channel</span>
                <span class="ms-auto badge" style="background:var(--primary);font-size:.72rem;">This Month</span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="channelChart" height="200" style="max-width:200px;"></canvas>
                <div class="mt-3 w-100">
                    <?php foreach ($by_channel as $ch): ?>
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.82rem;">
                        <span class="text-capitalize"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i><?= e($ch['channel']) ?></span>
                        <span class="fw-600"><?= format_money($ch['total']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($by_channel)): ?>
                        <p class="text-center text-muted mb-0" style="font-size:.82rem;">No data yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-clock-history text-gold me-2"></i>
        <span class="card-title">Recent Transactions</span>
        <a href="modules/collections/index.php" class="ms-auto btn btn-sm btn-outline-primary">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Branch</th>
                        <th>Channel</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $row): ?>
                    <tr>
                        <td><code style="font-size:.78rem;"><?= e($row['txn_ref'] ?? '—') ?></code></td>
                        <td><strong><?= e($row['branch_name'] ?? '—') ?></strong></td>
                        <td>
                            <span class="badge bg-light text-dark text-capitalize">
                                <i class="bi bi-<?= $row['channel']==='qr' ? 'qr-code' : 'cash-coin' ?> me-1"></i>
                                <?= e($row['channel']) ?>
                            </span>
                        </td>
                        <td class="text-capitalize"><?= e($row['category']) ?></td>
                        <td><strong><?= format_money($row['amount']) ?></strong></td>
                        <td><?= status_badge($row['status']) ?></td>
                        <td style="font-size:.82rem;"><?= format_datetime($row['collected_at']) ?></td>
                        <td>
                            <a href="modules/collections/view.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const primary = '#1A3C34';
const gold    = '#C9A84C';

// Trend chart
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Collections (RM)',
            data:  <?= json_encode($chart_data) ?>,
            borderColor: primary,
            backgroundColor: 'rgba(26,60,52,.08)',
            borderWidth: 2.5,
            pointBackgroundColor: gold,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' RM ' + ctx.parsed.y.toLocaleString('en-MY', {minimumFractionDigits:2})
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,.05)' },
                ticks: { callback: v => 'RM ' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});

// Donut chart
<?php
$ch_labels = array_column($by_channel, 'channel');
$ch_data   = array_map(fn($r) => (float)$r['total'], $by_channel);
if (empty($ch_labels)) { $ch_labels = ['No Data']; $ch_data = [1]; }
?>
new Chart(document.getElementById('channelChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map('ucfirst', $ch_labels)) ?>,
        datasets: [{
            data: <?= json_encode($ch_data) ?>,
            backgroundColor: ['#1A3C34','#C9A84C','#3B82F6','#22C55E','#F59E0B'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' RM ' + ctx.parsed.toLocaleString('en-MY', {minimumFractionDigits:2})
                }
            }
        }
    }
});
</script>

<?php include INCLUDES_PATH . 'footer.php'; ?>
