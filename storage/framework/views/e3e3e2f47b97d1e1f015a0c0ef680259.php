<!-- Vendor js -->
<script src="<?php echo e(asset('backend/assets/js/vendors.min.js')); ?>"></script>

<!-- App js -->
<script src="<?php echo e(asset('backend/assets/js/app.js')); ?>"></script>

<!-- Additional Scripts -->
<?php echo e($scripts ?? ''); ?>



<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Toast Notification Container -->
<div class="top-0 p-3 position-fixed end-0" style="z-index: 11000">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="mdi mdi-bell-ring me-2" id="toastIcon"></i>
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Message here
        </div>
    </div>
</div>

<script>
    // Toast notification function
    window.showToast = function(type, message, title = null) {
        const toast = document.getElementById('liveToast');
        const toastIcon = document.getElementById('toastIcon');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        const toastHeader = toast.querySelector('.toast-header');

        // Reset classes
        toastHeader.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'text-white');
        toastIcon.className = 'mdi me-2';

        // Set icon and colors based on type
        if (type === 'success') {
            toastHeader.classList.add('bg-success', 'text-white');
            toastIcon.classList.add('mdi-check-circle');
            toastTitle.textContent = title || 'Success';
        } else if (type === 'error') {
            toastHeader.classList.add('bg-danger', 'text-white');
            toastIcon.classList.add('mdi-alert-circle');
            toastTitle.textContent = title || 'Error';
        } else if (type === 'warning') {
            toastHeader.classList.add('bg-warning', 'text-white');
            toastIcon.classList.add('mdi-alert-outline');
            toastTitle.textContent = title || 'Warning';
        } else if (type === 'info') {
            toastHeader.classList.add('bg-info', 'text-white');
            toastIcon.classList.add('mdi-information');
            toastTitle.textContent = title || 'Info';
        }

        toastMessage.textContent = message;

        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
    };

    // Listen for Livewire toast events
    window.addEventListener('toast', event => {
        const detail = event.detail[0] || event.detail;
        showToast(detail.type, detail.message, detail.title);
    });
</script>

<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/admin/partials/scripts.blade.php ENDPATH**/ ?>