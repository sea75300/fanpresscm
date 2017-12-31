<?php
    /**
     * Welcome Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\dashboard;

    /**
     * Welcome dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class welcome extends \fpcm\model\abstracts\dashcontainer {

        /**
         * Session
         * @var \fpcm\model\system\session
         */
        protected $session;     

        /**
         * Konstruktor
         */
        public function __construct() {

            parent::__construct();            

            $this->session = \fpcm\classes\baseconfig::$fpcmSession;
            $this->headline = $this->language->translate('WELCOME_HEADLINE', array('{{username}}' => $this->session->currentUser->getDisplayname()));
            $this->content  = $this->language->translate('WELCOME_CONTENT', array('{{profilelink}}' => 'index.php?module=system/profile'));
            $this->name     = 'welcome';            
            $this->position = 1;
        }
        
    }