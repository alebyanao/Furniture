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
            <li class="nav-item">
              <a class="nav-link $LinkingMode" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
            </li>
          <% end_loop %>
        </ul>
      </div>

      <!-- Icon kanan -->
      <div class="d-flex align-items-center gap-3 text-dark">
        <i class="bi bi-person position-relative"></i>
        <i class="bi bi-heart position-relative">
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #b78b5c; color: white;">3</span>
        </i>
        <i class="bi bi-cart3 position-relative">
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #b78b5c; color: white;">12</span>
        </i>
      </div>

    </div>
  </nav>
</div>
