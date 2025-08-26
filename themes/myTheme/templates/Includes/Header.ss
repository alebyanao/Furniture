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

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <% else %>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <% end_if %>

    <div class="container">

      <!-- Logo dari SiteConfig -->
      <img src="$SiteConfig.FooterLogo.URL" alt="$SiteConfig.FooterLogo.Title" class="mb-2 img-fluid" style="max-height: 40px;">

      <!-- Tombol toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Menu dari CMS -->
      <div class="collapse navbar-collapse justify-content-center text-dark fw-bold" id="navbarNav">
        <ul class="navbar-nav gap-3">
          <% loop $Menu(1) %>
            <% if $MenuTitle != 'Cart' %>
              <li class="nav-item">
                <a class="nav-link $LinkingMode" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
              </li>
            <% end_if %>
          <% end_loop %>
        </ul>
      </div>

      <!-- Icon kanan -->
      <div class="d-flex align-items-center gap-3 text-dark">
        <i class="bi bi-person position-relative"></i>
        <i class="bi bi-heart position-relative">
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #b78b5c; color: white;">3</span>
        </i>
        <i class="position-relative">
          <a class="nav-link cart-icon" href="/cartpage/">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="m1 1 4 4 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="cart-counter">0</span>
          </a>
        </i>

      </div>

    </div>
  </nav>
</div>
