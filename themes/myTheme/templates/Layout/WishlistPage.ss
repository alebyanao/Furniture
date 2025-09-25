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
                            <!-- Clickable Image Link -->
                            <a onclick="window.location.href='$Top.Link(product)/$Product.ID'" class="d-block" style="text-decoration: none; cursor: pointer;">
                                <% if $Product.Image %>
                                <img src="$Product.Image.FitMax(200,200).URL" alt="$Product.Name" class="img-fluid" style="max-height: 160px; max-width: 100%; object-fit: contain; transition: transform 0.2s ease;">
                                <% else %>
                                <img src="https://via.placeholder.com/200x200/f5f5f5/cccccc?text=No+Image" alt="$Product.Name" class="img-fluid" style="max-height: 160px; max-width: 100%; object-fit: contain; transition: transform 0.2s ease;">
                                <% end_if %>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="card-body">
                        <!-- Clickable Product Name -->
                        <h5 class="card-title mb-2 fw-bold">
                            <a onclick="window.location.href='$Top.Link(product)/$Product.ID'" style="text-decoration: none; color: inherit; cursor: pointer;">
                                $Product.Name
                            </a>
                        </h5>
                        
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
    <div class="d-flex flex-row justify-content-md-end justify-content-center">

      <!-- Keranjang -->

       <a href="$BaseHref/cart/add/$Product.ID" 
                class="btn cart-btn px-3 px-md-4 rounded-pill" 
                style="font-size: 14px;">
                <i class="bi bi-cart"></i>
              </a>

        <a href="$BaseHref/wishlist/remove/$ID" 
            class="btn btn-sm"
            onclick="return confirm('Yakin ingin menghapus item ini dari wishlist?')">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
            </svg>
        </a>
      
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

<style>
/* Hover effect for product im~agese */
.card:hover img {
    transform: scale(1.05);
}

/* Hover effect for product name links */
.card-title a:hover {
    color: #c4965c !important;
}

/* Make clickable elements more obvious */
a[onclick] {
    cursor: pointer !important;
}

a[onclick]:hover {
    opacity: 0.8;
}


.cart-btn {
  background-color: transparent;
  color: #c4965c; /* icon default pakai warna emas */
  border: 1px solid #c4965c; /* biar ada outline */
  transition: all 0.3s ease;
}

.cart-btn:hover,
.cart-btn:focus,
.cart-btn:active {
  background-color: #c4965c;
  color: #fff; /* icon jadi putih saat hover/click */
}

</style>