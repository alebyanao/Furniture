<div class="container py-3 py-md-5">
    <h3 class="fw-bold mb-4">Keranjang Belanja</h3>

    <!-- User Message Display -->
    <% if $getUserMessage %>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        $getUserMessage
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <% end_if %>

    <% if $CartItems && $CartItems.Count > 0 %>
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8 mb-4">
            <!-- Desktop Header -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row fw-bold text-muted d-none d-md-flex" style="font-size: 18px; color: #c4965c !important;">
                        <div class="col-md-5">Item</div>
                        <div class="col-md-2 text-center">Harga</div>
                        <div class="col-md-2 text-center">Quantity</div>
                        <div class="col-md-2 text-center">Total</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>

            <!-- Cart Items Loop -->
            <% loop $CartItems %>
            <div class="card mb-3 border-0 shadow-sm cart-item" data-item-id="$ID">
                <div class="row g-0 align-items-center">
                    <!-- Product Image & Name -->
                    <div class="col-12 col-md-5">
                        <div class="d-flex align-items-center p-3">
                            <div class="position-relative me-3" style="background-color: #fdf8ef; border: 1px solid #c4965c; padding: 10px; min-width: 140px; min-height: 100px;">
                                <% if $Product.hasDiscount %>
                                <div class="discount-badge position-absolute" style="top: 8px; left: 8px; background-color: #c4965c; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; z-index: 2;">
                                    $Product.DiscountPercentage Off
                                </div>
                                <% end_if %>
                                
                                <div class="d-flex align-items-center justify-content-center h-100">
                                     <a onclick="window.location.href='$Top.Link(product)/$Product.ID'" class="d-block" style="text-decoration: none; cursor: pointer;">
                                        <% if $Product.Image %>
                                        <img src="$Product.Image.FitMax(100,100).URL" alt="$Product.Name" class="img-fluid" style="max-height: 140px; max-width: 100%; object-fit: contain; transition: transform 0.2s ease;">
                                        <% else %>
                                        <!-- No Image Placeholder -->
                                        <svg width="80" height="50" viewBox="0 0 80 50" fill="none">
                                            <rect x="5" y="15" width="70" height="20" rx="10" fill="#e0e0e0"/>
                                            <rect x="15" y="12" width="50" height="26" rx="13" fill="#ccc"/>
                                            <rect x="20" y="32" width="4" height="10" fill="#999"/>
                                            <rect x="56" y="32" width="4" height="10" fill="#999"/>
                                        </svg>
                                        <% end_if %>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <!-- Clickable Product Name -->
                                <h6 class="mb-0 fw-bold">
                                    <a onclick="window.location.href='$Top.Link(product)/$Product.ID'" style="text-decoration: none; color: inherit; cursor: pointer;">
                                        $Product.Name
                                    </a>
                                </h6>
                                <small class="text-muted">Stok: $Product.Stock</small>
                                <div class="mt-1">
                                    <% if $Product.AverageRating %>
                                    <span class="text-warning small">★ $Product.AverageRating</span>
                                    <% else %>
                                    <span class="text-warning small">★ 0</span>
                                    <% end_if %>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-6 col-md-2 text-center">
                        <div class="p-2">
                            <small class="d-md-none text-muted">Harga:</small>
                            <div class="fw-bold unit-price" data-unit-price="<% if $Product.hasDiscount %>$Product.DiscountPrice<% else %>$Product.Price<% end_if %>">
                                <% if $Product.hasDiscount %>
                                    $Product.FormattedDiscountPrice
                                <% else %>
                                    $Product.FormattedPrice
                                <% end_if %>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Form -->
                    <div class="col-6 col-md-2 text-center">
                        <div class="p-2">
                            <small class="d-md-none text-muted">Quantity:</small>
                            <form method="post" action="$BaseHref/cart/update-quantity" class="quantity-form" data-item-id="$ID">
                                <input type="hidden" name="cartItemID" value="$ID">
                                <div class="input-group rounded-pill border overflow-hidden flex-shrink-0" style="width: 120px; max-width: 120px;">
                                    <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(this, -1)">-</button>
                                    
                                    <input type="number" 
                                           name="quantity"
                                           class="form-control text-center border-0 px-1 quantity-input" 
                                           value="$Quantity" 
                                           min="1" 
                                           max="$Product.Stock" 
                                           data-original-value="$Quantity"
                                           onchange="scheduleSubmit(this)"
                                           style="font-size: 14px;">
                                           
                                    <button class="btn btn-outline-secondary border-0 px-2" type="button" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-6 col-md-2 text-center">
                        <div class="p-2">
                            <small class="d-md-none text-muted">Total:</small>
                            <div class="fw-bold item-total" style="color: #c4965c;">$FormattedSubtotal</div>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <div class="col-6 col-md-1 text-center">
                        <div class="p-2">
                            <a href="$BaseHref/cart/remove/$ID" 
                               class="btn btn-sm" 
                               style="width: 40px; height: 40px;"
                               onclick="return confirm('Yakin ingin menghapus item ini?')">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <% end_loop %>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border: 1px solid #fdf8ef;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total barang</span>
                        <span class="fw-bold" id="total-items">$TotalItems Item</span>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total harga</span>
                        <span class="fw-bold fs-5" style="color: #c4965c;" id="total-price">$FormattedTotalPrice</span>
                    </div>
                    
                    <a href="$BaseHref/checkout" class="btn w-100 text-white fw-bold rounded-pill py-3" style="background-color: #c4965c;">
                        Beli
                    </a>
                </div>
            </div>
        </div>
    </div>

    <% else %>
    <!-- Empty Cart -->
    <div class="text-center py-5">
        <div class="mb-4">
            <svg width="64" height="64" fill="#ddd" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>
        <h5 class="mb-3">Keranjang anda kosong</h5>
        <p class="text-muted mb-4">Silahkan tambahkan produk dahulu</p>
        <a href="$BaseHref/shop" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #c4965c;">Belanja Sekarang</a>
    </div>
    <% end_if %>
