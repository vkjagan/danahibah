<?php
/**
 * DanaHibah™ - Management Insights Report
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_auth();

// Security Check: Super Admin, Management, AND Branch Admins can view this
if (!is_super_admin() && !is_management() && !is_branch_admin()) {
    set_flash('error', 'Access denied.');
    redirect(APP_URL . '/index.php');
}

$page_title  = 'Insights Dashboard';
$active_menu = 'reports';
$breadcrumbs = [['label' => 'Reports', 'url' => APP_URL . '/modules/reports/index.php'], ['label' => 'Insights Dashboard']];

// Apply Branch Filtering for Data Integrity
$branch_where = get_branch_filter();

// ─── Data Aggregations ────────────────────────────────────────────────────────

// 1. Monthly Collections (MoM - Last 6 Months)
$monthly_trend = db_fetch_all($conn,
    "SELECT DATE_FORMAT(collected_at, '%b %Y') AS month_label,
            DATE_FORMAT(collected_at, '%Y-%m') AS month_key,
            COALESCE(SUM(amount), 0) AS total,
            COUNT(id) AS cnt
     FROM collections c
     WHERE deleted_at IS NULL AND collected_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) AND " . get_branch_filter('c.') . "
     GROUP BY month_key
     ORDER BY month_key ASC",
    '', []
);

// 2. Collection by Category
$by_category = db_fetch_all($conn,
    "SELECT category, COALESCE(SUM(amount), 0) AS total, COUNT(id) AS cnt
     FROM collections c
     WHERE deleted_at IS NULL AND " . get_branch_filter('c.') . "
     GROUP BY category
     ORDER BY total DESC",
    '', []
);

// 3. Peak Day Analysis (Sunday to Saturday)
$peak_days = db_fetch_all($conn,
    "SELECT DAYNAME(collected_at) AS day_name,
            DAYOFWEEK(collected_at) AS day_num,
            COALESCE(SUM(amount), 0) AS total,
            COUNT(id) AS cnt
     FROM collections c
     WHERE deleted_at IS NULL AND " . get_branch_filter('c.') . "
     GROUP BY day_num
     ORDER BY day_num ASC",
    '', []
);

// 4. Top Performing Branches
$top_branches = db_fetch_all($conn,
    "SELECT b.name AS branch_name,
            COALESCE(SUM(c.amount), 0) AS total,
            COUNT(c.id) AS cnt,
            (SELECT COUNT(*) FROM devices d WHERE d.branch_id = b.id AND d.deleted_at IS NULL) AS devices_cnt
     FROM branches b
     LEFT JOIN collections c ON c.branch_id = b.id AND c.deleted_at IS NULL
     WHERE b.deleted_at IS NULL AND b.status='active' AND " . get_branch_filter('b.', 'id') . "
     GROUP BY b.id
     ORDER BY total DESC
     LIMIT 5",
    '', []
);

include INCLUDES_PATH . 'header.php';
?>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><i class="bi bi-graph-up-arrow me-2" style="color:var(--gold);"></i>Insights Dashboard</h1>
        <p class="page-subtitle">Advanced business intelligence and performance metrics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-table me-2"></i>Standard Report
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-2"></i>Print Executive Summary
        </button>
    </div>
</div>

<!-- MoM Trend & Top Branches -->
<div class="row g-3 mb-4">
    <!-- 6-Month Trend -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-activity text-gold me-2"></i>
                <span class="card-title">Month-on-Month Collection Trend</span>
                <span class="ms-auto badge bg-light text-dark">6 Months</span>
            </div>
            <div class="card-body">
                <canvas id="momChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Branches Ranking -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-trophy text-gold me-2"></i>
                <span class="card-title">Top Performing Branches</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($top_branches as $idx => $br): ?>
                    <div class="list-group-item py-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-700 text-truncate" style="max-width:200px;">
                                <?= $idx + 1 ?>. <?= e($br['branch_name']) ?>
                            </span>
                            <span class="badge bg-success-subtle text-success"><?= format_money($br['total']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-muted" style="font-size:.78rem;">
                            <span><i class="bi bi-receipt me-1"></i><?= number_format((int)$br['cnt']) ?> txns</span>
                            <span><i class="bi bi-hdd-rack me-1"></i><?= (int)$br['devices_cnt'] ?> devices</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Distribution & Peak Days -->
<div class="row g-3 mb-4">
    <!-- Category Distribution -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart text-gold me-2"></i>
                <span class="card-title">Category Revenue Share</span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="categoryShareChart" height="160" style="max-width:160px;"></canvas>
                <div class="mt-3 w-100 px-3">
                    <?php foreach ($by_category as $cat): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.82rem;">
                        <span class="text-capitalize"><i class="bi bi-circle-fill me-2" style="font-size:.5rem;"></i><?= e($cat['category']) ?></span>
                        <span class="fw-600"><?= format_money($cat['total']) ?> (<?= number_format((int)$cat['cnt']) ?>)</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Peak Collection Days -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-calendar-week text-gold me-2"></i>
                <span class="card-title">Weekly Peak Collection Analysis</span>
            </div>
            <div class="card-body">
                <canvas id="weeklyPeakChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Charts Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const primaryColor = '#1A3C34';
const goldColor    = '#C9A84C';

// 1. MoM Line Chart
<?php
$mom_labels = array_column($monthly_trend, 'month_label');
$mom_data   = array_map(fn($r) => (float)$r['total'], $monthly_trend);
?>
new Chart(document.getElementById('momChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($mom_labels) ?>,
        datasets: [{
            label: 'Total Revenue (RM)',
            data: <?= json_encode($mom_data) ?>,
            borderColor: primaryColor,
            backgroundColor: 'rgba(26, 60, 52, 0.08)',
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: goldColor,
            pointBorderColor: '#fff',
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => 'RM ' + v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});

// 2. Category Pie Chart
<?php
$cat_labels = array_column($by_category, 'category');
$cat_data   = array_map(fn($r) => (float)$r['total'], $by_category);
?>
new Chart(document.getElementById('categoryShareChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map('ucfirst', $cat_labels)) ?>,
        datasets: [{
            data: <?= json_encode($cat_data) ?>,
            backgroundColor: ['#1A3C34', '#C9A84C', '#2b6cb0', '#319795', '#d69e2e', '#e53e3e'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { display: false }
        }
    }
});

// 3. Weekly Peak Bar Chart
<?php
$day_labels = array_column($peak_days, 'day_name');
$day_data   = array_map(fn($r) => (float)$r['total'], $peak_days);
?>
new Chart(document.getElementById('weeklyPeakChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($day_labels) ?>,
        datasets: [{
            label: 'Collections (RM)',
            data: <?= json_encode($day_data) ?>,
            backgroundColor: goldColor,
            borderRadius: 6,
            barThickness: 24
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?php include INCLUDES_PATH . 'footer.php'; ?>
