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

    public function hasPromoCards()
    {
        return PromoCard::get()->filter('IsActive', true)->exists();
    }

    public function getPromoCards()
    {
        return PromoCard::get()->filter('IsActive', true)->sort('SortOrder ASC, Created DESC');
    }

    public function getTwoPromoCards()
    {
        return $this->getPromoCards()->limit(2);
    }

    public function getAllPromoCards()
    {
        return $this->getPromoCards();
    }

    public function getPromoCardsCount()
    {
        return $this->getPromoCards()->count();
    }

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
        $review->IsApproved = false;
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