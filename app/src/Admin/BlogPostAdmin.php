<?php

namespace App\Admin;

use App\Models\BlogPost;
use SilverStripe\Admin\ModelAdmin;

class BlogPostAdmin extends ModelAdmin
{
    private static $managed_models = [
        BlogPost::class
    ];

    private static $url_segment = 'blog-posts';

    private static $menu_title = 'Blog Posts';

    private static $menu_icon_class = 'font-icon-news';

    private static $menu_priority = 3;

    public function getList()
    {
        $list = parent::getList();
        return $list->sort('Created DESC');
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        
        // Customize grid field jika diperlukan
        if ($gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass))) {
            $config = $gridField->getConfig();
            
            // Set items per page
            if ($paginator = $config->getComponentByType('SilverStripe\Forms\GridField\GridFieldPaginator')) {
                $paginator->setItemsPerPage(20);
            }
        }
        
        return $form;
    }
}