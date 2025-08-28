<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Security\Permission;

class PromoCard extends DataObject 
{
    private static $table_name = 'PromoCard';
    
    private static $db = [
        'Title' => 'Varchar(255)',
        'Description' => 'Varchar(255)', 
        'DiscountText' => 'Varchar(100)', 
        'ButtonText' => 'Varchar(50)',
        'ButtonLink' => 'Varchar(255)',
        'BackgroundColor' => 'Varchar(20)',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'PromoImage' => Image::class
    ];

    private static $owns = [
        'PromoImage'
    ];

    private static $defaults = [
        'IsActive' => true,
        'ButtonText' => 'Buy Now',
        'BackgroundColor' => '#eaf7fb',
        'SortOrder' => 1,
        'DiscountText' => 'GET 30% OFF'
    ];

    private static $default_sort = 'SortOrder ASC, Created DESC';

    private static $summary_fields = [
        'Title' => 'Title',
        'DiscountText' => 'Discount',
        'BackgroundColor' => 'Background',
        'IsActive.Nice' => 'Active',
        'SortOrder' => 'Sort Order'
    ];

    private static $searchable_fields = [
        'Title',
        'DiscountText',
        'IsActive'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Promo Title'),
            TextField::create('DiscountText', 'Discount Text'),
            TextareaField::create('Description', 'Description')->setRows(2),
            UploadField::create('PromoImage', 'Promo Image')
                ->setFolderName('promo-cards')
                ->setAllowedExtensions(['jpg','jpeg','png','webp']),
            TextField::create('ButtonText', 'Button Text'),
            TextField::create('ButtonLink', 'Button Link'),
            DropdownField::create('BackgroundColor', 'Background Color', [
                '#eaf7fb' => 'Light Blue (#eaf7fb)',
                '#f0f9ff' => 'Sky Blue (#f0f9ff)', 
                '#fef7ed' => 'Light Orange (#fef7ed)',
                '#f0fdf4' => 'Light Green (#f0fdf4)',
                '#fdf2f8' => 'Light Pink (#fdf2f8)',
                '#f8fafc' => 'Light Gray (#f8fafc)'
            ]),
            NumericField::create('SortOrder', 'Sort Order'),
            CheckboxField::create('IsActive', 'Show on Website')
        ]);

        return $fields;
    }

    public static function getActivePromoCards()
    {
        return self::get()->filter('IsActive', true)->sort('SortOrder ASC, Created DESC');
    }

    public function getProcessedButtonLink()
    {
        if (empty($this->ButtonLink)) {
            return '#';
        }
        if (!preg_match('/^(https?:\/\/|#|\/)/', $this->ButtonLink)) {
            return '/' . ltrim($this->ButtonLink, '/');
        }
        return $this->ButtonLink;
    }

    // permission
    public function canView($member = null) { return true; }
    public function canEdit($member = null) { return Permission::check('CMS_ACCESS_CMSMain', 'any', $member); }
    public function canDelete($member = null) { return Permission::check('CMS_ACCESS_CMSMain', 'any', $member); }
    public function canCreate($member = null, $context = []) { return Permission::check('CMS_ACCESS_CMSMain', 'any', $member); }
}