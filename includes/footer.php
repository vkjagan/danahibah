<?php
/**
 * DanaHibah™ - Footer Include
 */
?>
</div><!-- /.page-content -->

    <!-- App Footer -->
    <footer class="app-footer">
        <span>
            &copy; <?= date('Y') ?> <strong><?= APP_NAME ?></strong> &mdash; Secure. Transparent. Amanah.
            &nbsp;|&nbsp; Solutions by <a href="https://www.arisio.com.my" target="_blank">Arisio Sdn Bhd</a>
        </span>
        <span>Version <?= APP_VERSION ?></span>
    </footer>

</div><!-- /.main-content -->
</div><!-- /.app-wrapper -->

<!-- Spinner Overlay -->
<div class="spinner-overlay" id="spinnerOverlay">
    <div class="spinner-border text-primary" role="status" style="width:48px;height:48px;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
<!-- App JS -->
<script src="<?= APP_URL ?>/assets/js/app.js?v=<?= time() ?>"></script>
<?php if (!empty($extra_js)) echo $extra_js; ?>
</body>
</html>
