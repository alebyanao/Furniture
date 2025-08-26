<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Session;
use SilverStripe\View\Requirements;

class CartPage extends SiteTree 
{
    private static $table_name = 'CartPage';
}

class CartPageController extends ContentController
{
    private static $allowed_actions = [
        'addToCart',
        'updateQuantity',
        'removeFromCart',
        'clearCart',
        'checkout',
        'getTotalItems' => 'getTotalItemsAjax'
    ];

    // public function init()
    // {
    //     parent::init();
        
    //     // Add CSS for cart styling
    //     Requirements::customCSS('
    //         .cart-container {
    //             padding: 50px 0;
    //         }
            
    //         .cart-item {
    //             background: white;
    //             border: 1px solid #e0e0e0;
    //             border-radius: 10px;
    //             margin-bottom: 20px;
    //             padding: 20px;
    //             box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    //         }
            
    //         .cart-item-image {
    //             background-color: #fdf8ef;
    //             border-radius: 10px;
    //             padding: 20px;
    //             text-align: center;
    //             max-height: 150px;
    //             display: flex;
    //             align-items: center;
    //             justify-content: center;
    //         }
            
    //         .cart-item-image img {
    //             max-width: 100%;
    //             max-height: 120px;
    //             object-fit: contain;
    //         }
            
    //         .quantity-controls {
    //             display: flex;
    //             align-items: center;
    //             border: 1px solid #ddd;
    //             border-radius: 25px;
    //             overflow: hidden;
    //             width: fit-content;
    //         }
            
    //         .quantity-btn {
    //             background: white;
    //             border: none;
    //             width: 35px;
    //             height: 35px;
    //             display: flex;
    //             align-items: center;
    //             justify-content: center;
    //             cursor: pointer;
    //             font-size: 16px;
    //             color: #666;
    //             transition: background-color 0.3s;
    //         }
            
    //         .quantity-btn:hover {
    //             background-color: #f0f0f0;
    //         }
            
    //         .quantity-btn:disabled {
    //             opacity: 0.5;
    //             cursor: not-allowed;
    //         }
            
    //         .quantity-input {
    //             border: none;
    //             width: 50px;
    //             height: 35px;
    //             text-align: center;
    //             font-size: 14px;
    //             outline: none;
    //             background: #f9f9f9;
    //         }
            
    //         .btn-remove {
    //             background-color: #dc3545;
    //             color: white;
    //             border: none;
    //             padding: 8px 15px;
    //             border-radius: 20px;
    //             font-size: 12px;
    //             cursor: pointer;
    //             transition: all 0.3s;
    //         }
            
    //         .btn-remove:hover {
    //             background-color: #c82333;
    //         }
            
    //         .cart-summary {
    //             background: #f8f9fa;
    //             border-radius: 15px;
    //             padding: 30px;
    //             position: sticky;
    //             top: 20px;
    //         }
            
    //         .btn-checkout {
    //             background-color: #c4965c;
    //             color: white;
    //             border: none;
    //             padding: 15px 30px;
    //             border-radius: 25px;
    //             font-size: 16px;
    //             font-weight: 600;
    //             width: 100%;
    //             cursor: pointer;
    //             transition: all 0.3s;
    //         }
            
    //         .btn-checkout:hover {
    //             background-color: #a67c4a;
    //             transform: translateY(-2px);
    //         }
            
    //         .btn-continue {
    //             background: white;
    //             color: #c4965c;
    //             border: 2px solid #c4965c;
    //             padding: 12px 25px;
    //             border-radius: 25px;
    //             text-decoration: none;
    //             font-weight: 600;
    //             transition: all 0.3s;
    //             display: inline-block;
    //         }
            
    //         .btn-continue:hover {
    //             background-color: #c4965c;
    //             color: white;
    //             text-decoration: none;
    //         }
            
    //         .empty-cart {
    //             text-align: center;
    //             padding: 80px 20px;
    //             color: #666;
    //         }
            
    //         .loading-overlay {
    //             position: fixed;
    //             top: 0;
    //             left: 0;
    //             width: 100%;
    //             height: 100%;
    //             background: rgba(255,255,255,0.8);
    //             display: none;
    //             align-items: center;
    //             justify-content: center;
    //             z-index: 9999;
    //         }
            
    //         .spinner {
    //             border: 3px solid #f3f3f3;
    //             border-top: 3px solid #c4965c;
    //             border-radius: 50%;
    //             width: 40px;
    //             height: 40px;
    //             animation: spin 1s linear infinite;
    //         }
            
    //         @keyframes spin {
    //             0% { transform: rotate(0deg); }
    //             100% { transform: rotate(360deg); }
    //         }
            
