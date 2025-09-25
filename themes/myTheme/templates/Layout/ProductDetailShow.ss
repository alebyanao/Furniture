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
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur fuga fugit aperiam maxime numquam dolore. Architecto velit modi natus nisi eos sequi nesciunt ea nulla error provident laborum repellendus esse repudiandae eveniet perferendis ad, recusandae impedit voluptatem consequatur debitis.
                <% end_if %>
            </div>
          </div>

          <!-- Rating product detail -->
        <div class="mb-2">
            <% if $Product.AverageRating %>
            <span class="text-warning">★ $Product.AverageRating</span>
            <span class="text-muted">($Product.Review.Count Ulasan)</span>
            <% else %>
            <span class="text-warning">★ 0</span>
            <span class="text-muted">(0 Ulasan)</span>
            <% end_if %>
        </div>

          <!-- Price -->
          <div class="mb-3 mb-md-4">
            <% if $hasDiscount %>
              <div class="d-flex flex-wrap align-items-baseline">
                <span class="text-muted text-decoration-line-through me-2 fs-6">$FormattedPrice</span>
                <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;" id="unit-price" data-price="$DiscountPrice">$FormattedDiscountPrice</span>
              </div>
            <% else %>
              <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;" id="unit-price" data-price="$Price">$FormattedPrice</span>
            <% end_if %>
            
            <!-- Total Price Display -->
            <div class="mt-2">
              <small class="text-muted">Total: </small>
              <span class="fw-bold fs-5" style="color:#c4965c;" id="total-price">
                <% if $hasDiscount %>
                  $FormattedDiscountPrice
                <% else %>
                  $FormattedPrice
                <% end_if %>
              </span>
            </div>
          </div>

          <!-- Quantity Cart-->
          <div class="mt-auto">
            <% if $isInStock %>
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
              <strong class="flex-shrink-0">Jumlah :</strong>
              
              <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-sm-auto">
                <div class="input-group rounded-pill border overflow-hidden flex-shrink-0" style="width: 120px; max-width: 120px;">
                  <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(-1)">-</button>
                  <input type="number" id="quantity" class="form-control text-center border-0 px-1" value="1" min="1" max="$Stock" onchange="updateTotalPrice()" style="font-size: 14px;">
                  <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(1)">+</button>
                </div>

                <div class="d-flex gap-2">
                  <button class="btn text-white fw-bold px-3 px-md-4 rounded-pill" style="background-color: #c4965c; font-size: 14px;" onclick="addToCart()">keranjang</button>

                  <% if $IsInWishlist %>
                      <button type="button"
                              class="btn btn-outline-secondary rounded-pill d-flex align-items-center justify-content-center"
                              style="width: 40px; height: 38px; background-color: #ff6b6b; border-color: #ff6b6b;"
                              title="Remove from Wishlist"
                              onclick="window.location.href='{$BaseHref}/wishlist/add/$ID'">
                              <i class="fas fa-heart" style="color: white;"></i>
                      </button>
                  <% else %>
                      <button type="button"
                              class="btn btn-outline-secondary rounded-pill d-flex align-items-center justify-content-center"
                              style="width: 40px; height: 38px;"
                              title="Add to Wishlist"
                              onclick="window.location.href='{$BaseHref}/wishlist/add/$ID'">
                              <i class="far fa-heart"></i>
                      </button>
                  <% end_if %>
                </div>
              </div>
            </div>
            <% else %>
            <div class="alert alert-warning mt-3 mb-0">
              <strong>Out of Stock</strong> - This product is currently unavailable
            </div>
            <% end_if %>
          </div>
        </div>
      </div>
    </div>
    <% end_with %>
  </div>
</div>

<!-- User Message Display 
<% if $getUserMessage %>
<div class="container mt-3">
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    $getUserMessage
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<% end_if %>-->

<div class="container">
  <hr class="my-5">
          <!-- Reviews -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">Ulasan Pelanggan</h5>
            <% if Review && Review.Count > 0 %>
                <% loop Review %>
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
</div>

<script>
// Format rupiah function
function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

// Update total price display
function updateTotalPrice() {
    const quantity = parseInt(document.getElementById("quantity").value) || 1;
    const unitPrice = parseFloat(document.getElementById("unit-price").getAttribute('data-price')) || 0;
    const totalPrice = unitPrice * quantity;
    
    document.getElementById("total-price").textContent = formatRupiah(totalPrice);
}

// Change quantity function
function changeQuantity(change) {
    const input = document.getElementById("quantity");
    let currentValue = parseInt(input.value) || 1;
    let newValue = currentValue + change;
    
    const maxStock = parseInt(input.getAttribute('max'));
    
    if (newValue < 1) newValue = 1;
    if (newValue > maxStock) newValue = maxStock;
    
    input.value = newValue;
    updateTotalPrice();
}

// Add to cart function - simple redirect with quantity
function addToCart() {
    const quantity = parseInt(document.getElementById("quantity").value) || 1;
    const productId = <% with $Product %>$ID<% end_with %>;
    
    // Simple redirect to cart add with quantity parameter
    window.location.href = `$BaseHref/cart/add/${productId}/${quantity}`;
}

// Initialize total price on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotalPrice();
});
</script>