</div>

<style>
/* Hover effects for clickable elements */
a[onclick]:hover {
    opacity: 0.8;
}

.cart-item h6 a:hover {
    color: #c4965c !important;
}

.cart-item:hover img {
    transform: scale(1.05);
}
</style>

<script>
// Format rupiah function
function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

// Update item total price (real-time preview)
function updateItemTotal(input) {
    const quantity = parseInt(input.value) || 1;
    const unitPrice = parseFloat(input.closest('.cart-item').querySelector('.unit-price').getAttribute('data-unit-price')) || 0;
    const itemTotal = unitPrice * quantity;
    
    // Update item total display
    input.closest('.cart-item').querySelector('.item-total').textContent = formatRupiah(itemTotal);
    
    // Update grand totals
    updateGrandTotal();
}

// Update grand total (real-time preview)
function updateGrandTotal() {
    let totalItems = 0;
    let totalPrice = 0;
    
    document.querySelectorAll('.quantity-input').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        const unitPrice = parseFloat(input.closest('.cart-item').querySelector('.unit-price').getAttribute('data-unit-price')) || 0;
        
        totalItems += quantity;
        totalPrice += (unitPrice * quantity);
    });
    
    // Update displays
    document.getElementById('total-items').textContent = totalItems + ' Item';
    document.getElementById('total-price').textContent = formatRupiah(totalPrice);
}

// Change quantity with +/- buttons
function changeQuantity(button, delta) {
    const input = button.parentElement.querySelector('.quantity-input');
    let value = parseInt(input.value) || 1;
    const min = parseInt(input.getAttribute('min')) || 1;
    const max = parseInt(input.getAttribute('max')) || 99;

    value += delta;
    if (value < min) value = min;
    if (value > max) {
        alert('Quantity melebihi stok yang tersedia! Maksimal: ' + max);
        value = max;
    }

    input.value = value;
    updateItemTotal(input); // Update preview
    scheduleSubmit(input);  // Schedule form submit
}

// Schedule form submission with debounce
function scheduleSubmit(input) {
    // Validate quantity
    let value = parseInt(input.value) || 1;
    const min = parseInt(input.getAttribute('min')) || 1;
    const max = parseInt(input.getAttribute('max')) || 99;
    const originalValue = parseInt(input.getAttribute('data-original-value')) || 1;

    if (value < min) {
        value = min;
        input.value = value;
    }
    if (value > max) {
        alert('Quantity melebihi stok yang tersedia! Maksimal: ' + max);
        value = max;
        input.value = value;
    }

    // Update preview
    updateItemTotal(input);

    // Only submit if value changed
    if (value !== originalValue) {
        // Clear existing timeout
        clearTimeout(input.submitTimeout);
        
        // Schedule submit after 1 second (faster)
        input.submitTimeout = setTimeout(() => {
            submitFormAjax(input);
        }, 1000);
    }
}

// Submit the form via AJAX (no page reload)
function submitFormAjax(input) {
    const cartItemID = input.closest('form').querySelector('input[name="cartItemID"]').value;
    const quantity = input.value;
    
    // Update original value to prevent duplicate submits
    input.setAttribute('data-original-value', quantity);
    
    // Create simple POST request
    const formData = new FormData();
    formData.append('cartItemID', cartItemID);
    formData.append('quantity', quantity);
    
    // Send request in background
    fetch('/cart/update-quantity', {
        method: 'POST',
        body: formData
    }).then(response => {
        // Success - quantity saved silently
        console.log('Quantity updated');
    }).catch(error => {
        // Error - revert to original value
        console.error('Update failed:', error);
        input.value = input.getAttribute('data-original-value');
        updateItemTotal(input);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set up initial state
    updateGrandTotal();
        
    // Add manual input change listeners
    document.querySelectorAll('.quantity-input').forEach(input => {
        // On focus out
        input.addEventListener('blur', function() {
            scheduleSubmit(this);
        });
        
        // On Enter key
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                scheduleSubmit(this);
            }
        });
        
        // On input change (for real-time preview)
        input.addEventListener('input', function() {
            updateItemTotal(this);
        });
    });
});
</script>