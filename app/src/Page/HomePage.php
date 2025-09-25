<?php

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;

class HomePage extends Page
{
    private static $table_name = 'HomePage';

    private static $db = [
        'HeroBannerEnabled' => 'Boolean',
        'FeaturesEnabled' => 'Boolean'
    ];

    private static $defaults = [
        'HeroBannerEnabled' => true,
        'FeaturesEnabled' => true
    ];

    private static $description = 'Main homepage with hero banner slider and features';
    private static $singular_name = 'Home Page';
    private static $plural_name = 'Home Pages';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        // ===== HERO BANNER SETTINGS =====
        $fields->addFieldToTab('Root.HeroSettings', 
            HeaderField::create('HeroBannerHeader', 'Hero Banner Configuration', 2)
        );

        $fields->addFieldToTab('Root.HeroSettings', 
            CheckboxField::create('HeroBannerEnabled', 'Enable Hero Banner Section')
                ->setDescription('Centang untuk menampilkan section hero banner di homepage.')
        );

        $heroInfo = '<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">';
        $heroInfo .= '<h4 style="margin-top: 0;">Informasi Hero Banner:</h4>';
        $heroInfo .= '<ul style="margin-bottom: 0;">';
        $heroInfo .= '<li>Hero banner dikelola di menu <strong>"Content Management → Hero Banners"</strong></li>';
        $heroInfo .= '<li>Anda dapat menambah multiple gambar untuk slideshow otomatis</li>';
        $heroInfo .= '<li>Title dan Description cukup diisi sekali per banner</li>';
        $heroInfo .= '<li>Slideshow akan aktif otomatis jika ada gambar</li>';
        $heroInfo .= '</ul>';
        $heroInfo .= '</div>';

        $fields->addFieldToTab('Root.HeroSettings', 
            LiteralField::create('HeroInfo', $heroInfo)
        );

        // ===== FEATURES SETTINGS =====
        $fields->addFieldToTab('Root.FeaturesSettings', 
            HeaderField::create('FeaturesHeader', 'Features Section Configuration', 2)
        );

        $fields->addFieldToTab('Root.FeaturesSettings', 
            CheckboxField::create('FeaturesEnabled', 'Enable Features Section')
                ->setDescription('Centang untuk menampilkan features section di homepage.')
        );

        $featuresInfo = '<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">';
        $featuresInfo .= '<h4 style="margin-top: 0;">Informasi Features Section:</h4>';
        $featuresInfo .= '<ul style="margin-bottom: 0;">';
        $featuresInfo .= '<li>Features dikelola di menu <strong>"Content Management → Features Section"</strong></li>';
        $featuresInfo .= '<li>Maksimal 4 features untuk tampilan yang optimal</li>';
        $featuresInfo .= '<li>Bisa menggunakan icon image atau FontAwesome</li>';
        $featuresInfo .= '<li>Urutan bisa diatur dengan Sort Order</li>';
        $featuresInfo .= '</ul>';
        $featuresInfo .= '</div>';

        $fields->addFieldToTab('Root.FeaturesSettings', 
            LiteralField::create('FeaturesInfo', $featuresInfo)
        );

        return $fields;
    }

    public function canHaveChildren()
    {
        return true;
    }

    // ===== HERO BANNER METHODS =====
    public function getHeroBannerEnabled()
    {
        $enabled = $this->getField('HeroBannerEnabled');
        return $enabled !== null ? (bool) $enabled : true;
    }

    public function isHeroBannerEnabled()
    {
        return $this->getHeroBannerEnabled();
    }

    // ===== FEATURES METHODS =====
    public function getFeaturesEnabled()
    {
        $enabled = $this->getField('FeaturesEnabled');
        return $enabled !== null ? (bool) $enabled : true;
    }

    public function isFeaturesEnabled()
    {
        return $this->getFeaturesEnabled();
    }

}