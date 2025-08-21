<!-- Hero Banner Section -->
<% if $hasHeroBanners %>
<section class="hero-section position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-md-6 text-center text-md-start">
                <% with $FirstHeroBanner %>
                <h1 class="fw-bold text-dark display-5 mt-2 mb-3">
                    <% if $Title %>$Title<% else %>Elevate Your Home Aesthetics<% end_if %>
                </h1>
                <p class="text-muted mt-3 fs-6">
                    <% if $Description %>$Description<% else %>A furniture e-commerce company operates in the digital space, offering a wide range of furniture products for sale through an online platform.<% end_if %>
                </p>
                <% end_with %>
            </div>
            <div class="col-md-6 text-center mt-4 mt-md-0">
                <div class="hero-circle">
                    <div class="circle-bg"></div>
                    <div class="hero-image-swiper swiper">
                        <div class="swiper-wrapper">
                            <% loop $getAllHeroImages %>
                            <div class="swiper-slide">
                                <img src="$URL" class="hero-image" alt="Hero Image $Pos">
                            </div>
                            <% end_loop %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<% end_if %>

<!-- Features Section -->
<% if $hasFeatureItems %>
<section class="py-4" style="margin-top: 75px; margin-bottom: 75px;">
    <div class="container">
        <div class="row text-center text-md-start">
            <% loop $FeatureItems %>
            <div class="col-6 col-md-3 d-flex align-items-start mb-3 mb-md-0">
                <div class="me-3 d-flex align-items-center justify-content-center" style="min-width: 40px; height: 40px;">
                    <% if $IconType == 'fontawesome' && $FontAwesomeIcon %>
                        <i class="$FontAwesomeIcon" style="font-size: 40px; color: #b78b5c;"></i>
                    <% else_if $IconType == 'image' && $IconImage.exists %>
                        <img src="$IconImage.AbsoluteURL" alt="$Title" width="40" height="40" class="feature-icon" style="object-fit: contain;">
                    <% else %>
                        <i class="fas fa-star" style="font-size: 40px; color: #b78b5c;"></i>
                    <% end_if %>
                </div>
                <div>
                    <strong class="d-block text-dark">$Title</strong>
                    <small class="text-muted">$Description</small>
                </div>
            </div>
            <% end_loop %>
        </div>
    </div>
</section>
<% end_if %>

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


<!-- FEATURED PRODUCTS SECTION (Carousel Layout) -->
<% if $hasFeaturedProducts %>
<section class="featured-products py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-dark mb-3">Featured Products</h2>
                <p class="text-muted">Our handpicked featured collection</p>
            </div>
        </div>

        <div class="carousel-outer-container">
            <div class="product-carousel-container position-relative">
                <div class="product-carousel" id="featured-carousel">
                    <div class="product-carousel-wrapper">
                        <% loop $FeaturedProducts %>
                        <div class="product-carousel-slide">
                            <div class="product-card" onclick="window.location.href='$Top.Link(product)/$ID'">
                                <div class="product-image-container position-relative" style="background-color: #fdf8ef; padding: 40px 20px; min-height: 350px;">
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
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <button class="carousel-btn carousel-btn-prev" onclick="handleCarouselClick('featured-carousel', -1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" onclick="handleCarouselClick('featured-carousel', 1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <% if not $FeaturedProducts %>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No Featured Products Available</h4>
                    <p>Please add featured products from the admin panel.</p>
                </div>
            </div>
        </div>
        <% end_if %>
    </div>
</section>
<% end_if %>

<!-- TRENDY COLLECTION SECTION (Carousel Layout) -->
<% if $hasTrendyProducts %>
<section class="trendy-collection py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark">Trendy Collection</h2>
                        <p class="text-muted">Latest trends in furniture design</p>
                    </div>
                    <a href="$BaseHref/shop" class="btn btn-primary">View All</a>
                </div>
            </div>
        </div>

        <div class="carousel-outer-container">  
            <div class="product-carousel-container position-relative">
                <div class="product-carousel" id="trendy-carousel">
                    <div class="product-carousel-wrapper">
                        <% loop $TrendyProducts %>
                        <div class="product-carousel-slide">
                            <div class="product-card" onclick="window.location.href='$Top.Link(product)/$ID'">
                                <div class="product-image-container position-relative" style="background-color: #fdf8ef; padding: 40px 20px; min-height: 350px;">
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
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <button class="carousel-btn carousel-btn-prev" onclick="handleCarouselClick('trendy-carousel', -1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" onclick="handleCarouselClick('trendy-carousel', 1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <% if not $TrendyProducts %>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No Trendy Products Available</h4>
                    <p>Please add trendy products from the admin panel.</p>
                </div>
            </div>
        </div>
        <% end_if %>
    </div>
</section>
<% end_if %>

<!-- PROMO PRODUCTS SECTION (Carousel Layout) -->
<% if $hasPromoProducts %>
<section class="promo-products py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-dark mb-3">Special Promo</h2>
                <p class="text-muted">Don't miss these amazing deals</p>
            </div>
        </div>

        <div class="carousel-outer-container">
            <div class="product-carousel-container position-relative">
                <div class="product-carousel" id="promo-carousel">
                    <div class="product-carousel-wrapper">
                        <% loop $PromoProducts %>
                        <div class="product-carousel-slide">
                            <div class="product-card" onclick="window.location.href='$Top.Link(product)/$ID'">
                                <div class="product-image-container position-relative" style="background-color: #fdf8ef; padding: 40px 20px; min-height: 350px;">
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
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <button class="carousel-btn carousel-btn-prev" onclick="handleCarouselClick('promo-carousel', -1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" onclick="handleCarouselClick('promo-carousel', 1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <% if not $PromoProducts %>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No Promo Products Available</h4>
                    <p>Please add promo products from the admin panel.</p>
                </div>
            </div>
        </div>
        <% end_if %>
    </div>
</section>
<% end_if %>

<!-- BEST SELLERS SECTION (Grid Layout - unchanged) -->
<% if $hasBestSellers %>
<section class="best-sellers py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-dark mb-3">Best Sellers</h2>
                <p class="text-muted">Customer favorites and top-rated products</p>
            </div>
        </div>

        <div class="row">
            <% loop $BestSellersProducts %>
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
            
            <% if not $BestSellersProducts %>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No Best Sellers Available</h4>
                    <p>Please add best selling products from the admin panel.</p>
                </div>
            </div>
            <% end_if %>
        </div>
    </div>
</section>
<% end_if %>