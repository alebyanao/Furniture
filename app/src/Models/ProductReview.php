<?php


use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;

class ProductReview extends DataObject
{
    private static $table_name = 'ProductReview';

    private static $db = [
        'Name' => 'Varchar(255)',
        'Rating' => 'Int',
        'Comment' => 'Text'
    ];

    private static $has_one = [
        'Product' => 'Product'
    ];

    private static $defaults = [
        'Rating' => 5
    ];

    private static $summary_fields = [
        'Name' => 'Customer Name',
        'Product.Name' => 'Product',
        'Rating' => 'Rating',
        'Created.Nice' => 'Date'
    ];

    private static $default_sort = 'Created DESC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Customer Name'),
            DropdownField::create('ProductID', 'Product', \Product::get()->map('ID', 'Name'))
                ->setEmptyString('-- Select Product --'),
            DropdownField::create('Rating', 'Rating', [
                1 => '1 Star',
                2 => '2 Stars', 
                3 => '3 Stars',
                4 => '4 Stars',
                5 => '5 Stars'
            ]),
            TextareaField::create('Comment', 'Review Comment')
        ]);

        return $fields;
    }

    public function getTitle()
    {
        return $this->Name . ' - ' . $this->Rating . ' stars';
    }

    public function getStarDisplay()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->Rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }
}