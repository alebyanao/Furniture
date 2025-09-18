<?php

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\EmailField;
use SilverStripe\ORM\DataExtension;

class CustomSiteConfig extends DataExtension
{
    private static $db = [
        // Company Info
        'CompanyEmail' => 'Varchar(255)',
        'CompanyPhone' => 'Varchar(50)',
        'CompanyAddress' => 'Text',
        'CompanyWorkingHours' => 'Varchar(100)',
        'CompanyMapURL' => 'Varchar(255)',
        
        // Top Bar
        'TopBarText' => 'Text',
        'TopBarEnabled' => 'Boolean',
        
        // Contact Info
        'ContactDescription' => 'Text',
        
        // Social Media Links
        'FacebookURL' => 'Varchar(255)',
        'InstagramURL' => 'Varchar(255)',
        'TwitterURL' => 'Varchar(255)',
        'LinkedInURL' => 'Varchar(255)',
        'YouTubeURL' => 'Varchar(255)',
        'WhatsAppNumber' => 'Varchar(50)',
        
        // Services
        'Service1Title' => 'Varchar(100)',
        'Service1URL' => 'Varchar(255)',
        'Service2Title' => 'Varchar(100)',
        'Service2URL' => 'Varchar(255)',
        'Service3Title' => 'Varchar(100)',
        'Service3URL' => 'Varchar(255)',
        'Service4Title' => 'Varchar(100)',
        'Service4URL' => 'Varchar(255)',
        'Service5Title' => 'Varchar(100)',
        'Service5URL' => 'Varchar(255)',
        
        // Company Links
        'AboutUsURL' => 'Varchar(255)',
        'PrivacyPolicyURL' => 'Varchar(255)',
        'TermsConditionsURL' => 'Varchar(255)',
        'CareersURL' => 'Varchar(255)',
        'ContactUsURL' => 'Varchar(255)',
        
        // Footer Text
        'FooterCopyrightText' => 'Text',
        'FooterDescription' => 'Text',
    ];

    private static $has_one = [
        'FooterLogo' => Image::class,
        'PaymentMethodImage1' => Image::class,
        'PaymentMethodImage2' => Image::class,
        'PaymentMethodImage3' => Image::class,
        'PaymentMethodImage4' => Image::class,
        'PaymentMethodImage5' => Image::class,
    ];

    private static $owns = [
        'FooterLogo',
        'PaymentMethodImage1',
        'PaymentMethodImage2',
        'PaymentMethodImage3',
        'PaymentMethodImage4',
        'PaymentMethodImage5',
    ];

    private static $defaults = [
        'TopBarEnabled' => true,
        'TopBarText' => 'TAKE CARE OF YOUR HEALTH 25% OFF USE CODE " DOFIX03 "',
        'FooterCopyrightText' => 'All Rights Reserved.',
    ];

    // Add forTemplate method to fix the error
    public function forTemplate()
    {
        return $this->owner->Title;
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Main', [
            // Company Info
            EmailField::create('CompanyEmail', 'Company Email'),
            TextField::create('CompanyPhone', 'Company Phone'),
            TextareaField::create('CompanyAddress', 'Company Address')->setRows(4),
            TextField::create('CompanyWorkingHours', 'Working Hours'),
            TextField::create('CompanyMapURL', 'Google Maps URL'),
            
            // Top Bar
            CheckboxField::create('TopBarEnabled', 'Enable Top Bar'),
            TextareaField::create('TopBarText', 'Top Bar Text')->setRows(2),
            
            // Footer
            UploadField::create('FooterLogo', 'Footer Logo')
                ->setFolderName('footer-assets')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg']),
            TextareaField::create('FooterDescription', 'Footer Description')->setRows(3),
            TextField::create('FooterCopyrightText', 'Copyright Text'),
            
            // Contact & Location
            TextareaField::create('ContactDescription', 'Contact Description')->setRows(3),
            
            // Social Media
            TextField::create('FacebookURL', 'Facebook URL'),
            TextField::create('InstagramURL', 'Instagram URL'),
            TextField::create('TwitterURL', 'Twitter URL'),
            TextField::create('LinkedInURL', 'LinkedIn URL'),
            TextField::create('YouTubeURL', 'YouTube URL'),
            TextField::create('WhatsAppNumber', 'WhatsApp Number'),
            
            // Services
            TextField::create('Service1Title', 'Service 1 Title'),
            TextField::create('Service1URL', 'Service 1 URL'),
            TextField::create('Service2Title', 'Service 2 Title'),
            TextField::create('Service2URL', 'Service 2 URL'),
            TextField::create('Service3Title', 'Service 3 Title'),
            TextField::create('Service3URL', 'Service 3 URL'),
            TextField::create('Service4Title', 'Service 4 Title'),
            TextField::create('Service4URL', 'Service 4 URL'),
            TextField::create('Service5Title', 'Service 5 Title'),
            TextField::create('Service5URL', 'Service 5 URL'),
            
            // Company Links
            TextField::create('AboutUsURL', 'About Us URL'),
            TextField::create('PrivacyPolicyURL', 'Privacy Policy URL'),
            TextField::create('TermsConditionsURL', 'Terms & Conditions URL'),
            TextField::create('CareersURL', 'Careers URL'),
            TextField::create('ContactUsURL', 'Contact Us URL'),
            
            // Payment Methods
            UploadField::create('PaymentMethodImage1', 'Payment Method 1')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg']),
            UploadField::create('PaymentMethodImage2', 'Payment Method 2')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg']),
            UploadField::create('PaymentMethodImage3', 'Payment Method 3')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg']),
            UploadField::create('PaymentMethodImage4', 'Payment Method 4')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg']),
            UploadField::create('PaymentMethodImage5', 'Payment Method 5')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg'])
        ]);

        return $fields;
    }
}