<?php

use SilverStripe\ORM\DataObject;

class WishlistItem extends DataObject 
{
    private static $table_name = 'WishlistItem';
    
    private static $db = [
        'SessionID' => 'Varchar(255)',
        'UserEmail' => 'Varchar(255)',
        'DateAdded' => 'Datetime'
    ];

    private static $has_one = [
        'Product' => Product::class
    ];

    private static $summary_fields = [
        'Product.Name' => 'Product',
        'UserEmail' => 'Email',
        'DateAdded' => 'Date Added'
    ];

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();
        
        if (!$this->DateAdded) {
            $this->DateAdded = date('Y-m-d H:i:s');
        }
        
        if (!$this->SessionID) {
            $this->SessionID = session_id();
        }
    }

    public function getFormattedDateAdded()
    {
        return date('d M Y', strtotime($this->DateAdded));
    }
}