<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;

class Category extends DataObject 
{
    private static $table_name = 'Category';
    
    private static $db = [
        'Name' => 'Varchar(255)',
        'URLSegment' => 'Varchar(255)',
        'IsActive' => 'Boolean'
    ];

    // Ubah dari has_many ke belongs_many_many
    private static $belongs_many_many = [
        'Products' => 'Product'
    ];

    private static $defaults = [
        'IsActive' => true
    ];

    private static $summary_fields = [
        'Name' => 'Category Name',
        'ProductCount' => 'Products',
        'IsActive.Nice' => 'Active'
    ];

    private static $searchable_fields = [
        'Name',
        'IsActive'
    ];

    private static $default_sort = 'Name ASC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Category Name')
                ->setDescription('e.g. Electronics, Furniture, Clothing, Home & Garden'),
            CheckboxField::create('IsActive', 'Is Active')
                ->setDescription('Uncheck to hide this category')
        ]);

        // Remove unused fields
        $fields->removeByName(['URLSegment']);

        return $fields;
    }

    public function getProductCount()
    {
        return $this->Products()->count();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        
        // Auto-generate URL segment from name
        if (!$this->URLSegment) {
            $this->URLSegment = $this->generateURLSegment($this->Name);
        }
    }

    public function generateURLSegment($title)
    {
        // Simple URL segment generation
        $segment = strtolower($title);
        $segment = preg_replace('/[^a-z0-9]+/', '-', $segment);
        $segment = trim($segment, '-');
        
        // Ensure uniqueness
        $count = 1;
        $originalSegment = $segment;
        while (Category::get()->filter('URLSegment', $segment)->exclude('ID', $this->ID)->exists()) {
            $segment = $originalSegment . '-' . $count;
            $count++;
        }
        
        return $segment;
    }

    public function getActiveProducts()
    {
        return $this->Products()->filter('IsActive', true);
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return parent::canEdit($member);
    }

    public function canDelete($member = null)
    {
        return parent::canDelete($member);
    }
}