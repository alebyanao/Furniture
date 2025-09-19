<div class="container my-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">$Title</h1>
        <div class="text-muted">$Content</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <% if $FormMessage %>
                <div class="alert alert-<% if $FormMessageType = 'good' %>success<% else %>danger<% end_if %> alert-dismissible fade show" role="alert">
                    $FormMessage
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <% end_if %>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center">Kirim Pesan</h4>

                    <% with $ContactForm %>
                        <form $AttributesHTML>
                            <% loop $Fields %>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="$ID">$Title</label>
                                    $Field
                                </div>
                            <% end_loop %>

                            <div class="d-grid">
                                $Actions
                            </div>
                        </form>
                    <% end_with %>
                </div>
            </div>

        </div>
    </div>

</div>