<?php

namespace {

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;

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
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
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
                "CurrentUser" => $this->getCurrentUser()
                // tambahkan logic lainnya
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

        public function isLoggedIn()
        {
            return Security::getCurrentUser() ?true : false;
        }
        // di PageController.php atau Page.php
        public function FilteredMenu($level = 1) 
        {
            $menu = $this->Menu($level);
            return $menu->exclude('URLSegment', 'cart');
        }
        
    }
}
