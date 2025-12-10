<?php

use SilverStripe\Security\Member;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\HTTPRequest;

class HomePageController extends PageController
{
    private static $url_handlers = [
    'product/$ID' => 'product'
    ];
    private static $allowed_actions = [
        'product'
    ]; 

    private static $has_one = [
        "Product" => Product::class,
        "Member" => Member::class,
    ];

    public function getBestSellersProducts()
    {
        $category = Category::get()->filter('Name', 'Best Sellers')->first();
        if ($category && $category->Products()->count() > 0) {
            return $category->Products()
                ->filter('Stock:GreaterThan', 0)
                ->sort('Created', 'DESC')
                ->limit(6);
        }
        // Fallback jika kategori tidak ada
        return Product::get()
            ->filter('Stock:GreaterThan', 0)
            ->sort('ReviewCount', 'DESC')
            ->limit(6);
    }

    public function getFeaturedProducts()
    {
        $category = Category::get()->filter('Name', 'Featured Product')->first();
        if ($category && $category->Products()->count() > 0) {
            return $category->Products()
                ->filter('Stock:GreaterThan', 0)
                ->sort('Created', 'DESC');
        }
        // Fallback
        return Product::get()
            ->filter('Stock:GreaterThan', 0);
    }

    /**
     * Get "Promo" products - berdasarkan kategori admin
     */
    public function getPromoProducts()
    {
        $category = Category::get()->filter('Name', 'Promo')->first();
        if ($category && $category->Products()->count() > 0) {
            return $category->Products()
                ->filter('Stock:GreaterThan', 0)
                ->sort('Discount', 'DESC');
        }
        // Fallback ke produk dengan discount
        return Product::get()
            ->filter('Discount:GreaterThan', 0)
            ->filter('Stock:GreaterThan', 0);
    }

    /**
     * Get "Trendy Collection" products - berdasarkan kategori admin
     */
    public function getTrendyProducts()
    {
        $category = Category::get()->filter('Name', 'Trendy Collection')->first();
        if ($category && $category->Products()->count() > 0) {
            return $category->Products()
                ->filter('Stock:GreaterThan', 0)
                ->sort('Created', 'DESC');
        }
        // Fallback ke produk terbaru
        return Product::get()
            ->filter('Stock:GreaterThan', 0)
            ->sort('Created', 'DESC');
    }

    /**
     * Get products berdasarkan nama kategori spesifik
     */
    public function getProductsByCategoryName($categoryName, $limit = 4)
    {
        $category = Category::get()->filter('Name', $categoryName)->first();
        if ($category) {
            return $category->Products()
                ->filter('Stock:GreaterThan', 0)
                ->limit($limit);
        }
        return null;
    }

    public function hasBestSellers()
    {
        $products = $this->getBestSellersProducts();
        return $products && $products->count() > 0;
    }

    public function hasFeaturedProducts()
    {
        $products = $this->getFeaturedProducts();
        return $products && $products->count() > 0;
    }

    public function hasPromoProducts()
    {
        $products = $this->getPromoProducts();
        return $products && $products->count() > 0;
    }

    public function hasTrendyProducts()
    {
        $products = $this->getTrendyProducts();
        return $products && $products->count() > 0;
    }

    /**
     * Get all active categories (untuk loop di template)
     */
    public function getActiveCategories()
    {
        return Category::get()->filter('IsActive', true);
    }

    /**
     * Get products by category ID
     */
    public function getProductsByCategory($categoryID, $limit = 4)
    {
        return Product::get()
            ->filter('Categories.ID', $categoryID)
            ->filter('Stock:GreaterThan', 0)
            ->limit($limit);
    }

    /**
     * Check if we have products
     */
    public function hasProducts()
    {
        return Product::get()->count() > 0;
    }

    /**
     * Get specific category by name
     */
    public function getBestSellersCategory()
    {
        return Category::get()->filter('Name', 'Best Sellers')->first();
    }

    public function getFeaturedCategory()
    {
        return Category::get()->filter('Name', 'Featured Product')->first();
    }

    public function getPromoCategory()
    {
        return Category::get()->filter('Name', 'Promo')->first();
    }

    public function getTrendyCategory()
    {
        return Category::get()->filter('Name', 'Trendy Collection')->first();
    }

    // ===================== HERO BANNER & FEATURE METHODS (EXISTING) =====================
    
    public function getHeroBanners()
    {
        if (!$this->dataRecord->getHeroBannerEnabled()) {
            return null;
        }
        return HeroBanner::getActiveBanners();
    }

    public function hasHeroBanners()
    {
        if (!$this->dataRecord->getHeroBannerEnabled()) {
            return false;
        }
        $banners = $this->getHeroBanners();
        return $banners && $banners->count() > 0;
    }

    public function getFirstHeroBanner()
    {
        $banners = $this->getHeroBanners();
        return $banners ? $banners->first() : null;
    }

    public function getAllHeroImages()
    {
        $banners = $this->getHeroBanners();
        if (!$banners) {
            return ArrayList::create();
        }
        
        $allImages = ArrayList::create();
        foreach ($banners as $banner) {
            $images = $banner->getSortedHeroImages();
            foreach ($images as $image) {
                $allImages->push($image);
            }
        }
        return $allImages;
    }

    public function getFeatureItems()
    {
        if (!$this->dataRecord->getFeaturesEnabled()) {
            return null;
        }
        return FeatureItem::getActiveFeatures();
    }

    public function hasFeatureItems()
    {
        if (!$this->dataRecord->getFeaturesEnabled()) {
            return false;
        }
        $features = $this->getFeatureItems();
        return $features && $features->count() > 0;
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
    public function index()
    {
        return $this->renderWith(['HomePage', 'Page']);
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