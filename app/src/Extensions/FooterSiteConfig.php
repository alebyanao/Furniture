<?php

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;

// Extension untuk SiteConfig
class FooterSiteConfigExtension extends DataExtension
{
    private static $db = [
        // Top Bar
        'TopBarText' => 'Text',
        'TopBarEnabled' => 'Boolean',
        
        // Contact Info
        'ContactDescription' => 'Text',
        'ContactPhone' => 'Varchar(50)',
        'ContactWorkingHours' => 'Varchar(100)',
        
        // Location Info
        'LocationDescription' => 'Text',
        'LocationAddress' => 'Text',
        'LocationMapURL' => 'Varchar(255)',
    ];

    private static $has_one = [
        'FooterLogo' => Image::class,
    ];

    private static $has_many = [
        'SocialMediaLinks' => FooterSocialMedia::class,
        'ServicesList' => FooterService::class,
        'CompanyList' => FooterCompany::class,
        'PaymentMethods' => FooterPaymentMethod::class,
    ];

    private static $owns = [
        'FooterLogo',
        'SocialMediaLinks',
        'ServicesList', 
        'CompanyList',
        'PaymentMethods'
    ];

    private static $defaults = [
        'TopBarEnabled' => true,
        'TopBarText' => 'TAKE CARE OF YOUR HEALTH 25% OFF USE CODE " DOFIX03 "'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        // Remove default tabs yang tidak diperlukan
        $fields->removeByName(['Access', 'Users', 'ErrorPages']);
        
        // Top Bar Tab
        $fields->addFieldsToTab('Root.TopBar', [
            CheckboxField::create('TopBarEnabled', 'Enable Top Bar')
                ->setDescription('Centang untuk menampilkan top bar di website'),
            TextareaField::create('TopBarText', 'Top Bar Text')
                ->setDescription('Text yang akan ditampilkan di top bar (contoh: promo, pengumuman, dll)')
                ->setRows(2)
        ]);
        
        // Footer Tab
        $fields->addFieldToTab('Root.Footer', TabSet::create('FooterTabs'));
        
        // Logo & Branding Tab
        $fields->addFieldToTab('Root.Footer.FooterTabs.Branding', 
            UploadField::create('FooterLogo', 'Footer Logo')
                ->setFolderName('footer-assets')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg'])
                ->setDescription('Logo yang akan ditampilkan di footer')
        );

        // Social Media Tab
        $socialMediaConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab('Root.Footer.FooterTabs.SocialMedia',
            GridField::create('SocialMediaLinks', 'Social Media Links', $this->owner->SocialMediaLinks(), $socialMediaConfig)
        );

        // Services Tab
        $servicesConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab('Root.Footer.FooterTabs.Services',
            GridField::create('ServicesList', 'Services List', $this->owner->ServicesList(), $servicesConfig)
        );

        // Company Info Tab
        $companyConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab('Root.Footer.FooterTabs.Company',
            GridField::create('CompanyList', 'Company Links', $this->owner->CompanyList(), $companyConfig)
        );

        // Contact Info Tab
        $fields->addFieldsToTab('Root.Footer.FooterTabs.Contact', [
            TextareaField::create('ContactDescription', 'Contact Description')
                ->setDescription('Deskripsi kontak perusahaan')
                ->setRows(3),
            TextField::create('ContactPhone', 'Phone Number')
                ->setDescription('Nomor telepon (contoh: +62 21 1234 5678)'),
            TextField::create('ContactWorkingHours', 'Working Hours')
                ->setDescription('Jam kerja (contoh: Senin - Jumat, 09:00 - 17:00 WIB)')
        ]);

        // Location Info Tab
        $fields->addFieldsToTab('Root.Footer.FooterTabs.Location', [
            TextareaField::create('LocationDescription', 'Location Description')
                ->setDescription('Deskripsi lokasi perusahaan')
                ->setRows(3),
            TextareaField::create('LocationAddress', 'Address')
                ->setDescription('Alamat lengkap perusahaan')
                ->setRows(4),
            TextField::create('LocationMapURL', 'Google Maps URL')
                ->setDescription('URL Google Maps untuk lokasi (opsional)')
        ]);

        // Payment Methods Tab
        $paymentConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab('Root.Footer.FooterTabs.PaymentMethods',
            GridField::create('PaymentMethods', 'Payment Methods', $this->owner->PaymentMethods(), $paymentConfig)
        );

        return $fields;
    }

    // Helper methods untuk template
    public function getTopBarText()
    {
        return $this->owner->getField('TopBarText') ?: '';
    }

    public function getIsTopBarEnabled()
    {
        return (bool)$this->owner->getField('TopBarEnabled');
    }

    public function getFooterSocialMedia()
    {
        return $this->owner->SocialMediaLinks()->filter('IsActive', true)->sort('SortOrder');
    }

    public function getFooterServices()
    {
        return $this->owner->ServicesList()->filter('IsActive', true)->sort('SortOrder');
    }

    public function getFooterCompanyLinks()
    {
        return $this->owner->CompanyList()->filter('IsActive', true)->sort('SortOrder');
    }

    public function getFooterPaymentMethods()
    {
        return $this->owner->PaymentMethods()->filter('IsActive', true)->sort('SortOrder');
    }
}

// Social Media DataObject
class FooterSocialMedia extends DataObject
{
    private static $table_name = 'FooterSocialMedia';

