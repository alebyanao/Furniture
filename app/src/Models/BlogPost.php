<?php

namespace App\Models;

use App\Pages\BlogPage;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Security\Permission;

class BlogPost extends DataObject
{
    private static $table_name = 'BlogPost';

    private static $db = [
        'Title' => 'Varchar(255)',
        'Summary' => 'Text',
        'Content' => 'HTMLText'
    ];

    private static $has_one = [
        'FeaturedImage' => Image::class,
    ];

    private static $owns = [
        'FeaturedImage'
    ];

    private static $summary_fields = [
        'Title' => 'Judul',
        'Summary' => 'Deskripsi Singkat',
        'Created' => 'Tanggal Dibuat'
    ];

    private static $default_sort = 'Created DESC';

    public function getCMSFields()
    {
        $fields = FieldList::create([
            TextField::create('Title', 'Judul Berita'),
            TextareaField::create('Summary', 'Deskripsi Singkat')
                ->setDescription('Ringkasan singkat yang akan ditampilkan di halaman utama blog'),
            UploadField::create('FeaturedImage', 'Gambar Utama')
                ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif'])
                ->setFolderName('blog-images'),
            HTMLEditorField::create('Content', 'Konten Berita')
                ->setRows(15)
        ]);

        return $fields;
    }

    public function getThumbnail()
    {
        if ($this->FeaturedImage()->exists()) {
            return $this->FeaturedImage()->ScaleWidth(300);
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