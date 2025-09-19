<?php

namespace App\Models;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

// ===== HERO BANNER MODEL =====
class HeroBanner extends DataObject
{
    private static $table_name = 'HeroBanner';

    private static $db = [
        'Title' => 'Varchar(255)',
        'Description' => 'Text',
        'IsActive' => 'Boolean'
    ];

    private static $many_many = [
        'HeroImages' => Image::class
    ];

    private static $many_many_extraFields = [
        'HeroImages' => [
            'SortOrder' => 'Int'
        ]
    ];

    private static $owns = [
        'HeroImages'
    ];

    private static $defaults = [
        'IsActive' => true
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->addFieldToTab('Root.Main', 
            TextField::create('Title', 'Banner Title')
        );
        
        $fields->addFieldToTab('Root.Main', 
            TextareaField::create('Description', 'Banner Description')->setRows(4)
        );
        
        $fields->addFieldToTab('Root.Main', 
            UploadField::create('HeroImages', 'Hero Images (Multiple)')
                ->setFolderName('hero-banners')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                ->setDescription('Upload multiple images for slideshow. Images will rotate automatically.')
        );
        
        $fields->addFieldToTab('Root.Main', 
            CheckboxField::create('IsActive', 'Show on Homepage')
        );

        return $fields;
    }

    public static function getActiveBanners()
    {
        return self::get()->filter('IsActive', 1)->sort('ID DESC');
    }

    public function getSortedHeroImages()
    {
        return $this->HeroImages()->sort('SortOrder ASC');
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}

// ===== FEATURE ITEM MODEL (IN SAME FILE) =====
class FeatureItem extends DataObject
{
    private static $table_name = 'FeatureItem';

    private static $db = [
        'Title' => 'Varchar(255)',
        'Description' => 'Varchar(255)',
        'IconType' => 'Enum("image,fontawesome","image")',
        'FontAwesomeIcon' => 'Varchar(100)',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'IconImage' => Image::class
    ];

    private static $owns = [
        'IconImage'
    ];

    private static $defaults = [
        'IsActive' => true,
        'IconType' => 'image',
        'SortOrder' => 1
    ];

    private static $default_sort = 'SortOrder ASC';

    private static $summary_fields = [
        'Title' => 'Title',
        'Description' => 'Description',
        'IconType' => 'Icon Type',
        'IsActive.Nice' => 'Active'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->addFieldToTab('Root.Main', 
            TextField::create('Title', 'Feature Title')
                ->setDescription('Contoh: Free Delivery')
        );
        
        $fields->addFieldToTab('Root.Main', 
            TextField::create('Description', 'Feature Description')
                ->setDescription('Contoh: Free shipping on all order')
        );

        $fields->addFieldToTab('Root.Main',
            HeaderField::create('IconHeader', 'Icon Settings', 3)
        );
        
        $fields->addFieldToTab('Root.Main', 
            DropdownField::create('IconType', 'Icon Type', [
                'image' => 'Upload Image Icon',
                'fontawesome' => 'FontAwesome Icon'
            ])->setDescription('Pilih jenis icon yang ingin digunakan')
        );
        
        $fields->addFieldToTab('Root.Main', 
            UploadField::create('IconImage', 'Icon Image')
                ->setFolderName('feature-icons')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'svg', 'webp'])
                ->setDescription('Upload icon image (akan tampil jika Icon Type = Upload Image)')
        );
        
        $fields->addFieldToTab('Root.Main', 
            TextField::create('FontAwesomeIcon', 'FontAwesome Icon Class')
                ->setDescription('Contoh: fas fa-truck, fas fa-money-bill-wave, fas fa-headset, fas fa-shield-alt')
        );

        $fields->addFieldToTab('Root.Main', 
            TextField::create('SortOrder', 'Sort Order')
                ->setDescription('Urutan tampil (angka kecil tampil duluan)')
        );
        
        $fields->addFieldToTab('Root.Main', 
            CheckboxField::create('IsActive', 'Show on Website')
        );

        return $fields;
    }

    public static function getActiveFeatures()
    {
        return self::get()->filter('IsActive', 1)->sort('SortOrder ASC');
    }

    public function getIconHTML()
    {
        if ($this->IconType == 'fontawesome' && !empty($this->FontAwesomeIcon)) {
            return '<i class="' . $this->FontAwesomeIcon . '" style="font-size: 40px; color: #b78b5c;"></i>';
        } elseif ($this->IconType == 'image' && $this->IconImage()->exists()) {
            $imageURL = $this->IconImage()->AbsoluteURL;
            return '<img src="' . $imageURL . '" alt="' . htmlspecialchars($this->Title) . '" width="40" height="40" class="feature-icon" style="object-fit: contain; display: block;">';
        }
        
        return '<i class="fas fa-star" style="font-size: 40px; color: #b78b5c;"></i>';
    }

    public function getIconImageURL()
    {
        if ($this->IconImage()->exists()) {
            return $this->IconImage()->AbsoluteURL;
        }
        return null;
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}