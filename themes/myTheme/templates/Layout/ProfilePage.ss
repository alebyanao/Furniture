<main class="profile-page">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Profil Saya</h2>
                <% if $MembershipTier %>
    <% with $MembershipTierObject %>
        <div class="d-flex align-items-center gap-3 p-3 border rounded mb-3 bg-white">
            <% if $Image %>
                <img 
                    src="$Image.URL" 
                    alt="$Name" 
                    class="rounded-circle" 
                    width="56" 
                    height="56"
                    style="object-fit: cover;"
                />
            <% end_if %>
            <div>
                <h6 class="mb-0 text-primary">$Name</h6>
                <small class="text-muted">Keanggotaan Aktif</small>
            </div>
        </div>
    <% end_with %>
<% else %>
    <div class="d-flex align-items-center gap-3 p-3 border rounded mb-3 bg-light">
        <i class="bi bi-person-circle text-secondary" style="font-size: 2rem;"></i>
        <span class="text-secondary">Member</span>
    </div>
<% end_if %>

<% if $MembershipProgress %>
    <div class="card border mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                <span>Membership Progress</span>
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="mb-2">
                        <small class="text-muted d-block">Total Transaksi</small>
                        <strong class="text-success">Rp $MembershipProgress.current_total</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Status Saat Ini</small>
                        <strong>$MembershipProgress.current_tier</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <% if $MembershipProgress.next_tier %>
                        <div class="mb-2">
                            <small class="text-muted d-block">Target Berikutnya</small>
                            <strong class="text-primary">$MembershipProgress.next_tier</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Sisa Menuju Kenaikan</small>
                            <strong class="text-warning">Rp $MembershipProgress.remaining_amount</strong>
                        </div>

                        <div class="progress" style="height: 22px;">
                            <div
                                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                role="progressbar"
                                style="width: $MembershipProgress.progress_percentage;"
                                aria-valuenow="$MembershipProgress.progress_percentage"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >
                                $MembershipProgress.progress_percentage
                            </div>
                        </div>
                    <% else %>
                        <div class="text-center py-2">
                            <% if $MembershipTierObject.Image %>
                                <img
                                    src="$MembershipTierObject.Image.URL"
                                    alt="$MembershipTierObject.Name"
                                    width="48"
                                    height="48"
                                    class="mb-2 rounded-circle"
                                    style="object-fit: cover;"
                                />
                            <% end_if %>
                            <div class="text-success">
                                <strong>🎉 Selamat!</strong>
                                <p class="mb-0 small">Anda telah mencapai tingkat tertinggi</p>
                            </div>
                        </div>
                    <% end_if %>
                </div>
            </div>
        </div>
    </div>
<% end_if %>

                
                <% if $UpdateSuccess %>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        $UpdateSuccess
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <% end_if %>
                
                <% if $UpdateErrors %>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        $UpdateErrors.RAW
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <% end_if %>
                
                <% if $Member %>
                    <form method="POST" action="$BaseHref/profile" class="profile-form">
                        <input type="hidden" name="SecurityID" value="$SecurityID" />
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nama Depan *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="$Member.FirstName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Nama Belakang *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="$Member.Surname" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="$Member.Email" required>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Ubah Sandi (Opsional)</h5>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Sandi Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <small class="form-text text-muted">Diperlukan jika ingin mengubah Sandi</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Sandi Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <small class="form-text text-muted">Minimal 8 karakter</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Sandi Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="$BaseHref" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                        </div>
                    </form>
                <% end_if %>
            </div>
        </div>
    </div>
</main>