    //         .alert {
    //             padding: 15px;
    //             margin-bottom: 20px;
    //             border: 1px solid transparent;
    //             border-radius: 10px;
    //         }
            
    //         .alert-success {
    //             color: #155724;
    //             background-color: #d4edda;
    //             border-color: #c3e6cb;
    //         }
            
    //         .alert-danger {
    //             color: #721c24;
    //             background-color: #f8d7da;
    //             border-color: #f5c6cb;
    //         }
    //     ');

    //     // Add JavaScript for AJAX functionality
    //     Requirements::customScript('
    //         class Cart {
    //             constructor() {
    //                 this.bindEvents();
    //             }
                
    //             bindEvents() {
    //                 // Quantity change buttons
    //                 document.addEventListener("click", (e) => {
    //                     if (e.target.classList.contains("quantity-decrease")) {
    //                         this.updateQuantity(e.target.dataset.productId, -1);
    //                     }
    //                     if (e.target.classList.contains("quantity-increase")) {
    //                         this.updateQuantity(e.target.dataset.productId, 1);
    //                     }
    //                     if (e.target.classList.contains("remove-item")) {
    //                         this.removeFromCart(e.target.dataset.productId);
    //                     }
    //                 });
                    
    //                 // Direct quantity input change
    //                 document.addEventListener("change", (e) => {
    //                     if (e.target.classList.contains("quantity-input")) {
    //                         const productId = e.target.dataset.productId;
    //                         const currentQty = parseInt(e.target.dataset.currentQty);
    //                         const newQty = parseInt(e.target.value);
    //                         const change = newQty - currentQty;
                            
    //                         if (change !== 0) {
    //                             this.updateQuantity(productId, change, true);
    //                         }
    //                     }
    //                 });
    //             }
                
    //             showLoading() {
    //                 document.querySelector(".loading-overlay").style.display = "flex";
    //             }
                
    //             hideLoading() {
    //                 document.querySelector(".loading-overlay").style.display = "none";
    //             }
                
    //             showAlert(message, type = "success") {
    //                 const alertDiv = document.createElement("div");
    //                 alertDiv.className = `alert alert-${type}`;
    //                 alertDiv.textContent = message;
                    
    //                 const container = document.querySelector(".cart-container .container");
    //                 container.insertBefore(alertDiv, container.firstChild);
                    
    //                 setTimeout(() => {
    //                     alertDiv.remove();
    //                 }, 3000);
    //             }
                
    //             updateQuantity(productId, change, absolute = false) {
    //                 this.showLoading();
                    
    //                 const formData = new FormData();
    //                 formData.append("ProductID", productId);
    //                 formData.append("Change", change);
    //                 formData.append("Absolute", absolute ? "1" : "0");
                    
    //                 fetch("' . $this->Link() . 'updateQuantity", {
    //                     method: "POST",
    //                     body: formData,
    //                     headers: {
    //                         "X-Requested-With": "XMLHttpRequest"
    //                     }
    //                 })
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     this.hideLoading();
                        
    //                     if (data.success) {
    //                         // Update quantity display
    //                         const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
    //                         if (quantityInput) {
    //                             quantityInput.value = data.newQuantity;
    //                             quantityInput.dataset.currentQty = data.newQuantity;
    //                         }
                            
    //                         // Update subtotal
    //                         const subtotalElement = document.querySelector(`#subtotal-${productId}`);
    //                         if (subtotalElement) {
    //                             subtotalElement.textContent = data.subtotal;
    //                         }
                            
    //                         // Update cart totals
    //                         this.updateCartTotals(data.cartData);
                            
    //                         // Remove item if quantity is 0
    //                         if (data.newQuantity === 0) {
    //                             document.querySelector(`#cart-item-${productId}`).remove();
    //                             this.checkEmptyCart();
    //                         }
                            
    //                         this.showAlert(data.message);
    //                     } else {
    //                         this.showAlert(data.message, "danger");
    //                     }
    //                 })
    //                 .catch(error => {
    //                     this.hideLoading();
    //                     this.showAlert("An error occurred. Please try again.", "danger");
    //                     console.error("Error:", error);
    //                 });
    //             }
                
    //             removeFromCart(productId) {
    //                 if (!confirm("Are you sure you want to remove this item?")) {
    //                     return;
    //                 }
                    
    //                 this.showLoading();
                    
    //                 const formData = new FormData();
    //                 formData.append("ProductID", productId);
                    
    //                 fetch("' . $this->Link() . 'removeFromCart", {
    //                     method: "POST",
    //                     body: formData,
    //                     headers: {
    //                         "X-Requested-With": "XMLHttpRequest"
    //                     }
    //                 })
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     this.hideLoading();
                        
