<?php
    /**
     * User list Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\dashboard;

    /**
     * User list dashboard container object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.2.0
     */
    class userlist extends \fpcm\model\abstracts\dashcontainer {

        /**
         * Konstruktor
         */
        public function __construct() {

            $this->cacheName   = 'userlist';
            $this->cacheModule = self::CACHE_M0DULE_DASHBOARD;
            
            parent::__construct();

            $this->cache        = new \fpcm\classes\cache($this->cacheName, self::CACHE_M0DULE_DASHBOARD);
            
            if ($this->cache->isExpired()) {
                $this->renderContent();                
            } else {
                $this->content = $this->cache->read();
            }
                                   
            $this->headline = $this->language->translate('DASHBOARD_USERLIST');
            $this->name     = 'userlist';
            $this->position = 8;
            $this->height   = 0;
        }
        
        /**
         * Content rendern
         */
        private function renderContent() {

            $userlist = new \fpcm\model\users\userList();
             
            $content    = [];
            $content[]  = '<table class="fpcm-ui-table fpcm-ui-users fpcm-ui-large-td">';
            
            $items = $userlist->getUsersActive();
            /* @var $item \fpcm\model\users\author */
            foreach ($items as $item) {
                
                $emailAddress = \fpcm\model\view\helper::escapeVal($item->getEmail());
                
                $content[] = '<tr class="fpcm-small-text">';
                $content[] = '  <td class="fpcm-ui-editbutton-col">';
                $content[] = '  <a class="fpcm-ui-button fpcm-ui-button-blank fpcm-email-btn" href="mailto:'.$emailAddress.'" target="_blank" title="'.$this->language->translate('GLOBAL_WRITEMAIL').'">'.$this->language->translate('GLOBAL_WRITEMAIL').'</a>';
                $content[] = '  </td>';
                $content[] = '  <td>';
                $content[] = '  <strong>'.\fpcm\model\view\helper::escapeVal($item->getDisplayname()).'</strong><br>';
                $content[] = '  <span>'.$emailAddress.'</span>';
                $content[] = '  </td>';               
                $content[] = '</tr>';

            }
            
            $content[]  = '</table>';
            
            $this->content = implode(PHP_EOL, $content);
            
            $this->cache->write($this->content, $this->config->system_cache_timeout);
        }
        
    }