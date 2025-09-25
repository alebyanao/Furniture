<div class="container mt-4" style="margin-top: 500px">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-5">$Title</h1>
            
            <%-- <% if $Content %>
                <div class="mb-4">
                    $Content
                </div>
            <% end_if %> --%>
            
            <% if $Posts %>
                <div class="row">
                    <% loop $Posts %>
                        <div class="col-md-4 mb-4">
                            <div class="card h-200 shadow-sm">
                                <% if $FeaturedImage %>
                                    <img src="$FeaturedImage.ScaleWidth(400).URL" class="card-img-top" alt="$Title" style="height: 300px; object-fit: cover;">
                                <% else %>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <span class="text-muted">Tidak ada gambar</span>
                                    </div>
                                <% end_if %>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">$Title</h5>
                                    <p class="card-text flex-grow-1">
                                        <% if $Summary %>
                                            $Summary.LimitCharacters(120)
                                        <% else %>
                                            $Content.Summary(120)
                                        <% end_if %>
                                    </p>
                                    <small class="text-muted mb-2">
                                        <i class="fas fa-calendar-alt"></i> $Created.Format('d M Y')
                                    </small>
                                    <a href="{$Up.Link}/detail/{$ID}" class="btn btn-primary mt-auto">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    <% end_loop %>
                </div>
                
                <%-- Pagination --%>
                <% if $Posts.MoreThanOnePage %>
                    <nav aria-label="Pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <% if $Posts.NotFirstPage %>
                                <li class="page-item">
                                    <a class="page-link" href="$Posts.PrevLink" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <% end_if %>
                            
                            <% loop $Posts.PaginationSummary(4) %>
                                <% if $CurrentBool %>
                                    <li class="page-item active">
                                        <span class="page-link">$PageNum</span>
                                    </li>
                                <% else %>
                                    <% if $Link %>
                                        <li class="page-item">
                                            <a class="page-link" href="$Link">$PageNum</a>
                                        </li>
                                    <% else %>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <% end_if %>
                                <% end_if %>
                            <% end_loop %>
                            
                            <% if $Posts.NotLastPage %>
                                <li class="page-item">
                                    <a class="page-link" href="$Posts.NextLink" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <% end_if %>
                        </ul>
                    </nav>
                <% end_if %>
                
            <% else %>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-file-text" style="font-size: 64px; color: #ddd;"></i>
                    </div>
                    <h5 class="mb-3">Belum ada berita!</h5>
                    <p class="text-muted mb-4">Belum ada post blog yang tersedia saat ini. Admin dapat menambahkan post baru melalui panel admin</p>
                </div>
            <% end_if %>
        </div>
    </div>
</div>