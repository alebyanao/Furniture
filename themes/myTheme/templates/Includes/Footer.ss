<!-- Footer Template for SilverStripe using FooterSiteConfigExtension data -->
<footer class="bg-dark text-white pt-5">
  <div class="container">
    <div class="row gy-5">
      
      <!-- Logo + Deskripsi -->
      <div class="col-md-3">
        <% if $SiteConfig.FooterLogo %>
          <img src="$SiteConfig.FooterLogo.URL" alt="$SiteConfig.FooterLogo.Title" class="mb-3 img-fluid" style="max-width: 140px;">
        <% else %>
          <img src="$ThemeDir/images/logo.jpg" alt="$SiteConfig.Title" class="mb-3 img-fluid" style="max-width: 140px;">
        <% end_if %>
        
        <%-- <% if $SiteConfig.ContactDescription %>
          <p class="text-light">$SiteConfig.ContactDescription</p>
        <% else %>
			
          <p class="text-light">It helps designers plan out where the content will sit, the content to be written and approved.</p>
        <% end_if %> --%>
        
        <!-- Social Media Links -->
        <% if $SiteConfig.getFooterSocialMedia %>
          <div class="d-flex gap-3">
            <% loop $SiteConfig.getFooterSocialMedia %>
              <a href="$URL" target="_blank" rel="noopener" title="$Title">
                <% if $CustomIcon %>
                  <img src="$CustomIcon.URL" alt="$AltText" style="width: 20px; height: 20px;" class="text-white">
                <% else_if $IconClass %>
                  <i class="$IconClass text-white"></i>
                <% else %>
                  <i class="bi bi-link text-white"></i>
                <% end_if %>
              </a>
            <% end_loop %>
          </div>
        <% else %>
          <!-- Fallback social media icons -->
          <div class="d-flex gap-3">
            <a href="#"><i class="bi bi-facebook text-white"></i></a>
            <a href="#"><i class="bi bi-twitter text-white"></i></a>
            <a href="#"><i class="bi bi-linkedin text-white"></i></a>
            <a href="#"><i class="bi bi-instagram text-white"></i></a>
          </div>
        <% end_if %>
      </div>

      <!-- Services -->
      <div class="col-md-2">
        <h6 class="fw-semibold">Services</h6>
        <% if $SiteConfig.getFooterServices %>
          <ul class="list-unstyled small text-muted">
            <% loop $SiteConfig.getFooterServices %>
              <li><a href="$Link" class="text-decoration-none text-white" title="$Description">$Title</a></li>
            <% end_loop %>
          </ul>
        <% else %>
          <!-- Fallback services -->
          <ul class="list-unstyled small text-muted">
            <li><a href="#" class="text-decoration-none text-white">Log In</a></li>
            <li><a href="#" class="text-decoration-none text-white">Wishlist</a></li>
            <li><a href="#" class="text-decoration-none text-white">Return Policy</a></li>
            <li><a href="#" class="text-decoration-none text-white">Privacy policy</a></li>
            <li><a href="#" class="text-decoration-none text-white">Shopping FAQs</a></li>
          </ul>
        <% end_if %>
      </div>

      <!-- Company -->
      <div class="col-md-2">
        <h6 class="fw-semibold">Company</h6>
        <% if $SiteConfig.getFooterCompanyLinks %>
          <ul class="list-unstyled small text-muted">
            <% loop $SiteConfig.getFooterCompanyLinks %>
              <li><a href="$Link" class="text-decoration-none text-white">$Title</a></li>
            <% end_loop %>
          </ul>
        <% else %>
          <!-- Fallback company links -->
          <ul class="list-unstyled small text-muted">
            <li><a href="/" class="text-decoration-none text-white">Home</a></li>
            <li><a href="/about-us" class="text-decoration-none text-white">About us</a></li>
            <li><a href="/pages" class="text-decoration-none text-white">Pages</a></li>
            <li><a href="/blog" class="text-decoration-none text-white">Blog</a></li>
            <li><a href="/contact-us" class="text-decoration-none text-white">Contact us</a></li>
          </ul>
        <% end_if %>
      </div>

      <!-- Contact -->
      <div class="col-md-5">
        <h6 class="fw-semibold">Contact</h6>
        
        <!-- Contact Description -->
        <% if $SiteConfig.ContactDescription %>
          <p class="text-light mb-1">$SiteConfig.ContactDescription</p>
		  <div style="height: 32px;"></div>
        <% end_if %>

        <!-- Location Address -->
        <% if $SiteConfig.LocationAddress %>
          <div class="d-flex align-items-center mb-5">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px; min-height: 30px; background-color: #b78b5c; line-height: 1;">
              <i class="bi bi-geo-alt-fill text-white"></i>
            </div>
            <% if $SiteConfig.LocationMapURL %>
              <a href="$SiteConfig.LocationMapURL" target="_blank" class="small text-white text-decoration-none">
                $SiteConfig.LocationAddress.RAW
              </a>
            <% else %>
              <span class="small text-light">$SiteConfig.LocationAddress.RAW</span>
            <% end_if %>
          </div>
        <% else %>
          <!-- Fallback location -->
          <div class="d-flex align-items-center mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-geo-fill text-white"></i>
            </div>
            <span class="small">711–2880 Nulla St.</span>
          </div>
        <% end_if %>

        <!-- Phone and Working Hours -->
        <% if $SiteConfig.ContactPhone %>
          <div class="d-flex align-items-center mb-5">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-telephone-fill text-white"></i>
            </div>
            <div>
              <span class="small d-block text-light">$SiteConfig.ContactPhone</span>
              <% if $SiteConfig.ContactWorkingHours %>
                <small class="text-light">$SiteConfig.ContactWorkingHours</small>
              <% end_if %>
            </div>
          </div>
        <% else %>
          <!-- Fallback phone -->
          <div class="d-flex align-items-center mb-5">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-telephone-fill text-white"></i>
            </div>
            <div>
              <span class="small d-block text-light">+964 742 44 763</span>
              <small class="text-light">Mon - Sat: 9 AM - 5 PM</small>
            </div>
          </div>
        <% end_if %>
      </div>
    </div>

    <!-- Garis -->
    <hr class="border-light my-4">

    <!-- Bottom -->
    <div class="d-md-flex justify-content-between align-items-center text-center pb-4">
      <!-- Copyright -->
      <div class="text-light small mb-2 mb-md-0">
        © All Copyright {$Now.Year} by $SiteConfig.Title
      </div>
      
      <!-- Payment Methods -->
      <% if $SiteConfig.getFooterPaymentMethods %>
        <div class="d-flex gap-2 justify-content-center mb-2 mb-md-0">
          <% loop $SiteConfig.getFooterPaymentMethods %>
            <% if $PaymentImage %>
              <img src="$PaymentImage.URL" height="30" alt="$AltText" title="$Title">
            <% end_if %>
          <% end_loop %>
        </div>
      <% else %>
        <!-- Fallback payment methods -->
        <div class="d-flex gap-2 justify-content-center mb-2 mb-md-0">
          <img src="$ThemeDir/images/payoneer.png" height="30" alt="Payoneer">
          <img src="$ThemeDir/images/mastercard.png" height="30" alt="MasterCard">
          <img src="$ThemeDir/images/paypal.png" height="30" alt="PayPal">
        </div>
      <% end_if %>
      
      <!-- Footer Links -->
      <div class="small text-white">
        <% loop $Menu(2) %>
          <% if $First %>
            <a href="$Link" class="text-white text-decoration-none">$MenuTitle</a>
          <% else %>
            | <a href="$Link" class="text-white text-decoration-none">$MenuTitle</a>
          <% end_if %>
        <% end_loop %>
        
        <!-- Default links if no menu items -->
        <% if not $Menu(2) %>
          <a href="/terms-conditions" class="text-white text-decoration-none">Terms & Condition</a> |
          <a href="/privacy-policy" class="text-white text-decoration-none">Privacy Policy</a>
        <% end_if %>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap Icons CDN (jika belum termasuk) -->
<% if not $BootstrapIconsLoaded %>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<% end_if %>