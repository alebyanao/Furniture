<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Convert;

class WishlistPage extends SiteTree 
{
    private static $table_name = 'WishlistPage';
}

class WishlistPageController extends ContentController
{
    private static $allowed_actions = [
        'index',
        'addToWishlist',
        'removeFromWishlist',
        'clearWishlist',
        'isInWishlist',
        'getWishlistCount'
    ];

    public function index() 
    {
        return $this->renderWith(['WishlistPage', 'Page']);
    }

    public function getWishlistItems()
    {
        $sessionID = session_id();
        return WishlistItem::get()->filter('SessionID', $sessionID)->sort('DateAdded DESC');
    }

    public function getWishlistCount()
    {
        if ($this->getRequest()->isAjax()) {
            $count = $this->getWishlistItems()->count();
            return $this->jsonResponse(['count' => $count]);
        }
        return $this->getWishlistItems()->count();
    }

    public function addToWishlist(HTTPRequest $request)
    {
        $productID = $request->getVar('ProductID');
        $sessionID = session_id();
        
        if (!$productID) {
            return $this->jsonResponse(['success' => false, 'message' => 'Product ID required']);
        }

        $product = Product::get()->byID($productID);
        if (!$product) {
            return $this->jsonResponse(['success' => false, 'message' => 'Product not found']);
        }

        $existing = WishlistItem::get()->filter([
            'SessionID' => $sessionID,
            'ProductID' => $productID
        ])->first();

        if ($existing) {
            return $this->jsonResponse(['success' => false, 'message' => 'Already in wishlist']);
        }

        $wishlistItem = WishlistItem::create();
        $wishlistItem->ProductID = $productID;
        $wishlistItem->SessionID = $sessionID;
        $wishlistItem->DateAdded = date('Y-m-d H:i:s');
        $wishlistItem->write();

        return $this->jsonResponse([
            'success' => true, 
            'message' => 'Added to wishlist!',
            'count' => $this->getWishlistItems()->count()
        ]);
    }

    public function removeFromWishlist(HTTPRequest $request)
    {
        $productID = $request->getVar('ProductID');
        $sessionID = session_id();
        
        if (!$productID) {
            return $this->jsonResponse(['success' => false, 'message' => 'Product ID required']);
        }

        $wishlistItem = WishlistItem::get()->filter([
            'SessionID' => $sessionID,
            'ProductID' => $productID
        ])->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return $this->jsonResponse([
                'success' => true, 
                'message' => 'Removed from wishlist',
                'count' => $this->getWishlistItems()->count()
            ]);
        }

        return $this->jsonResponse(['success' => false, 'message' => 'Not in wishlist']);
    }

    public function clearWishlist(HTTPRequest $request)
    {
        $sessionID = session_id();
        $items = WishlistItem::get()->filter('SessionID', $sessionID);
        
        foreach ($items as $item) {
            $item->delete();
        }

        return $this->jsonResponse([
            'success' => true, 
            'message' => 'Wishlist cleared',
            'count' => 0
        ]);
    }

    public function isInWishlist(HTTPRequest $request)
    {
        $productID = $request->getVar('ProductID');
        $sessionID = session_id();
        
        if (!$productID) {
            return $this->jsonResponse(['inWishlist' => false]);
        }

        $exists = WishlistItem::get()->filter([
            'SessionID' => $sessionID,
            'ProductID' => $productID
        ])->exists();

        return $this->jsonResponse(['inWishlist' => $exists]);
    }

    private function jsonResponse($data)
    {
        return HTTPResponse::create(
            json_encode($data),
            200
        )->addHeader('Content-Type', 'application/json');
    }

}