<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Requirements;

class ProductPage extends SiteTree 
{
    private static $table_name = 'ProductPage';
}

class ProductPageController extends ContentController
{
    private static $url_handlers = [
    'product/$ID' => 'product'
    ];
    private static $allowed_actions = [
        'product',
        'ReviewForm',
        'submitreview'
    ];

    public function getProducts()
    {
        return Product::get();
    }

    public function getCategories()
    {
        return Category::get()->filter('IsActive', true);
    }

    // Update method untuk multiple categories
    public function getProductsByCategory($categoryID = null)
    {
        if ($categoryID) {
            return Product::get()->filter('Categories.ID', $categoryID);
        }
        return Product::get();
    }

    // Tambah method untuk filter multiple categories
    public function getProductsByCategoryIDs($categoryIDs = [])
    {
        if (!empty($categoryIDs)) {
            return Product::get()->filter('Categories.ID', $categoryIDs);
        }
        return Product::get();
    }

    // Tambah method untuk produk yang tersedia (stock > 0)
    public function getAvailableProducts()
    {
        return Product::get()->filter('Stock:GreaterThan', 0);
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

    public function ReviewForm()
    {
        $productID = $this->getRequest()->getVar('product');
        
        $fields = FieldList::create([
            HiddenField::create('ProductID', 'ProductID', $productID),
            TextField::create('CustomerName', 'Your Name'),
            EmailField::create('CustomerEmail', 'Your Email'),
            DropdownField::create('Rating', 'Rating', [
                '5' => '5 Stars - Excellent',
                '4' => '4 Stars - Very Good',
                '3' => '3 Stars - Good',
                '2' => '2 Stars - Fair',
                '1' => '1 Star - Poor'
            ])->setEmptyString('Select Rating'),
            TextareaField::create('Comment', 'Your Review')
                ->setRows(4)
                ->setAttribute('placeholder', 'Write your review here...')
        ]);

        $actions = FieldList::create([
            FormAction::create('submitreview', 'Submit Review')
                ->addExtraClass('btn btn-primary')
        ]);

        $validator = RequiredFields::create([
            'CustomerName',
            'CustomerEmail', 
            'Rating',
            'Comment'
        ]);

        return Form::create($this, 'ReviewForm', $fields, $actions, $validator);
    }

    public function submitreview($data, Form $form)
    {
        $product = Product::get()->byID($data['ProductID']);
        
        if (!$product) {
            $form->sessionMessage('Product not found', 'bad');
            return $this->redirectBack();
        }

        $review = ProductReview::create();
        $review->ProductID = $data['ProductID'];
        $review->CustomerName = $data['CustomerName'];
        $review->CustomerEmail = $data['CustomerEmail'];
        $review->Rating = $data['Rating'];
        $review->Comment = $data['Comment'];
        $review->IsApproved = false; // Needs admin approval
        $review->write();

        $form->sessionMessage(
            'Thank you for your review! It will be published after admin approval.', 
            'good'
        );

        return $this->redirect($this->Link() . 'product/' . $data['ProductID']);
    }

    public function index() {
        return $this->renderWith(['ProductPage', 'Page']);
    }
    
}


class ProductDetailController extends Controller 
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
            'Title' => $product->Name . ' - Product Detail'
        ];
    }
}