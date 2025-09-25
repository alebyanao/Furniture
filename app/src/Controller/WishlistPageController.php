<?php

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
class WishlistPageController extends PageController
{

    
    private static $allowed_actions = [
        'product',
        'add',
        'remove',
        'index'
    ];

    private static $url_segment = 'wishlist';

    private static $table_name = 'wishlist';

    private static $url_handlers = [
        'product/$ID' => 'product',
        'add/$ID' => 'add',
        'remove/$ID' => 'remove',
        '' => 'index'
    ];

    public function index(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $user = $this->getCurrentUser();
        $wishlists = Wishlist::get()->filter('MemberID', $user->ID);
        $data = array_merge($this->getCommonData(), [
            'Title' => 'My Wishlists',
            'Wishlists' => $wishlists
        ]);

        return $this->customise($data)->renderWith(['WishlistPage', 'Page']);
    }

    public function add(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
        }

        $productID = $request->param('ID');
        $product = Product::get()->byID($productID);

        if (!$product) {
            return $this->httpError(404);
        }

        $user = $this->getCurrentUser();
        $existingWishlist = Wishlist::get()->filter([
            'ProductID' => $productID,
            'MemberID' => $user->ID
        ])->first();

        if (!$existingWishlist) {
            $wishlist = Wishlist::create();
            $wishlist->ProductID = $productID;
            $wishlist->MemberID = $user->ID;
            $wishlist->write();
        }

        return $this->redirectBack();
    }

    public function remove(HTTPRequest $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/login/');
        }

        $wishlistID = $request->param('ID');
        $user = $this->getCurrentUser();

        $wishlist = Wishlist::get()->filter([
            'ID' => $wishlistID,
            'MemberID' => $user->ID
        ])->first();

        if ($wishlist) {
            $wishlist->delete();
        }

        return $this->redirectBack();
    }

    public function product(HTTPRequest $request)
    {
        $id = $request->param('ID');
        $product = Product::get()->byID($id);
        
        if (!$product) {
            return $this->httpError(404, 'Product not found');
        }
        
        return $this->customise([
            'Product' => $product
        ])->renderWith(['ProductDetailShow', 'Page']);
    }

}