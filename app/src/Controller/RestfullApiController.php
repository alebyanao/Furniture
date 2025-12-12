<?php

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Security\Permission;

class RestfullApiController extends Controller
{
    private static $url_segment = 'api';

    private static $allowed_actions = [
        //login
        'index',
        'login',
        'register',
        'logout',
        'googleAuth',
        'forgotpassword',
        'updatePassword',
        'resetpassword',
        'member',
        'siteconfig',
        'product',
        'products',
        //cart
        'cart',
        'addToCart',
        'updateCartItem',
        'removeFromCart',
        'clearCart',
        //wishlist
        'wishlists',
        'addToWishlists',
        'removeFromWishlists',
        'checkWishlist', 
        'toggleWishlist',
    ];

    private static $url_handlers = [
        'login' => 'login',
        'register' => 'register',
        'logout' => 'logout',
        'google-auth' => 'googleAuth',
        'forgotpassword' => 'forgotpassword',
        'member/password' => 'updatePassword',
        'resetpassword' => 'resetpassword',
        'member' => 'member',
        'siteconfig' => 'siteconfig',
        'product/$ID' => 'product',
        'products' => 'products',
        '' => 'index',
        //cart
        'cart/add' => 'addToCart',
        'cart/update/$ID!' => 'updateCartItem',
        'cart/remove/$ID!' => 'removeFromCart',
        'cart/clear' => 'clearCart',
        'cart' => 'cart',
        // wishlist
        'wishlists/add' => 'addToWishlists',
        'wishlists/remove/$ID!' => 'removeFromWishlists',
        'wishlists/check/$ID!' => 'checkWishlist',
        'wishlists/toggle' => 'toggleWishlist', 
        'wishlists' => 'wishlists', 
         
    ];

