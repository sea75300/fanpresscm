<?php
    /**
     * Recent articles Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\dashboard;

    /**
     * Recent articles dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class fpcmnews extends \fpcm\model\abstracts\dashcontainer {

        /**
         * Konstruktor
         */
        public function __construct() {

            $this->cacheName   = 'fpcmnews';
            $this->cacheModule = self::CACHE_M0DULE_DASHBOARD;
            
            parent::__construct();
            
            if ($this->cache->isExpired()) {
                $this->renderContent();                
            } else {
                $this->content = $this->cache->read();
            }
                                   
            $this->headline = $this->language->translate('RECENT_FPCMNEWS');
            $this->name     = 'fpcmnews';
            $this->position = 7;
            $this->height   = 1;
        }
        
        /**
         * Content rendern
         */
        private function renderContent() {

            if (!\fpcm\classes\baseconfig::canConnect()) {
                $this->content = $this->language->translate('GLOBAL_NOTFOUND2');
                return false;
            }
            
            $xmlString = simplexml_load_file('https://nobody-knows.org/category/fanpress-cm/feed/');
            
            if (!$xmlString) {
                $this->content = $this->language->translate('GLOBAL_NOTFOUND2');
                return false;
            }
            
            $items     = $xmlString->channel->item;
            
            $idx = 0;
            
            $content    = [];
            $content[]  = '<table class="fpcm-ui-table fpcm-ui-rssnews fpcm-ui-large-td">';
            foreach ($items as $item) {
                if ($idx >= 10) {
                    break;
                }
                
                $content[] = '<tr class="fpcm-small-text">';
                $content[] = '  <td class="fpcm-ui-articlelist-open">';
                $content[] = '  <a class="fpcm-ui-button fpcm-ui-button-blank fpcm-openlink-btn" href="'.\fpcm\model\view\helper::escapeVal(strip_tags($item->link)).'" target="_blank" title="'.$this->language->translate('GLOBAL_OPENNEWWIN').'">'.$this->language->translate('GLOBAL_OPENNEWWIN').'</a>';
                $content[] = '  </td>';
                $content[] = '  <td>';
                $content[] = '  <strong>'.\fpcm\model\view\helper::escapeVal(strip_tags($item->title)).'</strong><br>';
                $content[] = '  <span>'.date($this->config->system_dtmask, strtotime($item->pubDate)).'</span>';
                $content[] = '  </td>';               
                $content[] = '</tr>';
                $idx++;
            }
            
            $content[]  = '</table>';
            
            $this->content = implode(PHP_EOL, $content);
            
            $this->cache->write($this->content, $this->config->system_cache_timeout);
        }
        
    }