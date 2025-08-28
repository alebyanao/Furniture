<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\View\Requirements;

class ProductDetailController extends ContentController 
{
    private static $allowed_actions = [
        'show'
    ];

    private static $url_handlers = [
        'show/$ID' => 'show'
    ];

    public function show(HTTPRequest $request)
    {
        $productID = $request->param('ID');
        $product = Product::get()->byID($productID);
        
        if (!$product) {
            return $this->httpError(404, 'Product not found');
        }

        // Add CSS for product detail styling
        Requirements::customCSS('
            .product-detail-container {
                padding: 50px 0;
            }
            
            .product-image-main {
                background-color: #fdf8ef;
                border-radius: 15px;
                padding: 40px;
                text-align: center;
                position: relative;
            }
            
            .product-image-main img {
                max-width: 100%;
                height: auto;
                max-height: 400px;
                object-fit: contain;
            }
            
            .discount-badge-large {
                position: absolute;
                top: 20px;
                left: 20px;
                background-color: #c4965c;
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
            }
            
            .product-title {
                color: #333;
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            .stock-info {
                color: #666;
                font-size: 1.1rem;
                margin-bottom: 10px;
            }
            
            .product-description {
                color: #666;
                line-height: 1.6;
                margin: 20px 0;
            }
            
            .price-section {
                margin: 30px 0;
            }
            
            .original-price {
                color: #999;
                text-decoration: line-through;
                font-size: 1.2rem;
            }
            
            .discounted-price {
                color: #c4965c;
                font-size: 2rem;
                font-weight: bold;
            }
            
            .regular-price {
                color: #c4965c;
                font-size: 2rem;
                font-weight: bold;
            }
            
            .quantity-section {
                display: flex;
                align-items: center;
                gap: 15px;
                margin: 30px 0;
            }
            
            .quantity-controls {
                display: flex;
                align-items: center;
                border: 2px solid #ddd;
                border-radius: 25px;
                overflow: hidden;
            }
            
            .quantity-btn {
                background: white;
                border: none;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 18px;
                color: #666;
            }
            
            .quantity-btn:hover {
                background-color: #f0f0f0;
            }
            
            .quantity-input {
                border: none;
                width: 60px;
                height: 40px;
                text-align: center;
                font-size: 16px;
                outline: none;
            }
            
            .btn-add-to-cart {
                background-color: #c4965c;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-add-to-cart:hover {
                background-color: #a67c4a;
                transform: translateY(-2px);
            }
            
            .btn-wishlist {
                background: white;
                border: 2px solid #ddd;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-wishlist:hover {
                border-color: #c4965c;
                color: #c4965c;
            }
            
            .rating-section {
                margin: 20px 0;
            }
            
            .star-rating {
                color: #ffc107;
                font-size: 24px;
                margin-right: 10px;
            }
            
            .rating-text {
                color: #666;
                font-size: 14px;
            }
        ');

        // Add JavaScript for quantity controls
        Requirements::customScript('
            function changeQuantity(change) {
                const input = document.getElementById("quantity");
                let currentValue = parseInt(input.value) || 1;
                let newValue = currentValue + change;
                
                if (newValue < 1) newValue = 1;
                if (newValue > ' . $product->Stock . ') newValue = ' . $product->Stock . ';
                
                input.value = newValue;
            }
            
            function addToCart() {
                const quantity = document.getElementById("quantity").value;
                alert("Added " + quantity + " item(s) to cart!");
                // Add your cart logic here
            }
        ');

        return [
            'Product' => $product,
            'OtherProducts' => $product->getOtherProducts(),
            'Title' => $product->Name . ' - Product Detail'
        ];
    }
}