    //helper
    private function requireAuth()
    {
        $member = Security::getCurrentUser();

        if (!$member) {
            return $this->jsonResponse(['error' => 'Authentication required'], 401);
        }

        return $member;
    }
    // index
    public function index(HTTPRequest $request)
    {
        return $this->jsonResponse([
            'message' => 'SilverStripe Furniture API',
            'status' => 'running',
            'endpoints' => [
                'authentication' => [
                    'POST /api/login' => 'Login user',
                    'POST /api/register' => 'Register user',
                    'POST /api/logout' => 'Logout user',
                    'POST /api/google-auth' => 'Google authentication',
                    'POST /api/forgotpassword' => 'Forgot password',
                    'GET /api/siteconfig' => 'Get site configuration',
                ],
                'member' => [
                    'GET /api/member' => 'Get current member profile',
                    'PUT /api/member' => 'Update member profile',
                    'PUT /api/member/password' => 'Update member password',
                ],
                 'catalog' => [
                    'GET /api/siteconfig' => 'Get site configuration',
                    // 'GET /api/popupad' => 'Get popup ads',
                    'GET /api/category' => 'Get categories',
                    'GET /api/products' => 'Get all products (with filters)',
                    'GET /api/product/{id}' => 'Get product detail',
                ],
                'cart' => [
                  'GET /api/cart' => 'Get cart items',
                    'POST /api/cart/add' => 'Add item to cart',
                    'PUT /api/cart/update/{id}' => 'Update cart item quantity',
                    'DELETE /api/cart/remove/{id}' => 'Remove item from cart',
                    'DELETE /api/cart/clear' => 'Clear all cart items',
                ],
            ]
        ]);
    }
    private function getCompanyEmailSafe(?SiteConfig $siteConfig = null): string
    {
        $siteConfig = $siteConfig ?: SiteConfig::current_site_config();

        if (!empty($siteConfig->CompanyEmail) && filter_var($siteConfig->CompanyEmail, FILTER_VALIDATE_EMAIL)) {
            return $siteConfig->CompanyEmail;
        }
        if (!empty($siteConfig->Email) && filter_var($siteConfig->Email, FILTER_VALIDATE_EMAIL)) {
            return $siteConfig->Email;
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'example.com';
        return 'noreply@' . preg_replace('/^www\./', '', $host);
    }
    public function login(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return $this->jsonResponse(['error' => 'Email dan password wajib diisi'], 400);
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member || !password_verify($password, $member->Password)) {
            return $this->jsonResponse(['error' => 'Email atau password salah'], 401);
        }

        if (!$member->IsVerified) {
            return $this->jsonResponse(['error' => 'Akun belum diverifikasi'], 403);
        }

        Injector::inst()->get(IdentityStore::class)->logIn($member, true);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id' => $member->ID,
                'email' => $member->Email,
                'first_name' => $member->FirstName,
                'surname' => $member->Surname,
                'is_verified' => $member->IsVerified
            ]
        ]);
    }
    public function register(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $firstName = $data['first_name'] ?? '';
        $surname = $data['surname'] ?? '';

        if (!$email || !$password || !$firstName) {
            return $this->jsonResponse(['error' => 'Email, password, dan nama depan wajib diisi'], 400);
        }

        if (strlen($password) < 8) {
            return $this->jsonResponse(['error' => 'Password minimal 8 karakter'], 400);
        }

        if (Member::get()->filter('Email', $email)->exists()) {
            return $this->jsonResponse(['error' => 'Email sudah terdaftar'], 400);
        }

        $member = Member::create();
        $member->FirstName = $firstName;
        $member->Surname = $surname;
        $member->Email = $email;
        $member->VerificationToken = bin2hex(random_bytes(32));
        $member->IsVerified = false;
        $member->write();
        $member->addToGroupByCode('site-users');
        $member->changePassword($password);

        $ngrokURL = Environment::getEnv('NGROK_URL');
        $verifyLink = rtrim($ngrokURL, '/') . '/verify?token=' . $member->VerificationToken;
        $siteConfig = SiteConfig::current_site_config();
        $companyEmail = $this->getCompanyEmailSafe($siteConfig);

        Email::create()
            ->setTo($member->Email)
            ->setFrom($companyEmail)
            ->setSubject('Verifikasi Akun Anda')
            ->setBody("
                <p>Halo {$member->FirstName},</p>
                <p>Klik tautan di bawah ini untuk memverifikasi akun Anda:</p>
                <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                <p>Terima kasih,<br>{$siteConfig->Title}</p>
            ")
            ->send();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Pendaftaran berhasil. Silakan cek email untuk verifikasi.'
        ], 201);
    }
    public function googleAuth(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $displayName = $data['display_name'] ?? '';

        if (!$email) {
            return $this->jsonResponse(['error' => 'Email wajib diisi'], 400);
        }

        $nameParts = explode(' ', $displayName, 2);
        $firstName = $nameParts[0] ?? '';
        $surname = $nameParts[1] ?? '';

        $member = Member::get()->filter('Email', $email)->first();

        if (!$member) {
            $member = Member::create();
            $member->FirstName = $firstName;
            $member->Surname = $surname;
            $member->Email = $email;
            $member->IsVerified = true;
            $member->write();
            $member->addToGroupByCode('site-users');
            $member->changePassword(bin2hex(random_bytes(16)));
        }

        Injector::inst()->get(IdentityStore::class)->logIn($member, false);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Google login berhasil',
            'user' => [
                'id' => $member->ID,
                'email' => $member->Email,
                'first_name' => $member->FirstName,
                'surname' => $member->Surname,
            ]
        ]);
    }
    public function logout(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if ($member) {
            Injector::inst()->get(IdentityStore::class)->logOut($request);
        }

        return $this->jsonResponse(['success' => true, 'message' => 'Logout berhasil']);
    }
    public function forgotpassword(HTTPRequest $request)

    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = trim($data['email'] ?? '');

        if (!$email) {
            return $this->jsonResponse(['error' => 'Email wajib diisi'], 422);
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Email tidak ditemukan'], 404);
        }

        $token = bin2hex(random_bytes(32));
        $member->ResetPasswordToken = $token;
        $member->ResetPasswordExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $member->write();

        $ngrokURL = Environment::getEnv('NGROK_URL');
        $siteConfig = SiteConfig::current_site_config();
        $fromEmail = $this->getCompanyEmailSafe($siteConfig);
        $resetLink = rtrim($ngrokURL, '/') . '/auth/resetpassword?token=' . $token;

        Email::create()
            ->setTo($member->Email)
            ->setFrom($fromEmail)
            ->setSubject('Reset Password Akun Anda')
            ->setBody("
                <p>Halo {$member->FirstName},</p>
                <p>Klik tautan berikut untuk mengatur ulang password Anda:</p>
                <p><a href='{$resetLink}'>{$resetLink}</a></p>
                <p>Tautan ini berlaku selama 1 jam.</p>
            ")
            ->send();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda.'
        ]);
    }

    public function resetpassword(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $token = $data['token'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (!$token || !$newPassword) {
            return $this->jsonResponse(['error' => 'Token dan password wajib diisi'], 400);
        }

        if (strlen($newPassword) < 8) {
            return $this->jsonResponse(['error' => 'Password minimal 8 karakter'], 400);
        }

        $member = Member::get()->filter('ResetPasswordToken', $token)->first();
        
        if (!$member) {
            return $this->jsonResponse(['error' => 'Token tidak valid'], 404);
        }

        if (strtotime($member->ResetPasswordExpiry) < time()) {
            return $this->jsonResponse(['error' => 'Token sudah kadaluarsa'], 400);
        }

        $member->changePassword($newPassword);
        $member->ResetPasswordToken = null;
        $member->ResetPasswordExpiry = null;
        $member->write();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }

    public function updatePassword(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getBody(), true);
        $oldPassword = $data['old_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (!$oldPassword || !$newPassword) {
            return $this->jsonResponse(['error' => 'Password lama dan baru wajib diisi'], 400);
        }

        if (!password_verify($oldPassword, $member->Password)) {
            return $this->jsonResponse(['error' => 'Password lama salah'], 403);
        }

        if (strlen($newPassword) < 8) {
            return $this->jsonResponse(['error' => 'Password baru minimal 8 karakter'], 400);
        }

        $member->changePassword($newPassword);

        return $this->jsonResponse(['success' => true, 'message' => 'Password berhasil diperbarui']);
    }
    public function member(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $this->jsonResponse([
            'id' => $member->ID,
            'email' => $member->Email,
            'first_name' => $member->FirstName,
            'surname' => $member->Surname,
            'is_verified' => $member->IsVerified,
            'membership_tier' => $member->MembershipTierName,
            'total_transactions' => $member->getFormattedTotalTransactions()
        ]);
    }
    public function siteconfig(HTTPRequest $request)
    {
        $config = SiteConfig::current_site_config();

        return $this->jsonResponse([
            'title' => $config->Title,
            'tagline' => $config->Tagline,
            'company_email' => $this->getCompanyEmailSafe($config),
            'phone' => $config->Phone,
            'address' => $config->Address,
        ]);
    }
    private function jsonResponse($data, $status = 200)
    {
        $response = new HTTPResponse(json_encode($data, JSON_UNESCAPED_UNICODE), $status);
        $response->addHeader('Content-Type', 'application/json; charset=utf-8');
        $response->addHeader('Access-Control-Allow-Origin', '*');
        $response->addHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->addHeader('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
    // product
    public function product(HTTPRequest $request)
    {
        if (!$request->isGET()) {
            return $this->jsonResponse(['error' => 'Only GET method allowed'], 405);
        }

        $id = $request->param('ID');
        if (!$id || !is_numeric($id)) {
            return $this->jsonResponse(['error' => 'Invalid product ID'], 400);
        }

        $product = Product::get()->byID($id);
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        $user = Security::getCurrentUser();

        // Basic data
        $data = [
            'id' => $product->ID,
            'name' => $product->Name,
            'description' => $product->Description,
            'price' => (float) $product->Price,
            'discount' => (float) $product->Discount,
            'price_after_discount' => (float) $product->getDiscountPrice(),
            'discount_percentage' => $product->hasDiscount()
                ? round(($product->Discount / $product->Price) * 100, 2)
                : 0,
            'stock' => $product->Stock,
            'stock_status' => $product->getStockStatus(),
            'categories' => $product->getCategoryNames(),
            'first_category' => $product->getFirstCategoryName(),
            'rating' => $product->getAverageRating(),
            'weight' => $product->Weight,
            'created' => $product->Created,
            'updated' => $product->LastEdited,
            'image_url' => $product->Image()->exists()
                ? $product->Image()->getAbsoluteURL()
                : null,
        ];
        if (class_exists(Review::class)) {
            $reviews = Review::get()->filter("ProductID", $product->ID);
            $data['reviews'] = [];

            foreach ($reviews as $review) {
                $data['reviews'][] = [
                    'id' => $review->ID,
                    'author' => $review->Member()->FirstName ?? 'Anonymous',
                    'rating' => $review->Rating,
                    'message' => $review->Message,
                    'createdAt' => $review->Created,
                    'showName' => $review->ShowName ?? false,
                ];
            }
        }
        if (class_exists(Wishlist::class)) {
            $data['wishlists_count'] = Wishlist::get()
                ->filter('ProductID', $product->ID)
                ->count();
            $data['in_wishlist'] = $user
                ? Wishlist::get()->filter([
                    'ProductID' => $product->ID,
                    'MemberID' => $user->ID
                ])->exists()
                : false;
        }
        if (class_exists(CartItem::class)) {
            $data['in_cart'] = $user
                ? CartItem::get()->filter([
                    'ProductID' => $product->ID,
                    'MemberID' => $user->ID,
                ])->exists()
                : false;
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $data
        ]);
    }

    public function products(HTTPRequest $request)
    {
        $products = Product::get()
            ->filter('Stock:GreaterThan', 0)
            ->sort('Created', 'DESC');

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'ID' => $product->ID,
                'Name' => $product->Name,
                'Price' => $product->Price,
                'Discount' => $product->Discount,
                'FinalPrice' => $product->Price - ($product->Price * $product->Discount / 100),
                'Stock' => $product->Stock,
                'Image' => $product->Image()->exists() ? $product->Image()->AbsoluteLink() : null,
                'Categories' => $product->Categories()->column('Name')
            ];
        }

        $response = HTTPResponse::create(json_encode($data));
        $response->addHeader('Content-Type', 'application/json');
        return $response;
    }
    // cart
    public function cart(HTTPRequest $request)
    {
        if (!$request->isGET()) {
            return $this->jsonResponse(['error' => 'Only GET method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $cartItems = CartItem::get()->filter('MemberID', $member->ID);

        $items = [];
        $subtotal = 0;
        $totalItems = 0;
        $totalWeight = 0;                        

        foreach ($cartItems as $item) {
            $product = $item->Product();

            $items[] = [
                'id' => $item->ID,
                'product_id' => $product->ID,
                'product_name' => $product->Name,
                'quantity' => $item->Quantity,
                'price' => (float) $product->getDiscountPrice(),
                'original_price' => (float) $product->Price,
                'description' => $product->Description,
                'subtotal' => (float) $item->getSubtotal(),
                'weight' => $product->Weight,
                'total_weight' => $product->Weight * $item->Quantity,
                'stock' => $product->Stock,
                'rating' => $product->getAverageRating(),
                'image_url' => $product->Image()->exists() ? $product->Image()->getAbsoluteURL() : null,
            ];

            $subtotal += $item->getSubtotal();
            $totalItems += $item->Quantity;
            $totalWeight += ($product->Weight * $item->Quantity);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => [
                'items' => $items,
                'summary' => [
                    'total_items' => $totalItems,
                    'total_weight' => $totalWeight,
                    'subtotal' => $subtotal,
                    'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, '.', '.'),
                ]
            ]
        ]);
    }
    public function addToCart(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $data = json_decode($request->getBody(), true);

        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            return $this->jsonResponse(['error' => 'product_id and quantity are required'], 400);
        }

        $productID = (int) $data['product_id'];
        $quantity = (int) $data['quantity'];

        if ($quantity <= 0) {
            return $this->jsonResponse(['error' => 'Quantity must be greater than 0'], 400);
        }

        $product = Product::get()->byID($productID);
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        // Check stock
        if ($product->Stock < $quantity) {
            return $this->jsonResponse(['error' => 'Insufficient stock'], 400);
        }

        // Check if item already in cart
        $cartItem = CartItem::get()->filter([
            'MemberID' => $member->ID,
            'ProductID' => $productID
        ])->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->Quantity + $quantity;

            if ($product->Stock < $newQuantity) {
                return $this->jsonResponse(['error' => 'Insufficient stock'], 400);
            }

            $cartItem->Quantity = $newQuantity;
            $cartItem->write();
        } else {
            // Create new cart item
            $cartItem = CartItem::create();
            $cartItem->MemberID = $member->ID;
            $cartItem->ProductID = $productID;
            $cartItem->Quantity = $quantity;
            $cartItem->write();
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product added to cart',
            'data' => [
                'cart_item_id' => $cartItem->ID,
                'quantity' => $cartItem->Quantity,
            ]
        ], 201);
    }
    public function updateCartItem(HTTPRequest $request)
    {
        if (!$request->isPUT()) {
            return $this->jsonResponse(['error' => 'Only PUT method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $cartItemID = $request->param('ID');
        $data = json_decode($request->getBody(), true);

        if (!isset($data['quantity'])) {
            return $this->jsonResponse(['error' => 'quantity is required'], 400);
        }

        $quantity = (int) $data['quantity'];

        if ($quantity <= 0) {
            return $this->jsonResponse(['error' => 'Quantity must be greater than 0'], 400);
        }

        $cartItem = CartItem::get()->filter([
            'ID' => $cartItemID,
            'MemberID' => $member->ID
        ])->first();

        if (!$cartItem) {
            return $this->jsonResponse(['error' => 'Cart item not found'], 404);
        }

        $product = $cartItem->Product();

        if ($product->Stock < $quantity) {
            return $this->jsonResponse(['error' => 'Insufficient stock'], 400);
        }

        $cartItem->Quantity = $quantity;
        $cartItem->write();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Cart item updated',
            'data' => [
                'cart_item_id' => $cartItem->ID,
                'quantity' => $cartItem->Quantity,
            ]
        ]);
    }
    public function removeFromCart(HTTPRequest $request)
    {
        if (!$request->isDELETE()) {
            return $this->jsonResponse(['error' => 'Only DELETE method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $cartItemID = $request->param('ID');

        $cartItem = CartItem::get()->filter([
            'ID' => $cartItemID,
            'MemberID' => $member->ID
        ])->first();

        if (!$cartItem) {
            return $this->jsonResponse(['error' => 'Cart item not found'], 404);
        }

        $cartItem->delete();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }
    public function clearCart(HTTPRequest $request)
    {
        if (!$request->isDELETE()) {
            return $this->jsonResponse(['error' => 'Only DELETE method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $cartItems = CartItem::get()->filter('MemberID', $member->ID);

        foreach ($cartItems as $item) {
            $item->delete();
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
    // wishlist
    public function wishlists(HTTPRequest $request)
    {
        if (!$request->isGET()) {
            return $this->jsonResponse(['error' => 'Only GET method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $wishlists = Wishlist::get()->filter('MemberID', $member->ID);

        $items = [];
        foreach ($wishlists as $wishlist) { 
            $product = $wishlist->Product();

            $items[] = [
                'id' => $wishlist->ID, 
                'product_id' => $product->ID,
                'product_name' => $product->Name,
                'price' => (float) $product->getDiscountPrice(),
                'original_price' => (float) $product->Price,
                'description' => $product->Description,
                'stock' => $product->Stock, 
                'rating' => $product->getAverageRating(),
                'image_url' => $product->Image()->exists() ? $product->Image()->getAbsoluteURL() : null,
            ];
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $items,
            'total' => count($items)
        ]);
    }
    public function addToWishlists(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $data = json_decode($request->getBody(), true);

        if (!isset($data['product_id'])) {
            return $this->jsonResponse(['error' => 'product_id is required'], 400);
        }

        $productID = (int) $data['product_id'];

        $product = Product::get()->byID($productID);
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        // Check if already in wishlist
        $existing = Wishlist::get()->filter([
            'MemberID' => $member->ID,
            'ProductID' => $productID
        ])->first();

        if ($existing) {
            return $this->jsonResponse(['error' => 'Product already in wishlist'], 400);
        }

        $wishlist = Wishlist::create();
        $wishlist->MemberID = $member->ID;
        $wishlist->ProductID = $productID;
        $wishlist->write();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product added to wishlist',
            'data' => [
                'wishlist_id' => $wishlist->ID
            ]
        ], 201);
    }
    public function removeFromWishlists(HTTPRequest $request)
    {
        if (!$request->isDELETE()) {
            return $this->jsonResponse(['error' => 'Only DELETE method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $wishlistID = $request->param('ID'); // ✅ Variable ini sudah benar

        $wishlist = Wishlist::get()->filter([
            'ID' => $wishlistID, // ✅ Perbaiki dari $favoriteID
            'MemberID' => $member->ID
        ])->first();

        if (!$wishlist) {
            return $this->jsonResponse(['error' => 'Wishlist not found'], 404);
        }

        $wishlist->delete();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Removed from wishlist' // ✅ Perbaiki dari Wishlists
        ]);
    }
    public function checkWishlist(HTTPRequest $request)
    {
        if (!$request->isGET()) {
            return $this->jsonResponse(['error' => 'Only GET method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $productID = $request->param('ID');

        $wishlist = Wishlist::get()->filter([
            'MemberID' => $member->ID,
            'ProductID' => $productID
        ])->first();

        return $this->jsonResponse([
            'success' => true,
            'in_wishlist' => $wishlist ? true : false,
            'wishlist_id' => $wishlist ? $wishlist->ID : null
        ]);
    }
    public function toggleWishlist(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST method allowed'], 405);
        }

        $member = $this->requireAuth();
        if ($member instanceof HTTPResponse)
            return $member;

        $data = json_decode($request->getBody(), true);

        if (!isset($data['product_id'])) {
            return $this->jsonResponse(['error' => 'product_id is required'], 400);
        }

        $productID = (int) $data['product_id'];

        $product = Product::get()->byID($productID);
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        // Check if already in wishlist
        $existing = Wishlist::get()->filter([
            'MemberID' => $member->ID,
            'ProductID' => $productID
        ])->first();

        if ($existing) {
            // Remove from wishlist
            $existing->delete();
            return $this->jsonResponse([
                'success' => true,
                'action' => 'removed',
                'message' => 'Product removed from wishlist',
                'in_wishlist' => false
            ]);
        } else {
            // Add to wishlist
            $wishlist = Wishlist::create();
            $wishlist->MemberID = $member->ID;
            $wishlist->ProductID = $productID;
            $wishlist->write();

            return $this->jsonResponse([
                'success' => true,
                'action' => 'added',
                'message' => 'Product added to wishlist',
                'in_wishlist' => true,
                'wishlist_id' => $wishlist->ID
            ], 201);
        }
    }
}