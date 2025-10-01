<!-- PROMO CARDS SECTION -->
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
    <% end_if %>
