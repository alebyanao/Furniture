<!-- Top Bar -->
<% if $SiteConfig.getField('TopBarEnabled') %>
    <div class="sticky-top">
      <div class="top-bar w-150">
        <div class="text-white small py-1 d-flex justify-content-center align-items-center px-4" style="background-color: #b78b5c">
          <div class="text-uppercase position-relative" style="transform: translateX(-20px);">
            $SiteConfig.getField('TopBarText')
          </div>
        </div>
      </div>
      
      <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-flex justify-content-between px-3 px-md-5">
        <% else %>
              <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-flex justify-content-between px-3 px-md-5">
        <% end_if %>
        <div class="container-fluid">
          <div class="row w-100">
            <div class="col-12 col-md-9 mx-auto d-flex justify-content-between align-items-center">

              <!-- Logo dari SiteConfig -->
              <img src="$SiteConfig.FooterLogo.URL" alt="$SiteConfig.FooterLogo.Title" class="mb-2 img-fluid" style="max-height: 40px;">

              <!-- Tombol toggle -->
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
              </button>
              
              <!-- Menu dari CMS -->
              <div class="collapse navbar-collapse justify-content-center text-dark fw-bold" id="navbarNav">
                  <ul class="navbar-nav gap-3 horizontal-nav">
                      <li class="nav-item">
                        <a class="nav-link" href="$BaseHref/home" title="home">Home</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="$BaseHref/shop" title="shop">Shop</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="$BaseHref/blog" title="blog">Blog</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="$BaseHref/contact" title="contact">Contact</a>
                      </li>
                  </ul>
              </div>

              <!-- Icon kanan -->
              <div class="d-flex align-items-center gap-3 text-dark">

                <!-- Wishlist -->
                <% if $IsLoggedIn %>
                  <a href="$BaseHref/wishlist" class="position-relative text-decoration-none">
                    <i class="bi bi-heart" style="cursor: pointer; font-size: 1.5rem; color: #000000;"></i>
                    <% if $WishlistCount > 0 %>
                      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                        style="background-color: #b78b5c; color: white; font-size: 0.7rem;">
                        $WishlistCount
                      </span>
                    <% end_if %>
                  </a>
                <% else %>
                  <a href="$BaseHref/auth/login" class="position-relative text-decoration-none">
                    <i class="bi bi-heart" style="cursor: pointer; font-size: 1.5rem; color: #000000;"></i>
                  </a>
                <% end_if %>

                <!-- Cart -->
                 <% if $IsLoggedIn %>
                  <a href="$BaseHref/cart" class="position-relative text-decoration-none">
                    <i class="bi bi-cart" style="cursor: pointer; font-size: 1.5rem; color: #000000;"></i>
                    <% if $CartCount > 0 %>
                      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                        style="background-color: #b78b5c; color: white; font-size: 0.7rem;">
                        $CartCount
                      </span>
                    <% end_if %>
                  </a>
                <% else %>
                  <a href="$BaseHref/auth/login" class="position-relative text-decoration-none">
                    <i class="bi bi-cart" style="cursor: pointer; font-size: 1.5rem; color: #000000;"></i>
                  </a>
                <% end_if %>
                <%-- <a href="$BaseHref/cart" class="position-relative text-decoration-none text-dark">
                  <i class="bi bi-cart fs-4"></i>
                  <span class="cart-counter position-absolute top-0 start-100 translate-middle badge rounded-pill"
                    style="background-color: #b78b5c; color: white; font-size: 0.7rem;">
                    $CartCount
                  </span>
                </a> --%>

                <!-- Login & register -->
                <div class="dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button"
                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                    <% if $IsLoggedIn %>
                      <span class="d-none d-sm-inline">$CurrentUser.FirstName</span>
                    <% else %>
                      <span class="d-none d-sm-inline">Akun</span>
                    <% end_if %>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <% if $IsLoggedIn %>
                      <li><a class="dropdown-item" href="$BaseHref/profile"><i class="bi bi-person me-2"></i>Profil</a></li>
                      <li><a class="dropdown-item" href="$BaseHref/order"><i class="bi bi-box me-2"></i>Pesanan</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="$BaseHref/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    <% else %>
                      <li><a class="dropdown-item" href="$BaseHref/auth/login"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</a></li>
                      <li><a class="dropdown-item" href="$BaseHref/auth/register"><i class="bi bi-person-plus me-2"></i>Daftar</a></li>
                    <% end_if %>
                  </ul>
                </div>

              </div>

              </div>
            </div>
          </div>
        </div>
      </nav>
    </div>
<% if $SiteConfig.getField('TopBarEnabled') %>
  </div>
<% end_if %>