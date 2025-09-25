<?php

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

class CartPageController extends PageController
{
    private static $allowed_actions = [
        'product',
        'add',
        'remove',
        'index',
        'updateQuantity',
    ];
    private static $url_segment = 'cart';
    private static $url_handlers = [
        'product/$ID' => 'product',
        'add/$ID/$Quantity' => 'add',
        'remove/$ID' => 'remove',
        'update-quantity' => 'updateQuantity',
        '' => 'index'
    ];

    /**
     * Halaman utama cart.
     */
    public function index(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $user = $this->getCurrentUser();
        $cartItems = CartItem::get()->filter('MemberID', $user->ID);

        $data = array_merge($this->getCommonData(), [
            'Title' => 'Shopping Cart',
            'CartItems' => $cartItems,
            'TotalItems' => $this->getTotalItems(),
            'TotalPrice' => $this->getTotalPrice(),
            'FormattedTotalPrice' => $this->getFormattedTotalPrice()
        ]);

        return $this->customise($data)->renderWith(['CartPage', 'Page']);
    }

    /**
     * Menambahkan produk ke cart.
     */
    public function add(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $productID = $request->param('ID');
        $quantity = (int) $request->param('Quantity') ?: 1;
        
        if ($request->getVar('quantity')) {
            $quantity = (int) $request->getVar('quantity');
        }
        
        $product = Product::get()->byID($productID);

        if (!$product) {
            return $this->httpError(404);
        }

        if ($quantity <= 0) {
            $quantity = 1;
        }

        $user = $this->getCurrentUser();
        $existingCartItem = CartItem::get()->filter([
            'ProductID' => $productID,
            'MemberID' => $user->ID
        ])->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->Quantity + $quantity;
            
            if ($newQuantity > $product->Stock) {
                $this->getRequest()->getSession()->set('UserMessage', 
                    'Tidak bisa menambah ' . $quantity . ' item. Maksimal bisa ditambah: ' . ($product->Stock - $existingCartItem->Quantity));
                return $this->redirectBack();
            }
            
            $existingCartItem->Quantity = $newQuantity;
            $existingCartItem->write();
        } else {
            if ($quantity > $product->Stock) {
                $this->getRequest()->getSession()->set('UserMessage', 
                    'Quantity melebihi stok. Stok tersedia: ' . $product->Stock);
                return $this->redirectBack();
            }
            
            $cartItem = CartItem::create();
            $cartItem->ProductID = $productID;
            $cartItem->MemberID = $user->ID;
            $cartItem->Quantity = $quantity;
            $cartItem->write();
        }

        $this->getRequest()->getSession()->set('UserMessage', 
            $quantity . ' item berhasil ditambahkan ke keranjang');

        return $this->redirectBack();
    }

    /**
     * Menghapus item dari cart berdasarkan ID cart item.
     */
    public function remove(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $cartItemID = $request->param('ID');
        $user = $this->getCurrentUser();

        $cartItem = CartItem::get()->filter([
            'ID' => $cartItemID,
            'MemberID' => $user->ID
        ])->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return $this->redirectBack();
    }

    /**
     * Mengupdate jumlah item di cart dengan AJAX response.
     */
    public function updateQuantity(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            if ($request->getHeader('Accept') == 'application/json') {
                return HTTPResponse::create('{"error": "Unauthorized"}', 401)
                    ->addHeader('Content-Type', 'application/json');
            }
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        if ($request->isPOST()) {
            $cartItemID = $request->postVar('cartItemID');
            $newQuantity = (int) $request->postVar('quantity');
            $user = $this->getCurrentUser();

            $cartItem = CartItem::get()->filter([
                'ID' => $cartItemID,
                'MemberID' => $user->ID
            ])->first();

            // For AJAX requests, return JSON response
            if ($request->getHeader('Accept') == 'application/json' || 
                $request->getHeader('Content-Type') == 'application/json' ||
                $request->getVar('ajax') == '1') {
                
                if ($cartItem) {
                    if ($newQuantity > 0) {
                        // Check if quantity doesn't exceed stock
                        if ($newQuantity <= $cartItem->Product()->Stock) {
                            $cartItem->Quantity = $newQuantity;
                            $cartItem->write();
                            
                            $response = [
                                'success' => true,
                                'message' => 'Quantity updated successfully',
                                'new_quantity' => $newQuantity,
                                'new_subtotal' => $cartItem->getSubtotal(),
                                'formatted_subtotal' => $cartItem->getFormattedSubtotal(),
                                'total_items' => $this->getTotalItems(),
                                'total_price' => $this->getTotalPrice(),
                                'formatted_total_price' => $this->getFormattedTotalPrice()
                            ];
                        } else {
                            $response = [
                                'success' => false,
                                'error' => 'Quantity melebihi stok yang tersedia! Maksimal: ' . $cartItem->Product()->Stock,
                                'max_stock' => $cartItem->Product()->Stock
                            ];
                        }
                    } else {
                        // Remove item if quantity is 0 or negative
                        $cartItem->delete();
                        $response = [
                            'success' => true,
                            'message' => 'Item removed from cart',
                            'item_removed' => true,
                            'total_items' => $this->getTotalItems(),
                            'total_price' => $this->getTotalPrice(),
                            'formatted_total_price' => $this->getFormattedTotalPrice()
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'error' => 'Cart item not found'
                    ];
                }

                return HTTPResponse::create(json_encode($response), 200)
                    ->addHeader('Content-Type', 'application/json');
            }

            // For regular form submissions (fallback)
            if ($cartItem && $newQuantity > 0) {
                if ($newQuantity <= $cartItem->Product()->Stock) {
                    $cartItem->Quantity = $newQuantity;
                    $cartItem->write();
                } else {
                    $this->getRequest()->getSession()->set('UserMessage', 
                        'Quantity melebihi stok. Maksimal: ' . $cartItem->Product()->Stock);
                }
            } else if ($newQuantity <= 0 && $cartItem) {
                $cartItem->delete();
            }
        }

        return $this->redirectBack();
    }

    /**
     * Menghitung total jumlah semua item di cart user.
     */
    public function getTotalItems()
    {
        if (!$this->isLoggedIn()) {
            return 0;
        }

        $user = $this->getCurrentUser();
        $cartItems = CartItem::get()->filter('MemberID', $user->ID);

        $totalItems = 0;
        foreach ($cartItems as $item) {
            $totalItems += $item->Quantity;
        }

        return $totalItems;
    }

    /**
     * Menghitung total harga semua item di cart user.
     */
    public function getTotalPrice()
    {
        if (!$this->isLoggedIn()) {
            return 0;
        }

        $user = $this->getCurrentUser();
        $cartItems = CartItem::get()->filter('MemberID', $user->ID);

        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->getSubtotal();
        }

        return $totalPrice;
    }

    /**
     * Memformat total harga ke format Rupiah.
     */
    public function getFormattedTotalPrice()
    {
        return 'Rp ' . number_format($this->getTotalPrice(), 0, '.', '.');
    }

    public function product(HTTPRequest $request)
    {
        $id = $request->param('ID');
        $product = Product::get()->byID($id);
        
        if (!$product) {
            return $this->httpError(404, 'Product not found');
        }
        
        return $this->customise([
            'Product' => $product
        ])->renderWith(['ProductDetailShow', 'Page']);
    }
}