    //                     if (data.success) {
    //                         document.querySelector(`#cart-item-${productId}`).remove();
    //                         this.updateCartTotals(data.cartData);
    //                         this.checkEmptyCart();
    //                         this.showAlert(data.message);
    //                     } else {
    //                         this.showAlert(data.message, "danger");
    //                     }
    //                 })
    //                 .catch(error => {
    //                     this.hideLoading();
    //                     this.showAlert("An error occurred. Please try again.", "danger");
    //                     console.error("Error:", error);
    //                 });
    //             }
                
    //             updateCartTotals(cartData) {
    //                 const totalItemsElement = document.querySelector("#total-items");
    //                 const totalPriceElement = document.querySelector("#total-price");
                    
    //                 if (totalItemsElement) {
    //                     totalItemsElement.textContent = cartData.totalItems;
    //                 }
    //                 if (totalPriceElement) {
    //                     totalPriceElement.textContent = cartData.totalFormatted;
    //                 }
    //             }
                
    //             checkEmptyCart() {
    //                 const cartItems = document.querySelectorAll(".cart-item");
    //                 if (cartItems.length === 0) {
    //                     location.reload(); // Reload to show empty cart message
    //                 }
    //             }
    //         }
            
    //         // Initialize cart when DOM is loaded
    //         document.addEventListener("DOMContentLoaded", function() {
    //             new Cart();
    //         });
    //     ');
    // }

    public function index()
    {
        return $this->renderWith(['CartPage', 'Page']);
    }

    public function getCartItems()
    {
        $session = $this->getRequest()->getSession();
        $cart = $session->get('Cart') ?: [];
        $cartItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::get()->byID($productId);
            if ($product && $quantity > 0) {
                $cartItems[] = [
                    'Product' => $product,
                    'Quantity' => $quantity,
                    'Subtotal' => $product->getDiscountPrice() * $quantity,
                    'SubtotalFormatted' => 'Rp ' . $product->formatRupiahPublic($product->getDiscountPrice() * $quantity)
                ];
            }
        }

