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
        //product
        'products',
        'product',
        'createProduct',
        'updateProduct',
        'deleteProduct',
        'productsByCategory',
        //wishlist
        'wishlist',
        'addToWishlist',
        'removeFromWishlist',
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
        '' => 'index',
        //product
        'products/$ID/DELETE' => 'deleteProduct',
        'products/$ID/PUT' => 'updateProduct',
        'products/$ID' => 'product',
        'products/category/$CategoryID' => 'productsByCategory',
        'products/POST' => 'createProduct',
        'products' => 'products',
        //wishlist
        'wishlist/add' => 'addToWishlist',
        'wishlist/remove/$ID!' => 'removeFromWishlist',
        'wishlist/check/$ID!' => 'checkWishlist',
        'wishlist/toggle/$ID!' => 'toggleWishlist',
        'wishlist' => 'wishlist',

    ];

    /* INDEX */
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

    public function products(HTTPRequest $request)
    {
        // Pagination
        $limit = (int)$request->getVar('limit') ?: 12;
        $offset = (int)$request->getVar('offset') ?: 0;
        
        // Base query
        $query = Product::get();
        
        // Filter by search keyword
        if ($search = $request->getVar('search')) {
            $query = $query->filterAny([
                'Name:PartialMatch' => $search,
                'Description:PartialMatch' => $search,
            ]);
        }
        
        // Filter by category
        if ($categoryId = $request->getVar('category')) {
            $query = $query->filter('Categories.ID', $categoryId);
        }
        
        // Filter by stock status
        if ($inStock = $request->getVar('in_stock')) {
            if ($inStock === '1' || $inStock === 'true') {
                $query = $query->filter('Stock:GreaterThan', 0);
            }
        }
        
        // Filter by price range
        if ($minPrice = $request->getVar('min_price')) {
            $query = $query->filter('Price:GreaterThanOrEqual', $minPrice);
        }
        if ($maxPrice = $request->getVar('max_price')) {
            $query = $query->filter('Price:LessThanOrEqual', $maxPrice);
        }
        
        // Sorting
        $sort = $request->getVar('sort') ?: 'Created';
        $order = $request->getVar('order') ?: 'DESC';
        $allowedSort = ['Name', 'Price', 'Created', 'Stock'];
        $allowedOrder = ['ASC', 'DESC'];
        
        if (in_array($sort, $allowedSort) && in_array($order, $allowedOrder)) {
            $query = $query->sort("$sort $order");
        }
        
        // Total count before pagination
        $total = $query->count();
        
        // Apply pagination
        $products = $query->limit($limit, $offset);
        
        // Format response
        $data = [];
        foreach ($products as $product) {
            $data[] = $this->formatProductData($product);
        }
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($data)
            ]
        ]);
    }

    /**
     * GET /api/products/{id}
     * Detail produk
     */
    public function product(HTTPRequest $request)
    {
        $id = $request->param('ID');
        
        if (!$id) {
            return $this->jsonResponse(['error' => 'Product ID required'], 400);
        }
        
        $product = Product::get()->byID($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }
    
        // Include reviews
        $reviews = [];
        foreach ($product->Review() as $review) {
            $reviews[] = [
                'id' => $review->ID,
                'rating' => $review->Rating,
                'comment' => $review->Comment,
                'member_name' => $review->Member()->FirstName,
                'created' => $review->Created,
            ];
        }
        
        return $this->jsonResponse([
            'success' => true,
            'data' => array_merge(
                $this->formatProductData($product, true),
                [
                    'reviews' => $reviews,
                ]
            )
        ]);
    }

    /**
     * GET /api/products/category/{categoryId}
     * Produk berdasarkan kategori
     */
    public function productsByCategory(HTTPRequest $request)
    {
        $categoryId = $request->param('CategoryID');
        
        if (!$categoryId) {
            return $this->jsonResponse(['error' => 'Category ID required'], 400);
        }
        
        $category = Category::get()->byID($categoryId);
        
        if (!$category) {
            return $this->jsonResponse(['error' => 'Category not found'], 404);
        }
        
        $limit = (int)$request->getVar('limit') ?: 12;
        $offset = (int)$request->getVar('offset') ?: 0;
        
        $products = Product::get()
            ->filter('Categories.ID', $categoryId)
            ->limit($limit, $offset);
        
        $data = [];
        foreach ($products as $product) {
            $data[] = $this->formatProductData($product);
        }
        
        return $this->jsonResponse([
            'success' => true,
            'category' => [
                'id' => $category->ID,
                'name' => $category->Name,
            ],
            'data' => $data,
            'meta' => [
                'total' => Product::get()->filter('Categories.ID', $categoryId)->count(),
                'limit' => $limit,
                'offset' => $offset,
            ]
        ]);
    }

    /**
     * POST /api/products
     * Create produk baru (Admin only)
     */
    public function createProduct(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }
        
        $member = Security::getCurrentUser();
        if (!$member || !Permission::check('ADMIN', 'any', $member)) {
            return $this->jsonResponse(['error' => 'Admin access required'], 403);
        }
        
        $data = json_decode($request->getBody(), true);
        
        // Validation
        if (empty($data['name']) || empty($data['price'])) {
            return $this->jsonResponse(['error' => 'Name and price are required'], 400);
        }
        
        try {
            $product = Product::create();
            $product->Name = $data['name'];
            $product->Price = (int)$data['price'];
            $product->Discount = (int)($data['discount'] ?? 0);
            $product->Description = $data['description'] ?? '';
            $product->Stock = (int)($data['stock'] ?? 0);
            $product->Weight = (int)($data['weight'] ?? 0);
            $product->write();
            
            // Add categories
            if (!empty($data['categories']) && is_array($data['categories'])) {
                $product->Categories()->setByIDList($data['categories']);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $this->formatProductData($product)
            ], 201);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUT /api/products/{id}
     * Update produk (Admin only)
     */
    public function updateProduct(HTTPRequest $request)
    {
        if (!$request->isPUT() && !$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only PUT/POST allowed'], 405);
        }
        
        $member = Security::getCurrentUser();
        if (!$member || !Permission::check('ADMIN', 'any', $member)) {
            return $this->jsonResponse(['error' => 'Admin access required'], 403);
        }
        
        $id = $request->param('ID');
        $product = Product::get()->byID($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }
        
        $data = json_decode($request->getBody(), true);
        
        try {
            if (isset($data['name'])) $product->Name = $data['name'];
            if (isset($data['price'])) $product->Price = (int)$data['price'];
            if (isset($data['discount'])) $product->Discount = (int)$data['discount'];
            if (isset($data['description'])) $product->Description = $data['description'];
            if (isset($data['stock'])) $product->Stock = (int)$data['stock'];
            if (isset($data['weight'])) $product->Weight = (int)$data['weight'];
            
            $product->write();
            
            // Update categories
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->Categories()->setByIDList($data['categories']);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $this->formatProductData($product)
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/products/{id}
     * Delete produk (Admin only)
     */
    public function deleteProduct(HTTPRequest $request)
    {
        if (!$request->isDELETE() && !$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only DELETE/POST allowed'], 405);
        }
        
        $member = Security::getCurrentUser();
        if (!$member || !Permission::check('ADMIN', 'any', $member)) {
            return $this->jsonResponse(['error' => 'Admin access required'], 403);
        }
        
        $id = $request->param('ID');
        $product = Product::get()->byID($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }
        
        try {
            $product->delete();
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => 'Failed to delete product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper: Format product data untuk response
     */
    private function formatProductData($product, $detailed = false)
    {
        $data = [
            'id' => $product->ID,
            'name' => $product->Name,
            'price' => $product->Price,
            'formatted_price' => $product->getFormattedPrice(),
            'discount' => $product->Discount,
            'formatted_discount' => $product->getFormattedDiscount(),
            'discount_price' => $product->getDiscountPrice(),
            'formatted_discount_price' => $product->getFormattedDiscountPrice(),
            'discount_percentage' => $product->getDiscountPercentage(),
            'has_discount' => $product->hasDiscount(),
            'stock' => $product->Stock,
            'stock_status' => $product->getStockStatus(),
            'is_in_stock' => $product->isInStock(),
            'weight' => $product->Weight,
            'categories' => $product->getCategoryNames(),
            'first_category' => $product->getFirstCategoryName(),
            'average_rating' => $product->getAverageRating(),
            'review_count' => $product->Review()->count(),
            'is_in_wishlist' => $product->getIsInWishlist(),
            'image_url' => $product->Image()->exists() ? $product->Image()->AbsoluteURL : null,
            'created' => $product->Created,
        ];
        
        if ($detailed) {
            $data['description'] = $product->Description;
        }
        
        return $data;
    }

    
public function wishlist(HTTPRequest $request)
{
    $member = Security::getCurrentUser();
    if (!$member) {
        return $this->jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $items = Wishlist::get()->filter('MemberID', $member->ID);

    $formatted = [];
    foreach ($items as $item) {
        $formatted[] = [
            'id' => $item->ID,
            'product_id' => $item->ProductID,
            'product' => $item->Product()->toMap(),
        ];
    }

    return $this->jsonResponse([
        'success' => true,
        'wishlist' => $formatted
    ]);
}

public function addToWishlist(HTTPRequest $request)
{
    $member = Security::getCurrentUser();
    if (!$member) {
        return $this->jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $data = json_decode($request->getBody(), true);
    $productID = $data['product_id'] ?? null;

    if (!$productID || !Product::get()->byID($productID)) {
        return $this->jsonResponse(['error' => 'Invalid product'], 400);
    }

    $exists = Wishlist::get()->filter([
        'ProductID' => $productID,
        'MemberID' => $member->ID
    ])->first();

    if ($exists) {
        return $this->jsonResponse(['message' => 'Already in wishlist'], 200);
    }

    $wishlist = Wishlist::create();
    $wishlist->ProductID = $productID;
    $wishlist->MemberID = $member->ID;
    $wishlist->write();

    return $this->jsonResponse(['success' => true, 'message' => 'Added to wishlist']);
}


public function removeFromWishlist(HTTPRequest $request)
{
    $member = Security::getCurrentUser();
    if (!$member) {
        return $this->jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $id = $request->param('ID');
    $item = Wishlist::get()->filter([
        'ID' => $id,
        'MemberID' => $member->ID
    ])->first();

    if ($item) {
        $item->delete();
    }

    return $this->jsonResponse(['success' => true, 'message' => 'Removed from wishlist']);
}

public function checkWishlist(HTTPRequest $request)
{
    $member = Security::getCurrentUser();
    if (!$member) {
        return $this->jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $productID = $request->param('ID');

    $exists = Wishlist::get()->filter([
        'ProductID' => $productID,
        'MemberID' => $member->ID
    ])->exists();

    return $this->jsonResponse([
        'product_id' => $productID,
        'is_wishlisted' => $exists
    ]);
}

public function toggleWishlist(HTTPRequest $request)
{
    $member = Security::getCurrentUser();
    if (!$member) {
        return $this->jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $productID = $request->param('ID');
    $existing = Wishlist::get()->filter([
        'ProductID' => $productID,
        'MemberID' => $member->ID
    ])->first();

    if ($existing) {
        $existing->delete();
        return $this->jsonResponse(['success' => true, 'message' => 'Removed from wishlist']);
    }

    $wishlist = Wishlist::create();
    $wishlist->ProductID = $productID;
    $wishlist->MemberID = $member->ID;
    $wishlist->write();

    return $this->jsonResponse(['success' => true, 'message' => 'Added to wishlist']);
}









}