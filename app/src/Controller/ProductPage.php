<?php

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Member;

class ProductPage extends Page 
{
    private static $table_name = 'ProductPage';
}
class ProductPageController extends PageController
{
    private static $url_handlers = [
    'product/$ID' => 'product'
    ];

    private static $allowed_actions = [
        'product',
    ];

    private static $has_one = [
        "Product" => Product::class,
        "Member" => Member::class,
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

    public function getAverageRating()
    {
        $reviews = $this->Review();
        if ($reviews->count() == 0) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review->Rating;
        }

        $average = $totalRating / $reviews->count();
        return number_format($average, 1);
    }

    public function index() {
        return $this->renderWith(['ProductPage', 'Page']);
    }

}