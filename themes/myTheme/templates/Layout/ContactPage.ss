<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">$Title</h1>
        <div class="text-muted">$Content</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Alert Message -->
            <% if $AlertMessage %>
                <div class="alert alert-$AlertType alert-dismissible fade show" role="alert">
                    <% if $AlertType = 'success' %>
                        <i class="fas fa-check-circle me-2"></i>
                    <% else %>
                        <i class="fas fa-exclamation-triangle me-2"></i>
                    <% end_if %>
                    $AlertMessage
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <% end_if %>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center">Kirim Pesan</h4>

                    <% with $ContactForm %>
                        <form $AttributesHTML>
                           <% loop $Fields %>
                                <% if $Name != 'SecurityID' %>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" for="$ID">$Title <span class="text-danger">*</span></label>
                                        $Field
                                    </div>
                                <% else %>
                                    $Field
                                <% end_if %>
                            <% end_loop %>

                            <div class="d-grid">
                                $Actions
                            </div>
                        </form>
                    <% end_with %>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Semua field dengan tanda <span class="text-danger">*</span> wajib diisi
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.alert {
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #198754;
    background-color: #d1e7dd;
    color: #0f5132;
}

.alert-danger {
    border-left-color: #dc3545;
    background-color: #f8d7da;
    color: #842029;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alert setelah 5 detik
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    });

    // Clear form jika ada parameter success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        const form = document.querySelector('form');
        if (form) {
            // Reset semua input fields
            form.reset();
            
            // Khusus untuk field yang mungkin tidak ter-reset
            const inputs = form.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.value = '';
            });
            
            // Clean URL setelah delay singkat
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 100);
        }
    }

    // Loading state untuk form
    const form = document.querySelector('form');
    const submitBtn = form?.querySelector('button[type="submit"]');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
        });
    }
});
</script>