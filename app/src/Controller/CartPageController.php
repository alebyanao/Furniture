<?php

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;

class CartPageController extends PageController
{
    private static $allowed_actions = [
        'add',
        'remove',
        'index',
        'updateQuantity',
    ];
    private static $url_segment = 'cart';
    private static $url_handlers = [
        'add/$ID/$Quantity' => 'add', // modifikasi untuk support quantity
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
     * Support untuk quantity dari URL parameter atau default 1.
     */
    public function add(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $productID = $request->param('ID');
        $quantity = (int) $request->param('Quantity') ?: 1; // ambil quantity dari URL atau default 1
        
        // Jika quantity dari GET parameter (fallback)
        if ($request->getVar('quantity')) {
            $quantity = (int) $request->getVar('quantity');
        }
        
        $product = Product::get()->byID($productID);

        if (!$product) {
            return $this->httpError(404);
        }

        // Validasi quantity
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
            
            // Cek stok
            if ($newQuantity > $product->Stock) {
                $this->getRequest()->getSession()->set('UserMessage', 
                    'Tidak bisa menambah ' . $quantity . ' item. Maksimal bisa ditambah: ' . ($product->Stock - $existingCartItem->Quantity));
                return $this->redirectBack();
            }
            
            $existingCartItem->Quantity = $newQuantity;
            $existingCartItem->write();
        } else {
            // Cek stok untuk item baru
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
     * Mengupdate jumlah item di cart.
     * Memastikan jumlah tidak melebihi stok produk.
     */
    public function updateQuantity(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
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

            if ($cartItem && $newQuantity > 0) {
                // Cek apakah quantity tidak melebihi stok
                if ($newQuantity <= $cartItem->Product()->Stock) {
                    $cartItem->Quantity = $newQuantity;
                    $cartItem->write();
                } else {
                    $this->getRequest()->getSession()->set('UserMessage', 
                        'Quantity melebihi stok. Maksimal: ' . $cartItem->Product()->Stock);
                }
            } else if ($newQuantity <= 0) {
                // Hapus item jika quantity 0
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
}