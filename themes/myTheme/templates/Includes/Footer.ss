<!-- Footer Template for SilverStripe using CustomSiteConfig data -->
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
        
        <!-- Footer Description -->
        <% if $SiteConfig.FooterDescription %>
          <p class="text-light mb-3 small">$SiteConfig.FooterDescription</p>
        <% end_if %>
        
        <!-- Social Media Links -->
        <div class="d-flex gap-3">
          <% if $SiteConfig.FacebookURL %>
            <a href="$SiteConfig.FacebookURL" target="_blank" rel="noopener" title="Facebook">
              <i class="bi bi-facebook text-white"></i>
            </a>
          <% end_if %>
          <% if $SiteConfig.InstagramURL %>
            <a href="$SiteConfig.InstagramURL" target="_blank" rel="noopener" title="Instagram">
              <i class="bi bi-instagram text-white"></i>
            </a>
          <% end_if %>
          <% if $SiteConfig.TwitterURL %>
            <a href="$SiteConfig.TwitterURL" target="_blank" rel="noopener" title="Twitter">
              <i class="bi bi-twitter text-white"></i>
            </a>
          <% end_if %>
          <% if $SiteConfig.LinkedInURL %>
            <a href="$SiteConfig.LinkedInURL" target="_blank" rel="noopener" title="LinkedIn">
              <i class="bi bi-linkedin text-white"></i>
            </a>
          <% end_if %>
          <% if $SiteConfig.YouTubeURL %>
            <a href="$SiteConfig.YouTubeURL" target="_blank" rel="noopener" title="YouTube">
              <i class="bi bi-youtube text-white"></i>
            </a>
          <% end_if %>
          <% if $SiteConfig.WhatsAppNumber %>
            <a href="https://wa.me/$SiteConfig.WhatsAppNumber" target="_blank" rel="noopener" title="WhatsApp">
              <i class="bi bi-whatsapp text-white"></i>
            </a>
          <% end_if %>
          
          <% if not $SiteConfig.FacebookURL && not $SiteConfig.InstagramURL && not $SiteConfig.TwitterURL && not $SiteConfig.LinkedInURL && not $SiteConfig.YouTubeURL && not $SiteConfig.WhatsAppNumber %>
            <!-- Fallback social media icons -->
            <a href="#"><i class="bi bi-facebook text-white"></i></a>
            <a href="#"><i class="bi bi-twitter text-white"></i></a>
            <a href="#"><i class="bi bi-linkedin text-white"></i></a>
            <a href="#"><i class="bi bi-instagram text-white"></i></a>
          <% end_if %>
        </div>
      </div>

      <!-- Services -->
      <div class="col-md-2">
        <h6 class="fw-semibold">Services</h6>
        <ul class="list-unstyled small text-muted">
          <% if $SiteConfig.Service1Title %>
            <li><a href="<% if $SiteConfig.Service1URL %>$SiteConfig.Service1URL<% else %>#<% end_if %>" class="text-decoration-none text-white">$SiteConfig.Service1Title</a></li>
          <% end_if %>
          <% if $SiteConfig.Service2Title %>
            <li><a href="<% if $SiteConfig.Service2URL %>$SiteConfig.Service2URL<% else %>#<% end_if %>" class="text-decoration-none text-white">$SiteConfig.Service2Title</a></li>
          <% end_if %>
          <% if $SiteConfig.Service3Title %>
            <li><a href="<% if $SiteConfig.Service3URL %>$SiteConfig.Service3URL<% else %>#<% end_if %>" class="text-decoration-none text-white">$SiteConfig.Service3Title</a></li>
          <% end_if %>
          <% if $SiteConfig.Service4Title %>
            <li><a href="<% if $SiteConfig.Service4URL %>$SiteConfig.Service4URL<% else %>#<% end_if %>" class="text-decoration-none text-white">$SiteConfig.Service4Title</a></li>
          <% end_if %>
          <% if $SiteConfig.Service5Title %>
            <li><a href="<% if $SiteConfig.Service5URL %>$SiteConfig.Service5URL<% else %>#<% end_if %>" class="text-decoration-none text-white">$SiteConfig.Service5Title</a></li>
          <% end_if %>
          
          <% if not $SiteConfig.Service1Title && not $SiteConfig.Service2Title && not $SiteConfig.Service3Title && not $SiteConfig.Service4Title && not $SiteConfig.Service5Title %>
            <!-- Fallback services -->
            <li><a href="$BaseHref/auth/login" class="text-decoration-none text-white">Log In</a></li>
            <li><a href="$BaseHref/wishlist" class="text-decoration-none text-white">Wishlist</a></li>
            <li><a href="#" class="text-decoration-none text-white">Return Policy</a></li>
            <li><a href="#" class="text-decoration-none text-white">Privacy policy</a></li>
            <li><a href="#" class="text-decoration-none text-white">Shopping FAQs</a></li>
          <% end_if %>
        </ul>
      </div>

      <!-- Company -->
      <div class="col-md-2">
        <h6 class="fw-semibold">Company</h6>
        <ul class="list-unstyled small text-muted">
          <% if $SiteConfig.AboutUsURL %>
            <li><a href="$SiteConfig.AboutUsURL" class="text-decoration-none text-white">About Us</a></li>
          <% end_if %>
          <% if $SiteConfig.PrivacyPolicyURL %>
            <li><a href="$SiteConfig.PrivacyPolicyURL" class="text-decoration-none text-white">Privacy Policy</a></li>
          <% end_if %>
          <% if $SiteConfig.TermsConditionsURL %>
            <li><a href="$SiteConfig.TermsConditionsURL" class="text-decoration-none text-white">Terms & Conditions</a></li>
          <% end_if %>
          <% if $SiteConfig.CareersURL %>
            <li><a href="$SiteConfig.CareersURL" class="text-decoration-none text-white">Careers</a></li>
          <% end_if %>
          <% if $SiteConfig.ContactUsURL %>
            <li><a href="$SiteConfig.ContactUsURL" class="text-decoration-none text-white">Contact Us</a></li>
          <% end_if %>
          
          <% if not $SiteConfig.AboutUsURL && not $SiteConfig.PrivacyPolicyURL && not $SiteConfig.TermsConditionsURL && not $SiteConfig.CareersURL && not $SiteConfig.ContactUsURL %>
            <!-- Fallback company links -->
            <li><a href="$BaseHref/home" class="text-decoration-none text-white">Home</a></li>
            <li><a href="$BaseHref/shop" class="text-decoration-none text-white">Shop</a></li>
            <li><a href="$BaseHref/blog" class="text-decoration-none text-white">Blog</a></li>
            <li><a href="$BaseHref/contact" class="text-decoration-none text-white">Contact us</a></li>
          <% end_if %>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-5">
        <h6 class="fw-semibold">Contact</h6>
        
        <!-- Contact Description -->
        <% if $SiteConfig.ContactDescription %>
          <p class="text-light mb-1">$SiteConfig.ContactDescription</p>
          <div style="height: 32px;"></div>
        <% end_if %>

        <!-- Company Address -->
        <% if $SiteConfig.CompanyAddress %>
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px; min-height: 30px; background-color: #b78b5c; line-height: 1;">
              <i class="bi bi-geo-alt-fill text-white"></i>
            </div>
            <% if $SiteConfig.CompanyMapURL %>
              <a href="$SiteConfig.CompanyMapURL" target="_blank" class="small text-white text-decoration-none">
                $SiteConfig.CompanyAddress.RAW
              </a>
            <% else %>
              <span class="small text-light">$SiteConfig.CompanyAddress.RAW</span>
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
        <% if $SiteConfig.CompanyPhone %>
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-telephone-fill text-white"></i>
            </div>
            <div>
              <span class="small d-block text-light">$SiteConfig.CompanyPhone</span>
              <% if $SiteConfig.CompanyWorkingHours %>
                <small class="text-light">$SiteConfig.CompanyWorkingHours</small>
              <% end_if %>
            </div>
          </div>
        <% else %>
          <!-- Fallback phone -->
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-telephone-fill text-white"></i>
            </div>
            <div>
              <span class="small d-block text-light">+964 742 44 763</span>
              <small class="text-light">Mon - Sat: 9 AM - 5 PM</small>
            </div>
          </div>
        <% end_if %>
        <!-- Email -->
        <% if $SiteConfig.CompanyEmail %>
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #b78b5c">
              <i class="bi bi-envelope-fill text-white"></i>
            </div>
            <div>
              <a href="mailto:$SiteConfig.CompanyEmail" class="small text-white text-decoration-none">$SiteConfig.CompanyEmail</a>
            </div>
          </div>
        <% end_if %>
        <!-- WhatsApp -->
        <%-- <% if $SiteConfig.WhatsAppNumber %>
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background-color: #25D366">
              <i class="bi bi-whatsapp text-white"></i>
            </div>
            <div>
              <a href="https://wa.me/$SiteConfig.WhatsAppNumber" target="_blank" class="small text-white text-decoration-none">WhatsApp Chat</a>
            </div>
          </div>
        <% end_if %> --%>
      </div>
    </div>

    <!-- Garis -->
    <hr class="border-light my-4">

    <!-- Bottom -->
    <div class="d-md-flex justify-content-between align-items-center text-center pb-4">
      <!-- Copyright -->
      <div class="text-light small mb-2 mb-md-0">
        <% if $SiteConfig.FooterCopyrightText %>
          © {$Now.Year} $SiteConfig.Title. $SiteConfig.FooterCopyrightText
        <% else %>
          © All Copyright {$Now.Year} by $SiteConfig.Title
        <% end_if %>
      </div>
      
      <!-- Payment Methods -->
      <div class="d-flex gap-2 justify-content-center mb-2 mb-md-0">
        <% if $SiteConfig.PaymentMethodImage1 %>
          <img src="$SiteConfig.PaymentMethodImage1.URL" height="30" alt="Payment Method">
        <% end_if %>
        <% if $SiteConfig.PaymentMethodImage2 %>
          <img src="$SiteConfig.PaymentMethodImage2.URL" height="30" alt="Payment Method">
        <% end_if %>
        <% if $SiteConfig.PaymentMethodImage3 %>
          <img src="$SiteConfig.PaymentMethodImage3.URL" height="30" alt="Payment Method">
        <% end_if %>
        <% if $SiteConfig.PaymentMethodImage4 %>
          <img src="$SiteConfig.PaymentMethodImage4.URL" height="30" alt="Payment Method">
        <% end_if %>
        <% if $SiteConfig.PaymentMethodImage5 %>
          <img src="$SiteConfig.PaymentMethodImage5.URL" height="30" alt="Payment Method">
        <% end_if %>
        
        <% if not $SiteConfig.PaymentMethodImage1 && not $SiteConfig.PaymentMethodImage2 && not $SiteConfig.PaymentMethodImage3 && not $SiteConfig.PaymentMethodImage4 && not $SiteConfig.PaymentMethodImage5 %>
          <!-- Fallback payment methods -->
          <img src="$ThemeDir/images/payoneer.png" height="30" alt="Payoneer">
          <img src="$ThemeDir/images/mastercard.png" height="30" alt="MasterCard">
          <img src="$ThemeDir/images/paypal.png" height="30" alt="PayPal">
        <% end_if %>
      </div>
      
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
          <% if $SiteConfig.TermsConditionsURL %>
            <a href="$SiteConfig.TermsConditionsURL" class="text-white text-decoration-none">Terms & Condition</a>
          <% else %>
            <a href="/terms-conditions" class="text-white text-decoration-none">Terms & Condition</a>
          <% end_if %>
          |
          <% if $SiteConfig.PrivacyPolicyURL %>
            <a href="$SiteConfig.PrivacyPolicyURL" class="text-white text-decoration-none">Privacy Policy</a>
          <% else %>
            <a href="/privacy-policy" class="text-white text-decoration-none">Privacy Policy</a>
          <% end_if %>
        <% end_if %>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap Icons CDN (jika belum termasuk) -->
<%-- <% if not $BootstrapIconsLoaded %>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<% end_if %> --%>