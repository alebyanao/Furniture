<?php

namespace App\Admin;

use App\Models\HeroBanner;
use App\Models\FeatureItem;
use SilverStripe\Admin\ModelAdmin;

// ===== HERO BANNER ADMIN =====
class HeroBannerAdmin extends ModelAdmin
{
    private static $managed_models = [
        HeroBanner::class
    ];

    private static $url_segment = 'hero-banners';
    
    private static $menu_title = 'Hero Banners';
    
    private static $menu_icon_class = 'font-icon-block-banner';
}

// ===== CONTENT ADMIN (Gabungan Hero + Features) =====
class ContentAdmin extends ModelAdmin
{
    private static $managed_models = [
        HeroBanner::class => ['title' => 'Hero Banners'],
        FeatureItem::class => ['title' => 'Features Section']
    ];

    private static $url_segment = 'content-management';
    
    private static $menu_title = 'Content Management';
    
    private static $menu_icon_class = 'font-icon-block-content';
    
    private static $menu_priority = 10;

}