        return $cartItems;
    }

    public function getCartTotal()
    {
        $cartItems = $this->getCartItems();
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item['Subtotal'];
        }

        return $total;
    }

    public function getCartTotalFormatted()
    {
        return 'Rp ' . number_format($this->getCartTotal(), 0, ',', '.');
    }

    public function getTotalItems()
    {
        $session = $this->getRequest()->getSession();
        $cart = $session->get('Cart') ?: [];
        return array_sum($cart);
    }

    public function addToCart(HTTPRequest $request)
    {
        $productId = $request->postVar('ProductID') ?: $request->getVar('ProductID');
        $quantity = (int) ($request->postVar('Quantity') ?: $request->getVar('Quantity')) ?: 1;
        
        $product = Product::get()->byID($productId);
        
        // Handle AJAX requests
        if ($request->isAjax() && $request->isPOST()) {
            $response = HTTPResponse::create();
            $response->addHeader('Content-Type', 'application/json');

            if (!$product) {
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Product not found'
                ]));
                return $response;
            }

            if (!$product->isInStock()) {
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ]));
                return $response;
            }

            $session = $request->getSession();
            $cart = $session->get('Cart') ?: [];
            
            $currentQuantity = isset($cart[$productId]) ? $cart[$productId] : 0;
            $newQuantity = $currentQuantity + $quantity;

            if ($newQuantity > $product->Stock) {
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Not enough stock available. Only ' . $product->Stock . ' items left.'
                ]));
                return $response;
            }

            $cart[$productId] = $newQuantity;
            $session->set('Cart', $cart);

            $response->setBody(json_encode([
                'success' => true,
                'message' => $quantity . ' item(s) added to cart',
                'totalItems' => array_sum($cart)
            ]));

            return $response;
        }
        
        // Handle GET requests (fallback)
        if ($productId) {
            if (!$product) {
                $this->getRequest()->getSession()->set('CartMessage', 'Product not found');
                return $this->redirect($this->Link());
            }

            if (!$product->isInStock()) {
                $this->getRequest()->getSession()->set('CartMessage', 'Product is out of stock');
                return $this->redirect($this->Link());
            }

            $session = $request->getSession();
            $cart = $session->get('Cart') ?: [];
            
            $currentQuantity = isset($cart[$productId]) ? $cart[$productId] : 0;
            $newQuantity = $currentQuantity + $quantity;

            if ($newQuantity > $product->Stock) {
                $session->set('CartMessage', 'Not enough stock available. Only ' . $product->Stock . ' items left.');
                return $this->redirect($this->Link());
            }   

            $cart[$productId] = $newQuantity;
            $session->set('Cart', $cart);
            $session->set('CartMessage', $quantity . ' item(s) added to cart successfully!');
        }
        
        return $this->redirect($this->Link());
    }

    public function updateQuantity(HTTPRequest $request)
    {
        if (!$request->isAjax() || !$request->isPOST()) {
            return $this->redirect($this->Link());
        }

        $productId = $request->postVar('ProductID');
        $change = (int) $request->postVar('Change');
        $absolute = $request->postVar('Absolute') === '1';
        
        $product = Product::get()->byID($productId);
        
        $response = HTTPResponse::create();
        $response->addHeader('Content-Type', 'application/json');

        if (!$product) {
            $response->setBody(json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]));
            return $response;
        }

        $session = $request->getSession();
        $cart = $session->get('Cart') ?: [];
        
        $currentQuantity = isset($cart[$productId]) ? $cart[$productId] : 0;
        
        if ($absolute) {
            $newQuantity = $change;
        } else {
            $newQuantity = $currentQuantity + $change;
        }

        if ($newQuantity < 0) {
            $newQuantity = 0;
        }

        if ($newQuantity > $product->Stock) {
            $response->setBody(json_encode([
                'success' => false,
                'message' => 'Not enough stock available. Only ' . $product->Stock . ' items left.'
            ]));
            return $response;
        }

        if ($newQuantity === 0) {
            unset($cart[$productId]);
            $message = 'Item removed from cart';
        } else {
            $cart[$productId] = $newQuantity;
            $message = 'Quantity updated';
        }

        $session->set('Cart', $cart);

        $subtotal = $product->getDiscountPrice() * $newQuantity;
        $subtotalFormatted = 'Rp ' . $product->formatRupiahPublic($subtotal);

        $response->setBody(json_encode([
            'success' => true,
            'message' => $message,
            'newQuantity' => $newQuantity,
            'subtotal' => $subtotalFormatted,
            'cartData' => [
                'totalItems' => array_sum($cart),
                'totalFormatted' => $this->getCartTotalFormatted()
            ]
        ]));

        return $response;
    }

    public function removeFromCart(HTTPRequest $request)
    {
        if (!$request->isAjax() || !$request->isPOST()) {
            return $this->redirect($this->Link());
        }

        $productId = $request->postVar('ProductID');
        
        $response = HTTPResponse::create();
        $response->addHeader('Content-Type', 'application/json');

        $session = $request->getSession();
        $cart = $session->get('Cart') ?: [];
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $session->set('Cart', $cart);
            
            $response->setBody(json_encode([
                'success' => true,
                'message' => 'Item removed from cart',
                'cartData' => [
                    'totalItems' => array_sum($cart),
                    'totalFormatted' => $this->getCartTotalFormatted()
                ]
            ]));
        } else {
            $response->setBody(json_encode([
                'success' => false,
                'message' => 'Item not found in cart'
            ]));
        }

        return $response;
    }

    public function clearCart(HTTPRequest $request)
    {
        $session = $request->getSession();
        $session->clear('Cart');
        
        return $this->redirect($this->Link());
    }

    public function checkout(HTTPRequest $request)
    {
        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            return $this->redirect($this->Link());
        }

        // Simple checkout - just clear cart and show success
        // In real implementation, you would process payment here
        $session = $request->getSession();
        $session->clear('Cart');
        
        $session->set('CheckoutSuccess', 'Thank you for your purchase! Your order has been placed successfully.');
        
        return $this->redirect($this->Link());
    }

    public function getCheckoutMessage()
    {
        $session = $this->getRequest()->getSession();
        $message = $session->get('CheckoutSuccess');
        if ($message) {
            $session->clear('CheckoutSuccess');
            return $message;
        }
        return null;
    }

    public function getCartItemCount()
    {
        $session = $this->getRequest()->getSession();
        $cart = $session->get('Cart') ?: [];
        return array_sum($cart);
    }

    public function getCartMessage()
    {
        $session = $this->getRequest()->getSession();
        $message = $session->get('CartMessage');
        if ($message) {
            $session->clear('CartMessage');
            return $message;
        }
        return null;
    }

    public function getTotalItemsAjax(HTTPRequest $request)
    {
        $response = HTTPResponse::create();
        $response->addHeader('Content-Type', 'application/json');
        
        $session = $request->getSession();
        $cart = $session->get('Cart') ?: [];
        $totalItems = array_sum($cart);
        
        $response->setBody(json_encode([
            'totalItems' => $totalItems
        ]));
        
        return $response;
    }
}