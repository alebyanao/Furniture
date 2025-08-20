<?php

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\DropdownField;

class ProductAdmin extends ModelAdmin 
{
    private static $managed_models = [
        Product::class,
        Category::class,
        ProductReview::class,
        PromoCard::class  
    ];

    private static $url_segment = 'shop-admin';
    
    private static $menu_title = 'Shop Management';

    private static $menu_icon_class = 'font-icon-cart';

    public function getList()
    {
        $list = parent::getList();
        
        // Sort products by newest first
        if ($this->modelClass === Product::class) {
            return $list->sort('Created', 'DESC');
        }
        
        // Sort categories alphabetically
        if ($this->modelClass === Category::class) {
            return $list->sort('Name', 'ASC');
        }
        
        // Sort reviews by newest first
        if ($this->modelClass === ProductReview::class) {
            return $list->sort('Created', 'DESC');
        }
        
        // Sort promo cards by sort order, then newest first
        if ($this->modelClass === PromoCard::class) {
            return $list->sort('SortOrder', 'ASC')->sort('Created', 'DESC');
        }
        
        return $list;
    }

    public function getSearchContext()
    {
        $context = parent::getSearchContext();
        
        // Add category filter for products
        if ($this->modelClass === Product::class) {
            $fields = $context->getFields();
            $fields->push(
                DropdownField::create('CategoryFilter', 'Category', Category::get()->filter('IsActive', true)->map('ID', 'Name'))
                    ->setEmptyString('All Categories')
            );
        }
        
        return $context;
    }
}