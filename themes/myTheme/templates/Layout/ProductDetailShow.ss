<div class="product-detail-container py-5">
  <div class="container">
    <% with $Product %>
    <div class="row align-items-start">
      
      <!-- Product Image -->
      <div class="col-lg-6 col-md-6 mb-4">
        <div class="position-relative text-center p-4" style="background-color: #fdf8ef; border: 1px #c4965c">
          <% if $hasDiscount %>
          <div class="discount-badge position-absolute" style="top: 15px; left: 15px; background-color: #c4965c; color: white; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold;">
            $DiscountPercentage Off
          </div>
          <% end_if %>  

          <% if $Image %>
          <img src="$Image.FitMax(500,500).URL" alt="$Name" class="img-fluid" style="max-height:400px;object-fit:contain;">
          <% else %>
          <img src="https://via.placeholder.com/500x500/f5f5f5/cccccc?text=No+Image" alt="$Name" class="img-fluid">
          <% end_if %>
        </div>
      </div>

      <!-- Product Info -->
      <div class="col-lg-6 col-md-6 ps-5">
        <h1 class="fw-bold mb-3">$Name</h1>

        <p class="mb-2"><strong>Stok :</strong> $Stock</p>
        
      <strong style="display: block; margin-bottom: 5px;">Deskripsi :</strong>
        <div class="mb-4" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
            <% if $Description %>
                $Description
            <% else %>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur fuga fugit aperiam maxime numquam dolore.
            <% end_if %>
        </div>

        <!-- Price -->
        <div class="mb-3">
          <% if $hasDiscount %>
            <span class="text-muted text-decoration-line-through me-2">$FormattedPrice</span>
            <span class="fw-bold fs-4" style="color:#c4965c;">$FormattedDiscountPrice</span>
          <% else %>
            <span class="fw-bold fs-4" style="color:#c4965c;">$FormattedPrice</span>
          <% end_if %>
        </div>

        <!-- Quantity + Cart -->
        <% if $isInStock %>
        <div class="d-flex align-items-center">
          <strong class="me-5">Jumlah :</strong>
                    
          <div class="input-group me-2 rounded-pill border overflow-hidden" style="width: 130px;">
            <button class="btn btn-outline-secondary border-0" type="button" onclick="changeQuantity(-1)">-</button>
            <input type="number" id="quantity" class="form-control text-center border-0" value="1" min="1" max="99" readonly>
            <button class="btn btn-outline-secondary border-0" type="button" onclick="changeQuantity(1)">+</button>
          </div>

          <button class="btn text-white fw-bold px-4 rounded-pill me-2" style="background-color: #c4965c;" onclick="addToCart()">keranjang</button>
          <button class="btn btn-outline-secondary rounded-pill" title="Add to Wishlist"><i class="far fa-heart"></i></button>
        </div>
        <% else %>
        <div class="alert alert-warning mt-3">
          <strong>Out of Stock</strong> - This product is currently unavailable
        </div>
        <% end_if %>

      </div>
    </div>
    <% end_with %>
  </div>
</div>


<div class="container">
  <hr class="my-5">

<div class="related-products mt-5">
        <h3 class="fw-bold mb-4">Produk Lainnya</h3>
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