<div class="container">
    <div class="row">
        <%-- <div class="col-12">
            <h1 class="text-center mb-3">All Product</h1>
        </div> --%>
    </div>

<%-- <% include Promocard %> --%>

<%-- PRODUCT --%>
    <div class="row">
        <% loop $Products %>
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <div class="product-card" onclick="window.location.href='$Top.Link(product)/$ID'">
                <div class="product-image-container position-relative" style="background-color: #fdf8ef; padding: 40px 20px 60px; min-height: 350px;">
                    <% if $hasDiscount %>
                    <div class="discount-badge position-absolute" style="top: 15px; left: 15px; background-color: #c4965c; color: white; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold;">
                        $DiscountPercentage Off
                    </div>
                    <% end_if %>
                    
                    <% if $Image %>
                    <img src="$Image.FitMax(160,160).URL" alt="$Name" class="img-fluid" style="max-height: 160px; width: 100%; object-fit: contain;">
                    <% else %>
                    <img src="https://via.placeholder.com/160x160/f5f5f5/cccccc?text=No+Ima ge" alt="$Name" class="img-fluid" style="max-height: 160px; width: 100%; object-fit: contain;">
                    <% end_if %>
                </div>
                
                <div class="product-info text-start mt-5">
                    <h6 class="fw-bold text-dark text-start ms-3 product-name">$Name</h6>

                    <%-- rating di homepage dan productpage --%>
                   <div class="ms-3 mb-2">
                        <% if $AverageRating %>
                        <span class="text-warning small">★ $AverageRating</span>
                        <span class="text-muted small">($Review.Count Ulasan)</span>
                        <% else %>
                        <span class="text-warning small">★ 0</span>
                        <span class="text-muted small">(0 Ulasan)</span>
                        <% end_if %>
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