<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Security\Security;
    use SilverStripe\View\ArrayData;
    // Tambahkan import untuk Wishlist class
    // use App\Model\Wishlist; // sesuaikan dengan namespace Wishlist Anda

    /**
     * @template T of Page
     * @extends ContentController<T>
     */
    class PageController extends ContentController
    {

        public function getPromoCards()
        {
            return PromoCard::getActivePromoCards();
        }

        public function hasPromoCards()
        {
            return $this->getPromoCards()->exists();
        }

        public function getTwoPromoCards()
        {
            return $this->getPromoCards()->limit(2);
        }

        public function getAllPromoCards()
        {
            return $this->getPromoCards();
        }

        public function getPromoCardsCount()
        {
            return $this->getPromoCards()->count();
        }

        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         */
        private static $allowed_actions = [
            "Index"
        ];
        
        protected $flashMessage = null;

        public function getFlashMessage()
        {
            return $this->flashMessage;
        }

        protected function init()
        {
            parent::init();

            $session = $this->getRequest()->getSession();
            $flash = $session->get('FlashMessage');

            if ($flash) {
                $this->flashMessage = ArrayData::create($flash);
                $session->clear('FlashMessage');
            }
        }

        protected function getCommonData()
        {
            return [
                "IsLoggedIn" => $this->isLoggedIn(),
                "CurrentUser" => $this->getCurrentUser(),
                "WishlistCount" => $this->getWishlistCount(),
                "CartCount" => $this->getCartCount() // tambahkan ini juga
            ];
        }

        public function getCurrentUser()
        {
            return Security::getCurrentUser();
        }

        public function getUserMessage()
        {
             $message = $this->getRequest()->getSession()->get('UserMessage');
            if ($message) {
                $this->getRequest()->getSession()->clear('UserMessage');
                return $message;
            }
            return null;
        }

        public function getWishlistCount()
        {
            if ($this->isLoggedIn()) {
                $user = $this->getCurrentUser();
                if ($user && $user->exists()) {
                    try {
                        $count = Wishlist::get()->filter('MemberID', $user->ID)->count();
                        return $count ? (int) $count : 0;
                    } catch (Exception $e) {
                        // Log error atau debug
                        error_log("Error getting wishlist count: " . $e->getMessage());
                        return 0;
                    }
                }
            }
            return 0;
        }

        public function isLoggedIn()
        {
            return Security::getCurrentUser() ? true : false;
        }

         public function getCartCount()
        {
            $user = Security::getCurrentUser();
            if ($user && $user->exists()) {
                return CartItem::get()->filter('MemberID', $user->ID)->count();
            }
            return 0;
        }
        
    }
}