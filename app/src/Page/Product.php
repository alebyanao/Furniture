<?php


use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxSetField;

class Product extends DataObject 
{
    private static $table_name = 'Product';
    
    private static $db = [
        'Name' => 'Varchar(255)',
        'Price' => 'Int',
        'Discount' => 'Int',
        'Description' => 'Text',
        'Stock' => 'Int'
    ];

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $many_many = [
        'Categories' => 'Category'
    ];

    private static $has_many = [
        'Reviews' => 'ProductReview'
    ];

    private static $owns = [
        'Image'
    ];

    private static $defaults = [
        'Stock' => 0
    ];

    private static $summary_fields = [
        'Name' => 'Product Name',
        'CategoryList' => 'Categories',
        'FormattedPrice' => 'Price',
        'FormattedDiscount' => 'Discount',
        'Stock' => 'Stock',
        'AverageRating' => 'Rating',
        'ReviewCount' => 'Reviews'
    ];

    private static $searchable_fields = [
        'Name',
        'Price',
        'Stock'
    ];

    private static $default_sort = 'Created DESC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Product Name'),
            
            CheckboxSetField::create('Categories', 'Categories', Category::get()->filter('IsActive', true)->map('ID', 'Name'))
                ->setDescription('Select one or more categories for this product'),
                
            TextareaField::create('Description', 'Product Description'),
            NumericField::create('Price', 'Price (Rp)')
                ->setDescription('Enter price without dots/commas. e.g: 150000 for Rp 150.000'),
            NumericField::create('Discount', 'Discount (Rp)')
                ->setDescription('Enter discount amount. e.g: 15000 for Rp 15.000 discount'),
            NumericField::create('Stock', 'Stock')
                ->setDescription('Jumlah stok barang yang tersedia')
        ]);

        return $fields;
    }

    public function getCategoryList()
    {
        $categories = $this->Categories()->column('Name');
        return $categories ? implode(', ', $categories) : 'No categories';
    }

    public function getFormattedPrice()
    {
        return 'Rp ' . $this->formatRupiah($this->Price);
    }

    public function getFormattedDiscount()
    {
        return $this->Discount > 0 ? 'Rp ' . $this->formatRupiah($this->Discount) : 'No Discount';
    }

        public function getDiscountPrice()
    {
        return max(0, $this->Price - $this->Discount);
    }

    public function getFormattedDiscountPrice()
    {
        return 'Rp ' . $this->formatRupiah($this->getDiscountPrice());
    }

    private function formatRupiah($amount)
    {
        return number_format($amount, 0, ',', '.');
    }

    public function getDiscountPercentage()
    {
        if ($this->Price > 0 && $this->Discount > 0) {
            return round(($this->Discount / $this->Price) * 100) . '%';
        }
        return '0%';
    }

    public function hasDiscount()
    {
        return $this->Discount > 0;
    }

    public function isInStock()
    {
        return $this->Stock > 0;
    }

    public function getStockStatus()
    {
        if ($this->Stock <= 0) {
            return 'Out of Stock';
        } elseif ($this->Stock <= 5) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getAverageRating()
    {
        $reviews = $this->Reviews();
        if ($reviews->count() > 0) {
            $total = $reviews->sum('Rating');
            return round($total / $reviews->count(), 1);
        }
        return 0;
    }

    public function getReviewCount()
    {
        return $this->Reviews()->count();
    }

    public function getStarRating()
    {
        $rating = $this->getAverageRating();
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '★';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        
        return $stars;
    }

    public function getCategoryNames()
    {
        return $this->Categories()->column('Name');
    }

    public function getFirstCategoryName()
    {
        $categories = $this->Categories();
        return $categories->count() > 0 ? $categories->first()->Name : 'Uncategorized';
    }
    
    // Add this method to your existing Product.php class
    
    public function formatRupiahPublic($amount)
    {
        return number_format($amount, 0, ',', '.');
    }

}