<div class="container py-3 py-md-5">
    <h3 class="fw-bold mb-4">Wishlist</h3>

    <% if $Wishlists && $Wishlists.Count > 0 %>
        <% loop $Wishlists %>
        <div class="card mb-4 border-0 shadow-sm">
            <div class="row g-0 align-items-center">
                <!-- Product Image -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="position-relative text-center p-3" style="background-color: #fdf8ef; border: 1px solid #c4965c; min-height: 200px;">
                        <% if $Product.hasDiscount %>
                        <div class="discount-badge position-absolute" style="top: 10px; left: 10px; background-color: #c4965c; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; z-index: 2;">
                            $Product.DiscountPercentage Off
                        </div>
                        <% end_if %>
                        
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <% if $Product.Image %>
                            <img src="$Product.Image.FitMax(200,200).URL" alt="$Product.Name" class="img-fluid" style="max-height: 160px; max-width: 100%; object-fit: contain;">
                            <% else %>
                            <img src="https://via.placeholder.com/200x200/f5f5f5/cccccc?text=No+Image" alt="$Product.Name" class="img-fluid" style="max-height: 160px; max-width: 100%; object-fit: contain;">
                            <% end_if %>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="card-body">
                        <h5 class="card-title mb-2 fw-bold">$Product.Name</h5>
                        
                        <!-- Stock Status -->
                        <p class="mb-2 fs-6"><strong>Stok :</strong> $Product.Stock</p>

                        <%-- Rating di Wishlist --%>
                        <p class="mb-1 text-warning">
                            <% if $Product.AverageRating %>
                                ★ $Product.AverageRating <span class="text-muted">($Product.Review.Count Ulasan)</span>
                            <% else %>
                                ★ 0 <span class="text-muted">(0 Ulasan)</span>
                            <% end_if %>
                        </p>
                        
                        <!-- Price -->
                        <div class="price-section">
                            <% if $Product.hasDiscount %>
                            <div class="d-flex flex-wrap align-items-baseline">
                                <span class="text-muted text-decoration-line-through me-2 small">$Product.FormattedPrice</span>
                                <span class="fw-bold fs-5" style="color:#c4965c;">$Product.FormattedDiscountPrice</span>
                            </div>
                            <% else %>
                            <span class="fw-bold fs-5" style="color:#c4965c;">$Product.FormattedPrice</span>
                            <% end_if %>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="card-body text-center text-md-end">
                        <div class="d-flex flex-column gap-2">
                          
                            <a href="$BaseHref/cart/add/$Product.ID" class="btn btn-sm text-white fw-bold rounded-pill" style="background-color: #c4965c;">Keranjang</a> 
                            <a href="$BaseHref/wishlist/remove/$ID" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini dari Wishlist Anda?')">Hapus dari Wishlist </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <% end_loop %>

    <% else %>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="far fa-heart" style="font-size: 64px; color: #ddd;"></i>
            </div>
            <h5 class="mb-3">Belum ada produk wishlist</h5>
            <p class="text-muted mb-4">Silakan tambahkan produk ke wishlist terlebih dahulu.</p>
            <a href="$BaseHref" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #c4965c;">Tambah Wishlist</a>
        </div>
    <% end_if %>
</div>