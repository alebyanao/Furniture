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

          <!-- Price -->
          <div class="mb-3 mb-md-4">
            <% if $hasDiscount %>
              <div class="d-flex flex-wrap align-items-baseline">
                <span class="text-muted text-decoration-line-through me-2 fs-6">$FormattedPrice</span>
                <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;">$FormattedDiscountPrice</span>
              </div>
            <% else %>
              <span class="fw-bold fs-4 fs-md-3" style="color:#c4965c;">$FormattedPrice</span>
            <% end_if %>
          </div>

          <!-- Quantity Cart-->
          <div class="mt-auto">
            <% if $isInStock %>
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
              <strong class="flex-shrink-0">Jumlah :</strong>
              
              <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-sm-auto">
                <div class="input-group rounded-pill border overflow-hidden flex-shrink-0" style="width: 120px; max-width: 120px;">
                  <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(-1)">-</button>
                  <input type="number" id="quantity" class="form-control text-center border-0 px-1" value="1" min="1" max="$Stock" readonly style="font-size: 14px;">
                  <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(1)">+</button>
                </div>

                <div class="d-flex gap-2">
                  <button class="btn text-white fw-bold px-3 px-md-4 rounded-pill" style="background-color: #c4965c; font-size: 14px;" onclick="addToCartFromProduct()">keranjang</button>
                  <button class="btn btn-outline-danger rounded-pill d-flex align-items-center justify-content-center" style="width: 40px; height: 38px;" title="Add to Wishlist" id="wishlist-btn" onclick="toggleWishlist($ID)">
                    <i class="far fa-heart" id="wishlist-icon-$ID"></i>
                  </button>
                </div>
              </div>
            </div>
            <% else %>
            <div class="alert alert-warning mt-3 mb-0 d-flex justify-content-between align-items-center">
              <span><strong>Out of Stock</strong> - This product is currently unavailable</span>
              <button class="btn btn-outline-danger btn-sm" title="Add to Wishlist" id="wishlist-btn" onclick="toggleWishlist($ID)">
                <i class="far fa-heart" id="wishlist-icon-$ID"></i> Wishlist
              </button>
            </div>
            <% end_if %>
          </div>

        </div>
      </div>
    </div>
    <% end_with %>
  </div>
</div>

<div class="container">
  <hr class="my-5">

<div class="related-product mt-5">
        <h3 class="fw-bold mb-4">Produk Lainnya</h3>
        <div class="row">
        <% loop $Products %>
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <div class="product-card position-relative" onclick="window.location.href='$Top.Link(product)/$ID'">
                <!-- Wishlist Button on Product Card -->
                <button class="btn btn-outline-danger position-absolute rounded-circle" 
                        style="top: 10px; right: 10px; width: 35px; height: 35px; z-index: 3; background-color: rgba(255,255,255,0.9);" 
                        title="Add to Wishlist" 
                        onclick="event.stopPropagation(); toggleWishlist($ID)">
                    <i class="far fa-heart fa-sm" id="wishlist-icon-$ID"></i>
                </button>

                <div class="product-image-container position-relative" style="background-color: #fdf8ef; padding: 40px 20px 60px; min-height: 350px;">
                    <% if $hasDiscount %>
                    <div class="discount-badge position-absolute" style="top: 15px; left: 15px; background-color: #c4965c; color: white; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold;">
                        $DiscountPercentage Off
                    </div>
                    <% end_if %>
                    
                    <% if $Image %>
                    <img src="$Image.FitMax(160,160).URL" alt="$Name" class="img-fluid" style="max-height: 160px; width: 100%; object-fit: contain;">
                    <% else %>
                    <img src="https://via.placeholder.com/160x160/f5f5f5/cccccc?text=No+Image" alt="$Name" class="img-fluid" style="max-height: 160px; width: 100%; object-fit: contain;">
                    <% end_if %>
                </div>
                
                <div class="product-info text-start mt-5">
                    <h6 class="fw-bold text-dark text-start ms-3 product-name">$Name</h6>
                    
                    <div class="rating-section text-start ms-3">
                        <div class="text-warning" style="font-size: 20px;">
                            $StarRating
                        </div>
                        <small class="text-muted">
                            <% if $ReviewCount > 0 %>
                                ($AverageRating/5) - $ReviewCount review<% if $ReviewCount > 1 %>s<% end_if %>
                            <% else %>
                                No reviews yet
                            <% end_if %>
                        </small>
                    </div>
                    
                    <div class="price-section text-start ms-3">
                        <% if $hasDiscount %>
                        <p class="text-muted mb-0 small text-decoration-line-through">$FormattedPrice</p>
                        <p class="fw-bold mb-0" style="color: #c4965c">$FormattedDiscountPrice</p>
                        <% else %>
                        <p class="fw-bold mb-0" style="color: #c4965c">$FormattedPrice</p>
                        <% end_if %>
                    </div>
                </div>
            </div>
        </div>
        <% end_loop %>
        <% if not $Products %>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>No Products Available</h4>
                <p>Please add products from the admin panel.</p>
            </div>
        </div>
        <% end_if %>
    </div>