    private static $db = [
        'Title' => 'Varchar(100)',
        'URL' => 'Varchar(255)',
        'IconClass' => 'Varchar(100)', // untuk Font Awesome atau icon lainnya
        'AltText' => 'Varchar(100)',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'SiteConfig' => 'SilverStripe\SiteConfig\SiteConfig',
        'CustomIcon' => Image::class // jika ingin upload icon custom
    ];

    private static $owns = [
        'CustomIcon'
    ];

    private static $default_sort = 'SortOrder ASC';

    private static $defaults = [
        'IsActive' => true,
        'SortOrder' => 0
    ];

    private static $summary_fields = [
        'Title' => 'Platform',
        'URL' => 'URL',
        'IconClass' => 'Icon Class',
        'IsActive.Nice' => 'Status'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->removeByName(['SiteConfigID', 'SortOrder']);
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Platform Name')
                ->setDescription('Nama platform (contoh: Facebook, Instagram, Twitter)'),
            TextField::create('URL', 'Social Media URL')
                ->setDescription('URL lengkap ke profile social media'),
            TextField::create('IconClass', 'Icon CSS Class')
                ->setDescription('CSS class untuk icon (contoh: fab fa-facebook-f untuk Font Awesome)'),
            TextField::create('AltText', 'Alt Text')
                ->setDescription('Text alternatif untuk accessibility'),
            UploadField::create('CustomIcon', 'Custom Icon (Optional)')
                ->setFolderName('footer-assets/social-icons')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg'])
                ->setDescription('Upload icon custom jika tidak menggunakan icon font')
        ]);

        return $fields;
    }

    public function getIcon()
    {
        if ($this->CustomIcon()->exists()) {
            return $this->CustomIcon();
        }
        return $this->IconClass;
    }
}

// Services DataObject
class FooterService extends DataObject
{
    private static $table_name = 'FooterService';

    private static $db = [
        'Title' => 'Varchar(100)',
        'URL' => 'Varchar(255)',
        'Description' => 'Text',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'SiteConfig' => 'SilverStripe\SiteConfig\SiteConfig',
        'LinkedPage' => SiteTree::class
    ];

    private static $default_sort = 'SortOrder ASC';

    private static $defaults = [
        'IsActive' => true,
        'SortOrder' => 0
    ];

    private static $summary_fields = [
        'Title' => 'Service Name',
        'URL' => 'URL',
        'IsActive.Nice' => 'Status'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->removeByName(['SiteConfigID', 'SortOrder']);
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Service Name')
                ->setDescription('Nama layanan'),
            TextField::create('URL', 'External URL (Optional)')
                ->setDescription('URL eksternal jika mengarah ke luar website'),
            TextareaField::create('Description', 'Description')
                ->setDescription('Deskripsi singkat layanan (opsional)')
                ->setRows(2)
        ]);

        return $fields;
    }

    public function getLink()
    {
        if ($this->URL) {
            return $this->URL;
        }
        if ($this->LinkedPage()->exists()) {
            return $this->LinkedPage()->Link();
        }
        return '#';
    }
}

// Company Links DataObject
class FooterCompany extends DataObject
{
    private static $table_name = 'FooterCompany';

    private static $db = [
        'Title' => 'Varchar(100)',
        'URL' => 'Varchar(255)',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'SiteConfig' => 'SilverStripe\SiteConfig\SiteConfig',
        'LinkedPage' => SiteTree::class
    ];

    private static $default_sort = 'SortOrder ASC';

    private static $defaults = [
        'IsActive' => true,
        'SortOrder' => 0
    ];

    private static $summary_fields = [
        'Title' => 'Link Name',
        'URL' => 'URL',
        'IsActive.Nice' => 'Status'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->removeByName(['SiteConfigID', 'SortOrder']);
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Link Title')
                ->setDescription('Judul link (contoh: About Us, Privacy Policy)'),
            TextField::create('URL', 'External URL (Optional)')
                ->setDescription('URL eksternal jika mengarah ke luar website')
        ]);

        return $fields;
    }

    public function getLink()
    {
        if ($this->URL) {
            return $this->URL;
        }
        if ($this->LinkedPage()->exists()) {
            return $this->LinkedPage()->Link();
        }
        return '#';
    }
}

// Payment Methods DataObject
class FooterPaymentMethod extends DataObject
{
    private static $table_name = 'FooterPaymentMethod';

    private static $db = [
        'Title' => 'Varchar(100)',
        'AltText' => 'Varchar(100)',
        'IsActive' => 'Boolean',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'SiteConfig' => 'SilverStripe\SiteConfig\SiteConfig',
        'PaymentImage' => Image::class
    ];

    private static $owns = [
        'PaymentImage'
    ];

    private static $default_sort = 'SortOrder ASC';

    private static $defaults = [
        'IsActive' => true,
        'SortOrder' => 0
    ];

    private static $summary_fields = [
        'Title' => 'Payment Method',
        'PaymentImage.CMSThumbnail' => 'Image',
        'IsActive.Nice' => 'Status'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->removeByName(['SiteConfigID', 'SortOrder']);
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Payment Method Name')
                ->setDescription('Nama metode pembayaran (contoh: BCA, Mandiri, OVO)'),
            TextField::create('AltText', 'Alt Text')
                ->setDescription('Text alternatif untuk accessibility'),
            UploadField::create('PaymentImage', 'Payment Method Image')
                ->setFolderName('footer-assets/payment-methods')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'svg'])
                ->setDescription('Logo/gambar metode pembayaran')
        ]);

        return $fields;
    }
}