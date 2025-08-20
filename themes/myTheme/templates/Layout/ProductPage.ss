<div class="container" style="margin-top: 50px">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-3">All Product</h1>
        </div>
    </div>

    <% if $hasPromoCards %>
    <section class="promo-cards-section my-5">
        <div class="container">
            <div class="row g-4">
                <% loop $getTwoPromoCards %>
                <div class="col-md-6">
                    <div class="promo-card p-4 d-flex align-items-center justify-content-between h-100" 
                        style="background-color: $BackgroundColor; min-height: 250px; transition: all 0.3s ease;">
                        
                        <!-- Text Content -->
                        <div class="flex-grow-1 pe-3">
                            <% if $DiscountText %>
                            <p class="text-uppercase small mb-2 fw-semibold discount-text" 
                            style="color: #b78b5c; letter-spacing: 0.5px;">$DiscountText</p>
                            <% end_if %>
                            
                            <h4 class="fw-bold mb-3" style="color: #333; line-height: 1.3;">$Title</h4>
                            
                            <% if $Description %>
                            <p class="text-muted mb-3 small">$Description</p>
                            <% end_if %>
                            
                            <% if $ButtonText %>
                            <a href="$ProcessedButtonLink" 
                            class="btn btn-brown d-inline-flex align-items-center"
                            style="font-size: 14px;">
                                $ButtonText
                                <i class="bi bi-chevron-right ms-2"></i>
                            </a>
                            <% end_if %>
                        </div>
                        
                        <!-- Image Section -->
                        <div class="flex-shrink-0">
                            <% if $PromoImage.exists %>
                            <img src="$PromoImage.AbsoluteURL" 
                                alt="$Title" 
                                class="img-fluid" 
                                style="max-height: 180px; max-width: 200px; object-fit: contain; border-radius: 10px;">
                            <% else %>
                            <!-- Placeholder for missing image -->
                            <div class="icon-placeholder d-flex align-items-center justify-content-center" 
                                style="width: 180px; height: 180px; background-color: rgba(183, 139, 92, 0.1); border-radius: 10px;">
                                <i class="fas fa-tag" style="font-size: 48px; color: #b78b5c; opacity: 0.4;"></i>
                            </div>
                            <% end_if %>
                        </div>
                    </div>
                </div>
                <% end_loop %>
            </div>

            <!-- Show all promo cards if more than 2 exist -->
            <% if $getPromoCardsCount > 2 %>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="text-muted mb-3">More special offers available:</p>
                    <div class="row g-3">
                        <% loop $getAllPromoCards.limit(4, 2) %>
                        <div class="col-md-6 col-lg-3">
                            <div class="promo-card-small p-3 text-center" 
                                style="background-color: $BackgroundColor; min-height: 150px;">
                                <% if $DiscountText %>
                                <small class="text-uppercase fw-semibold d-block mb-2" 
                                    style="color: #b78b5c;">$DiscountText</small>
                                <% end_if %>
                                <h6 class="fw-bold mb-2">$Title</h6>
                                <% if $ButtonText %>
                                <a href="$ProcessedButtonLink" class="btn btn-sm btn-brown">$ButtonText</a>
                                <% end_if %>
                            </div>
                        </div>
                        <% end_loop %>
                    </div>
                </div>
            </div>
            <% end_if %>
        </div>
    </section>
    <% else %>
    
    <!-- Default/Fallback Promo Cards - Tampil jika tidak ada promo card yang aktif -->
    <section class="promo-cards-section my-5">
        <div class="container">
            <div class="row g-4">
                <!-- Default Promo Card 1 -->
                <div class="col-md-6">
                    <div class="promo-card p-4 d-flex align-items-center justify-content-between" 
                        style="background-color: #eaf7fb; min-height: 250px;">
                        <div class="flex-grow-1 pe-3">
                            <p class="text-uppercase small mb-2 fw-semibold" 
                            style="color: #b78b5c;">Get 30% Off</p>
                            <h4 class="fw-bold mb-3" style="color: #333;">Wicker Hanging Chairs</h4>
                            <a href="/shop" class="btn btn-brown d-inline-flex align-items-center">
                                Buy Now
                                <i class="bi bi-chevron-right ms-2"></i>
                            </a>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="icon-placeholder d-flex align-items-center justify-content-center" 
                                style="width: 180px; height: 180px; background-color: rgba(183, 139, 92, 0.1); border-radius: 10px;">
                                <i class="fas fa-chair" style="font-size: 48px; color: #b78b5c; opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Default Promo Card 2 -->
                <div class="col-md-6">
                    <div class="promo-card p-4 d-flex align-items-center justify-content-between" 
                        style="background-color: #eaf7fb; min-height: 250px;">
                        <div class="flex-grow-1 pe-3">
                            <p class="text-uppercase small mb-2 fw-semibold" 
                            style="color: #b78b5c;">Get 15% Off</p>
                            <h4 class="fw-bold mb-3" style="color: #333;">Brasslegged Armchair</h4>
                            <a href="/shop" class="btn btn-brown d-inline-flex align-items-center">
                                Buy Now
                                <i class="bi bi-chevron-right ms-2"></i>
                            </a>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="icon-placeholder d-flex align-items-center justify-content-center" 
                                style="width: 180px; height: 180px; background-color: rgba(183, 139, 92, 0.1); border-radius: 10px;">
                                <i class="fas fa-couch" style="font-size: 48px; color: #b78b5c; opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <% end_if %>

<%-- PRODUCT --%>
    <div class="row">
        <% loop $Products %>
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <div class="product-card" onclick="window.location.href='/product-detail/show/$ID'">
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