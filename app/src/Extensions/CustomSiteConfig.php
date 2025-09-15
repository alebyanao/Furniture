<?php

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\EmailField;

class CustomSiteConfig extends DataExtension
{
    private static $db = [
        'CompanyEmail' => 'Varchar(255)',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.Main',
            EmailField::create('CompanyEmail', 'Company Email')
        );
    }
}