<% if $flashMessages %>
<div class="alert alert-$flashMessages.Type alert-dismissible fade show" role="alert">
    $flashMessages.Message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<% end_if %>

<main class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <form action="$BaseHref/auth/forgot-password" method="POST" class="w-100 p-4 border rounded shadow-sm" style="max-width: 400px;">
        <h4 class="mb-3">Lupa Sandi</h4>
        <p class="text-muted mb-3">Masukkan email Anda untuk mendapatkan link reset password</p>
        
        <% if $ValidationResult && not $ValidationResult.IsValid %>
            <% loop $ValidationResult.getMessages %>
                <div class="alert alert-danger">$Message</div>
            <% end_loop %>
        <% end_if %>
        
        <div class="mb-3">
            <label for="forgot_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="forgot_email" name="forgot_email" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Kirim Link Reset</button>
        
        <div class="text-center">
            <p class="mb-0">Ingat password? <a href="$BaseHref/auth/login">Masuk di sini</a></p>
        </div>
    </form>
</main>