</div>

<!-- Bootstrap and Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<script>
function changeQuantity(change) {
    const input = document.getElementById("quantity");
    let currentValue = parseInt(input.value) || 1;
    let newValue = currentValue + change;
    
    const maxStock = parseInt(input.getAttribute('max'));
    
    if (newValue < 1) newValue = 1;
    if (newValue > maxStock) newValue = maxStock;
    
    input.value = newValue;
}

function addToCartFromProduct() {
    const quantity = parseInt(document.getElementById("quantity").value) || 1;
    const productId = <% with $Product %>$ID<% end_with %>;
    
    // Show loading state
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Adding...';
    btn.disabled = true;
    
    const formData = new FormData();
    formData.append('ProductID', productId);
    formData.append('Quantity', quantity);
    
    fetch('/cart/addToCart', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }
        
        return response.json();
    })
    .then(data => {
        // Reset button
        btn.textContent = originalText;
        btn.disabled = false;
        
        if (data.success) {
            showMessage(data.message, 'success');
            // Reset quantity to 1
            document.getElementById("quantity").value = 1;
            
            // Ask if user wants to continue shopping or go to cart
            if (confirm('Item added to cart! Do you want to view your cart now?')) {
                window.location.href = '/cart/';
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        // Reset button
        btn.textContent = originalText;
        btn.disabled = false;
        
        console.error('Detailed error:', error);
        showMessage('Error adding to cart: ' + error.message, 'error');
        
        // Try alternative: redirect to cart page with product info
        if (confirm('Would you like to try adding to cart via page redirect?')) {
            window.location.href = `/cart/addToCart?ProductID=${productId}&Quantity=${quantity}`;
        }
    });
}

function toggleWishlist(productId) {
    const icon = document.getElementById('wishlist-icon-' + productId);
    const isInWishlist = icon.classList.contains('fas');
    
    if (isInWishlist) {
        // Remove from wishlist
        fetch('/wishlist-page/removeFromWishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'ProductID=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                showMessage('Product removed from wishlist', 'success');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        });
    } else {
        // Add to wishlist
        fetch('/wishlist-page/addToWishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'ProductID=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                showMessage('Product added to wishlist!', 'success');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        });
    }
}

function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Check wishlist status on page load
document.addEventListener('DOMContentLoaded', function() {
    <% with $Product %>
    checkWishlistStatus($ID);
    <% end_with %>
    
    <% loop $Products %>
    checkWishlistStatus($ID);
    <% end_loop %>
});

function checkWishlistStatus(productId) {
    fetch('/wishlist-page/isInWishlist?ProductID=' + productId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const icon = document.getElementById('wishlist-icon-' + productId);
        if (icon) {
            if (data.inWishlist) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        }
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
    });
}