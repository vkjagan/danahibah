<?php
/**
 * DanaHibah™ - HTML Header Include
 * Usage: include at top of every protected page
 * Required vars: $page_title, $breadcrumbs (array), $active_menu
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$page_title  = $page_title  ?? 'Dashboard';
$meta_title  = $meta_title  ?? e($page_title) . ' — ' . APP_NAME;
$breadcrumbs = $breadcrumbs ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($meta_desc ?? META_DESCRIPTION) ?>">
    <meta name="keywords"    content="<?= e(META_KEYWORDS) ?>">
    <meta property="og:title"       content="<?= e($meta_title) ?>">
    <meta property="og:description" content="<?= e($meta_desc ?? META_DESCRIPTION) ?>">
    <meta property="og:image"       content="<?= META_OG_IMAGE ?>">
    <meta property="og:type"        content="website">
    <meta name="theme-color" content="#1A3C34">
    <title><?= e($meta_title) ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css?v=<?= time() ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
    <?php if (!empty($extra_css)) echo $extra_css; ?>
</head>
<body>
<div class="app-wrapper">

<?php include INCLUDES_PATH . 'sidebar.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-content">
<?php include INCLUDES_PATH . 'navbar.php'; ?>
<div class="page-content">
<?php show_flash(); ?>
