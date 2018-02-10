<?php
    /**
     * User list Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2018, Stefan Seehafer
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
                                   
            $this->name      = 'userlist';

            $this->getCacheName();
            
            parent::__construct();
            
            if ($this->cache->isExpired($this->cacheName)) {
                $this->renderContent();                
            } else {
                $this->content = $this->cache->read($this->cacheName);
            }

            $this->headline  = $this->language->translate('DASHBOARD_USERLIST');
            $this->position  = 8;
            $this->height    = 0;
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
                
                $emailAddress = (new \fpcm\view\helper\escape($item->getEmail()));
                
                $content[] = '<tr class="fpcm-small-text">';
                $content[] = '  <td class="fpcm-ui-editbutton-col">';
                $content[] = (new \fpcm\view\helper\linkButton(uniqid('createMail')))->setUrl('mailto:'.$emailAddress)->setText('GLOBAL_WRITEMAIL')->setTarget('_blank')->setIcon('envelope-o')->setIconOnly(true);
                $content[] = '  </td>';
                $content[] = '  <td>';
                $content[] = '  <strong>'.(new \fpcm\view\helper\escape($item->getDisplayname())).'</strong><br>';
                $content[] = '  <span>'.$emailAddress.'</span>';
                $content[] = '  </td>';               
                $content[] = '</tr>';

            }
            
            $content[]  = '</table>';
            
            $this->content = implode(PHP_EOL, $content);
            
            $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
        }
        
    }