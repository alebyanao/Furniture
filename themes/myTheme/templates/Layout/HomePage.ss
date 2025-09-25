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

<% include PromoCard %>

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