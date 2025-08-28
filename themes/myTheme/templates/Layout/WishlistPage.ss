<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">My Wishlist</h2>
                <% if $WishlistItems %>
                <button class="btn btn-outline-danger" onclick="clearWishlist()">
                    <i class="fas fa-trash me-2"></i>Clear All
                </button>
                <% end_if %>
            </div>

            <% if $WishlistItems %>
            <div class="row" id="wishlist-items">
                <% loop $WishlistItems %>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4" id="wishlist-item-$Product.ID">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-0">
                            <!-- Product Image -->
                            <div class="position-relative text-center p-4" style="background-color: #fdf8ef; min-height: 250px;">
                                <% if $Product.hasDiscount %>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge text-white rounded-pill px-3" style="background-color: #c4965c;">
                                        $Product.DiscountPercentage Off
                                    </span>
                                </div>
                                <% end_if %>
                                
                                <% if $Product.Image %>
                                <img src="$Product.Image.FitMax(200,200).URL" alt="$Product.Name" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                                <% else %>
                                <img src="https://via.placeholder.com/200x200/f5f5f5/cccccc?text=No+Image" alt="$Product.Name" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                                <% end_if %>
                            </div>

                            <!-- Product Info -->
                            <div class="p-3">
                                <h6 class="fw-bold text-dark mb-2">$Product.Name</h6>
                                
                                <!-- Rating -->
                                <div class="mb-2">
                                    <div class="text-warning" style="font-size: 16px;">
                                        $Product.StarRating
                                    </div>
                                    <small class="text-muted">
                                        <% if $Product.ReviewCount > 0 %>
                                            ($Product.AverageRating/5) - $Product.ReviewCount review<% if $Product.ReviewCount > 1 %>s<% end_if %>
                                        <% else %>
                                            No reviews yet
                                        <% end_if %>
                                    </small>
                                </div>

                                <!-- Price -->
                                <div class="mb-3">
                                    <% if $Product.hasDiscount %>
                                    <small class="text-muted text-decoration-line-through d-block">$Product.FormattedPrice</small>
                                    <span class="fw-bold text-warning fs-5">$Product.FormattedDiscountPrice</span>
                                    <% else %>
                                    <span class="fw-bold text-warning fs-5">$Product.FormattedPrice</span>
                                    <% end_if %>
                                </div>

                                <!-- Stock Status -->
                                <div class="mb-3">
                                    <% if $Product.isInStock %>
                                    <span class="badge bg-success">In Stock ($Product.Stock)</span>
                                    <% else %>
                                    <span class="badge bg-danger">Out of Stock</span>
                                    <% end_if %>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2">
                                    <% if $Product.isInStock %>
                                    <button class="btn btn-warning text-white flex-grow-1" onclick="addToCartFromWishlist($Product.ID)">
                                        <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                    </button>
                                    <% else %>
                                    <button class="btn btn-secondary flex-grow-1" disabled>
                                        Out of Stock
                                    </button>
                                    <% end_if %>
                                    
                                    <button class="btn btn-outline-danger" onclick="removeFromWishlist($Product.ID)" title="Remove from Wishlist">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- View Product Link -->
                                <div class="mt-2">
                                    <a href="/product-page/product/$Product.ID" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>

                                <!-- Added Date -->
                                <div class="mt-2">
                                    <small class="text-muted">Added: $FormattedDateAdded</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <% end_loop %>
            </div>
            <% else %>
            <!-- Empty Wishlist -->
            <div class="text-center py-5" id="empty-wishlist">
                <div class="mb-4">
                    <i class="fas fa-heart fa-5x text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">Your Wishlist is Empty</h4>
                <p class="text-muted mb-4">Save items you love for later by clicking the heart icon on products</p>
                <a href="/product-page" class="btn btn-warning text-white px-4 py-2">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
            <% end_if %>
        </div>
    </div>
</div>

<!-- Bootstrap and Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<script>
function removeFromWishlist(productId) {
    if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
        return;
    }

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
            // Remove the item from DOM
            const item = document.getElementById('wishlist-item-' + productId);
            if (item) {
                item.style.transition = 'opacity 0.3s';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    
                    // Check if wishlist is empty
                    if (data.count === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
    });
}

function clearWishlist() {
    if (!confirm('Are you sure you want to clear all items from your wishlist?')) {
        return;
    }

    fetch('/wishlist-page/clearWishlist', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
    });
}

function addToCartFromWishlist(productId) {
    // This assumes you have a cart system similar to the one in your product detail
    const formData = new FormData();
    formData.append('ProductID', productId);
    formData.append('Quantity', 1);
    
    fetch('/cart/addToCart', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Product added to cart!', 'success');
            
            // Ask if user wants to remove from wishlist
            if (confirm('Item added to cart! Do you want to remove it from your wishlist?')) {
                removeFromWishlist(productId);
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error adding to cart. Please try again.', 'error');
    });
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
</script>