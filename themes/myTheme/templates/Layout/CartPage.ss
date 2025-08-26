<div class="cart-container">
    <div class="container">
        
        <!-- Loading Overlay -->
        <div class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <!-- Cart Messages -->
        <% if $CartMessage %>
        <div class="alert alert-info">
            $CartMessage
        </div>
        <% end_if %>

        <!-- Checkout Success Message -->
        <% if $CheckoutMessage %>
        <div class="alert alert-success">
            <strong>Success!</strong> $CheckoutMessage
        </div>
        <% end_if %>

        <div class="row">
            <div class="col-12">
                <h1 class="fw-bold mb-4">Shopping Cart</h1>
            </div>
        </div>

        <% if $CartItems %>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-12 col-lg-8">
                <% loop $CartItems %>
                <div class="cart-item" id="cart-item-$Product.ID">
                    <div class="row align-items-center">
                        <!-- Product Image -->
                        <div class="col-12 col-sm-3 col-md-2 mb-3 mb-sm-0">
                            <div class="cart-item-image">
                                <% if $Product.Image %>
                                <img src="$Product.Image.FitMax(120,120).URL" alt="$Product.Name">
                                <% else %>
                                <img src="https://via.placeholder.com/120x120/f5f5f5/cccccc?text=No+Image" alt="$Product.Name">
                                <% end_if %>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="col-12 col-sm-4 col-md-3 mb-3 mb-sm-0">
                            <h5 class="fw-bold mb-2">$Product.Name</h5>
                            <div class="text-muted small">
                                Stock: $Product.Stock
                            </div>
                            <div class="mt-2">
                                <% if $Product.hasDiscount %>
                                <div class="small text-muted text-decoration-line-through">$Product.FormattedPrice</div>
                                <div class="fw-bold" style="color: #c4965c;">$Product.FormattedDiscountPrice</div>
                                <% else %>
                                <div class="fw-bold" style="color: #c4965c;">$Product.FormattedPrice</div>
                                <% end_if %>
                            </div>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="col-12 col-sm-3 col-md-3 mb-3 mb-sm-0">
                            <div class="d-flex align-items-center justify-content-center justify-content-sm-start">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn quantity-decrease" data-product-id="$Product.ID" <% if $Quantity <= 1 %>disabled<% end_if %>>
                                        −
                                    </button>
                                    <input type="number" 
                                           class="quantity-input" 
                                           data-product-id="$Product.ID" 
                                           data-current-qty="$Quantity"
                                           value="$Quantity" 
                                           min="1" 
                                           max="$Product.Stock"
                                           readonly>
                                    <button type="button" class="quantity-btn quantity-increase" data-product-id="$Product.ID" <% if $Quantity >= $Product.Stock %>disabled<% end_if %>>
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal and Actions -->
                        <div class="col-12 col-sm-2 col-md-3">
                            <div class="text-center text-sm-end">
                                <div class="fw-bold mb-2" id="subtotal-$Product.ID" style="color: #c4965c;">
                                    $SubtotalFormatted
                                </div>
                                <button type="button" class="btn-remove remove-item" data-product-id="$Product.ID">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <% end_loop %>

                <!-- Continue Shopping -->
                <div class="text-center mt-4">
                    <a href="$BaseHref" class="btn-continue">
                        ← Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <div class="cart-summary">
                    <h4 class="fw-bold mb-4">Order Summary</h4>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Items:</span>
                        <span id="total-items" class="fw-bold">$TotalItems</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total:</span>
                        <span id="total-price" class="fs-4 fw-bold" style="color: #c4965c;">$CartTotalFormatted</span>
                    </div>

                    <button type="button" class="btn-checkout" onclick="window.location.href='$Link(checkout)'">
                        Proceed to Checkout
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="$Link(clearCart)" class="text-muted small" onclick="return confirm('Are you sure you want to clear your cart?')">
                            Clear Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <% else %>
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 text-center">
                    <div class="mb-4">
                        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-muted">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="m1 1 4 4 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <h3 class="mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                    <a href="$BaseHref" class="btn-continue">
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
        <% end_if %>
    </div>
</div>