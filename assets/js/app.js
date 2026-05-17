/**
 * DanaHibah™ — Main Application JS
 */
'use strict';

// ─── Sidebar Toggle ──────────────────────────────────────────
const sidebar        = document.getElementById('sidebar');
const sidebarToggle  = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function isMobile() { return window.innerWidth < 992; }

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        if (isMobile()) {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
        }
    });
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });
}

// Restore sidebar state on desktop
if (!isMobile() && localStorage.getItem('sidebar_collapsed') === 'true') {
    sidebar && sidebar.classList.add('collapsed');
}



// ─── Flash Auto-dismiss ───────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.flash-message').forEach(el => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        bsAlert && bsAlert.close();
    });
}, 5000);

// ─── AJAX Helper ─────────────────────────────────────────────
function ajaxPost(url, data, onSuccess, onError) {
    const spinner = document.getElementById('spinnerOverlay');
    if (spinner) spinner.style.display = 'flex';

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(r => r.json())
    .then(res => {
        if (spinner) spinner.style.display = 'none';
        if (res.status) { onSuccess && onSuccess(res); }
        else            { onError   && onError(res);   }
    })
    .catch(err => {
        if (spinner) spinner.style.display = 'none';
        showToast('error', 'Request failed. Please try again.');
        console.error(err);
    });
}

// ─── Toast Notifications ─────────────────────────────────────
function showToast(type, message) {
    const container = document.getElementById('toastContainer') || createToastContainer();
    const id   = 'toast_' + Date.now();
    const icon = type === 'success' ? 'bi-check-circle-fill text-success'
               : type === 'error'   ? 'bi-x-circle-fill text-danger'
               : type === 'warning' ? 'bi-exclamation-triangle-fill text-warning'
               :                      'bi-info-circle-fill text-info';
    container.insertAdjacentHTML('beforeend', `
        <div id="${id}" class="toast align-items-center border-0 show" role="alert"
             style="border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.15);">
            <div class="d-flex align-items-center gap-2 p-3">
                <i class="bi ${icon}"></i>
                <span style="font-size:.875rem;">${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`);
    const el = document.getElementById(id);
    setTimeout(() => { el && bootstrap.Toast.getOrCreateInstance(el).hide(); }, 4000);
    el && el.addEventListener('hidden.bs.toast', () => el.remove());
}

function createToastContainer() {
    const c = document.createElement('div');
    c.id = 'toastContainer';
    c.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    c.style.zIndex = '9999';
    document.body.appendChild(c);
    return c;
}

// ─── Flash Message Repositioning ────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.querySelector('.flash-message');
    const header = document.querySelector('.page-header');
    if (flash && header) {
        // Move the flash message to immediately after the page header
        header.parentNode.insertBefore(flash, header.nextSibling);
    }
});

// ─── Confirm Delete / Actions (SweetAlert2) ───────────────────
document.addEventListener('click', e => {
    const btn = e.target.closest('[data-confirm]');
    if (btn) {
        e.preventDefault();
        const msg = btn.dataset.confirm || 'Are you sure?';
        
        // Check if it's an approve action to customize title/icon
        const isApprove = btn.getAttribute('href') && btn.getAttribute('href').includes('approve.php');
        const swalTitle = isApprove ? 'Confirm Approved' : 'Are you sure?';
        const swalIcon  = isApprove ? 'question' : 'warning';
        const confirmBtn = isApprove ? 'Yes, proceed!' : 'Yes, do it!';
        const confirmColor = isApprove ? '#1A3C34' : '#dc3545';
        
        Swal.fire({
            title: swalTitle,
            text: msg,
            icon: swalIcon,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmBtn
        }).then((result) => {
            if (result.isConfirmed) {
                const href = btn.getAttribute('href') || btn.dataset.action;
                if (href) window.location.href = href;
                else if (btn.closest('form')) btn.closest('form').submit();
            }
        });
    }
});

// ─── DataTable Defaults ───────────────────────────────────────
if (typeof $.fn !== 'undefined' && $.fn.dataTable) {
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            search:      '<i class="bi bi-search"></i> _INPUT_',
            searchPlaceholder: 'Search...',
            lengthMenu:  'Show _MENU_ entries',
            info:        'Showing _START_ to _END_ of _TOTAL_ records',
            paginate: {
                first:    '<i class="bi bi-chevron-double-left"></i>',
                last:     '<i class="bi bi-chevron-double-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>',
                next:     '<i class="bi bi-chevron-right"></i>'
            }
        },
        pageLength: 25,
        responsive: true,
        dom: "<'row mb-3 align-items-center'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-light-success' },
            { extend: 'pdf', text: 'PDF', className: 'btn btn-sm btn-light-danger' },
            { extend: 'print', text: 'Print', className: 'btn btn-sm btn-light-dark' }
        ]
    });
}

// ─── Number Counter Animation ─────────────────────────────────
function animateCount(el, target, duration = 1200) {
    const start = 0;
    const step  = (target / duration) * 16;
    let   cur   = start;
    const timer = setInterval(() => {
        cur += step;
        if (cur >= target) { cur = target; clearInterval(timer); }
        el.textContent = Math.floor(cur).toLocaleString();
    }, 16);
}

// Animate stat values on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-count]').forEach(el => {
        animateCount(el, parseFloat(el.dataset.count));
    });
});
