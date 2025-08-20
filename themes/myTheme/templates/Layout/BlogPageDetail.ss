<div class="container mt-4" style="margin-top: 500px">
    <div class="row">
        <div class="col-12">
            <%-- Breadcrumb --%>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb" style="--bs-breadcrumb-divider: '>'; color: #c4965c;">
                    <li class="breadcrumb-item"><a href="$BaseHref" style="color:#c4965c;">Home</a></li>
                    <li class="breadcrumb-item"><a href="$Top.Link" style="color:#c4965c;">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color:#c4965c;">$Post.Title</li>
                </ol>
            </nav>
            
            <% if $Post %>
                <article class="blog-post">
                    <%-- Gambar utama --%>
                    <% if $Post.FeaturedImage %>
                        <div class="mb-4">
                            <img src="$Post.FeaturedImage.ScaleWidth(800).URL" 
                                 class="img-fluid shadow-sm" 
                                 alt="$Post.Title"
                                 style="width: 100%; height: 400px; object-fit: cover;">
                        </div>
                    <% end_if %>
                    
                    <%-- Judul dan meta info --%>
                    <header class="mb-4">
                        <h1 class="display-4 mb-3">$Post.Title</h1>
                        
                        <div class="text-muted mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>Dipublikasikan pada $Post.Created.Format('d F Y')</span>
                            
                            <% if $Post.LastEdited != $Post.Created %>
                                <span class="ms-3">
                                    <i class="fas fa-edit me-2"></i>
                                    Terakhir diupdate $Post.LastEdited.Format('d F Y')
                                </span>
                            <% end_if %>
                        </div>
                        
                        <% if $Post.Summary %>
                            <div class="lead mb-4 p-3" style="background-color: #f8f9fa">
                                $Post.Summary
                            </div>
                        <% end_if %>
                    </header>
                    
                    <%-- Konten --%>
                    <div class="blog-content">
                        $Post.Content
                    </div>
                </article>
                
                <%-- Navigasi kembali --%>
                <div class="mt-5 pt-4 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="$Top.Link" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali ke Blog
                            </a>
                        </div>
                        
                        <%-- Share buttons (optional) --%>
                        <div class="col-md-6 text-md-end">
                            <span class="text-muted me-3">Bagikan:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={$AbsoluteLink}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={$AbsoluteLink}&text={$Post.Title}" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm me-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={$Post.Title} {$AbsoluteLink}" 
                               target="_blank" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                
                <%-- Related posts atau latest posts --%>
                <% if $Top.LatestPosts %>
                    <div class="mt-5">
                        <h3 class="mb-4">Berita Terbaru Lainnya</h3>
                        <div class="row">
                            <% loop $Top.LatestPosts.Exclude('ID', $Post.ID).Limit(3) %>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <% if $FeaturedImage %>
                                            <img src="$FeaturedImage.ScaleWidth(300).URL" 
                                                 class="card-img-top" 
                                                 alt="$Title"
                                                 style="height: 150px; object-fit: cover;">
                                        <% end_if %>
                                        
                                        <div class="card-body">
                                            <h6 class="card-title">$Title</h6>
                                            <p class="card-text small">
                                                <% if $Summary %>
                                                    $Summary.LimitCharacters(80)
                                                <% else %>
                                                    $Content.Summary(80)
                                                <% end_if %>
                                            </p>
                                            <a href="{$Up.Link}/detail/{$ID}" class="btn btn-sm btn-outline-primary">Baca</a>
                                        </div>
                                    </div>
                                </div>
                            <% end_loop %>
                        </div>
                    </div>
                <% end_if %>
                
            <% else %>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Post tidak ditemukan!</h4>
                    <p>Maaf, post blog yang Anda cari tidak ditemukan atau mungkin telah dihapus.</p>
                    <hr>
                    <a href="$Top.Link" class="btn btn-primary">Kembali ke Blog</a>
                </div>
            <% end_if %>    
        </div>
    </div>
</div>
