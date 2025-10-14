<div class="product-detail-container py-3 py-md-5">
  <div class="container">
    <% with $Product %>
    <div class="row g-3 g-md-4 align-items-start">
      
      <!-- Product Image -->
      <div class="col-12 col-md-6 col-lg-5">
        <div class="position-relative text-center p-3 p-md-4 h-100 d-flex flex-column" style="background-color: #fdf8ef; border: 1px solid #c4965c; min-height: 300px;">
          <% if $hasDiscount %>
          <div class="discount-badge position-absolute" style="top: 10px; left: 10px; background-color: #c4965c; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; z-index: 2;">
            $DiscountPercentage Off
          </div>
          <% end_if %>  

          <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <% if $Image %>
              <img src="$Image.FitMax(500,500).URL" alt="$Name" class="img-fluid" style="max-height: 350px; max-width: 100%; object-fit: contain;">
            <% else %>
              <img src="https://via.placeholder.com/500x500/f5f5f5/cccccc?text=No+Image" alt="$Name" class="img-fluid" style="max-height: 350px; max-width: 100%; object-fit: contain;">
            <% end_if %>
          </div>
        </div>
      </div>

      <!-- Product Info -->
      <div class="col-12 col-md-6 col-lg-7">
        <div class="h-100 d-flex flex-column px-0 px-md-3 px-lg-4">
          
          <!-- Product Title -->
          <h1 class="fw-bold mb-3 fs-2 fs-md-1">$Name</h1>

          <!-- Stock Info -->
          <p class="mb-2 fs-6"><strong>Stok :</strong> $Stock</p>
          
          <!-- Description -->
          <div class="mb-2 mb-md-3 flex-grow-1">
            <strong class="d-block mb-2">Deskripsi :</strong>
            <div class="border p-3" style="height: 170px; overflow-y: auto; background-color: #fafafa;">
              <% if $Description %>
                $Description
              <% else %>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur fuga fugit aperiam maxime numquam dolore.
              <% end_if %>
            </div>
          </div>

          <!-- Rating -->
          <div class="mb-2">
            <% if $AverageRating %>
              <span class="text-warning">★ $AverageRating</span>
              <span class="text-muted">($Review.Count Ulasan)</span>
            <% else %>
              <span class="text-warning">★ 0</span>
              <span class="text-muted">(0 Ulasan)</span>
            <% end_if %>
          </div>

          <!-- Price & Buttons -->
          <div class="mb-3 mb-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
              
              <!-- Harga -->
              <div>
                <% if $hasDiscount %>
                  <div class="d-flex flex-wrap align-items-baseline">
                    <span class="text-muted text-decoration-line-through me-2 fs-6">$FormattedPrice</span>
                    <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;" 
                          id="unit-price" data-price="$DiscountPrice">
                          $FormattedDiscountPrice
                    </span>
                  </div>
                <% else %>
                  <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;" 
                        id="unit-price" data-price="$Price">
                        $FormattedPrice
                  </span>
                <% end_if %>
              </div>

              <!-- Tombol -->
              <div class="d-flex gap-2">
              <a href="$BaseHref/cart/add/$ID" 
                class="btn cart-btn px-3 px-md-4 rounded-pill" 
                style="font-size: 14px;">
                <i class="bi bi-cart"></i>
              </a>
                <% if $IsInWishlist %>
                <button type="button"
                        class="btn btn-outline-secondary rounded-pill d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 38px; background-color: #ff6b6b; border-color: #ff6b6b;"
                        title="Remove from Wishlist"
                        onclick="window.location.href='$BaseHref/wishlist/toggle/$ID'">
                  <i class="fas fa-heart" style="color: white;"></i>
                </button>
              <% else %>
                <button type="button"
                        class="btn btn-outline-secondary rounded-pill d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 38px;"
                        title="Add to Wishlist"
                        onclick="window.location.href='$BaseHref/wishlist/toggle/$ID'">
                  <i class="far fa-heart"></i>
                </button>
              <% end_if %>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
    <% end_with %>
  </div>
</div>

<div class="container">
  <hr class="my-5">
  <!-- Reviews -->
  <div class="row">
    <div class="col-12">
      <h5 class="mb-3">Ulasan Pelanggan</h5>
      <% with $Product %>
        <% if $Review && $Review.Count > 0 %>
          <% loop $Review %>
            <div class="mb-4 border rounded p-3">
              <strong><% if $ShowName == true %>$Member.FirstName $Member.Surname<% else %>Anonim<% end_if %></strong> 
              <span class="text-warning">★ $Rating</span>
              <p class="mb-0">
                <% if $Message %>$Message<% else %>Tidak ada komentar<% end_if %>
              </p>
            </div>
          <% end_loop %>
        <% else %>
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="bi bi-star" style="font-size: 64px; color: #ddd;"></i>
            </div>
            <h5 class="mb-3">Belum ada ulasan untuk produk ini</h5>
          </div>
        <% end_if %>
      <% end_with %>
    </div>
  </div>

  <% if not $Product %>
    <div class="col-12">
      <div class="alert alert-info text-center">
        <h4>No Products Available</h4>
        <p>Please add products from the admin panel.</p>
      </div>
    </div>
  <% end_if %>
</div>
<style>
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