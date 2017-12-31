<?php
    /**
     * Public article archive list controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\pub;
    
    class showarchive extends showcommon {    

        /**
         * Konstruktor
         * @param API-Modus $apiMode
         */
        public function __construct($apiMode = false) {
            
            parent::__construct();
            
            $this->view = new \fpcm\model\view\pub('showall', 'public');

            $this->view->setShowHeader($apiMode ? false : true);
            $this->view->setShowFooter($apiMode ? false : true);
        }
        
        /**
         * @see \fpcm\controller\abstracts\controller::request()
         * @return boolean
         */
        public function request() {            
            if (!$this->maintenanceMode()) {
                return false;
            }

            $this->limit = defined('FPCM_PUB_LIMIT_ARCHIVE') ? FPCM_PUB_LIMIT_ARCHIVE : $this->config->articles_limit;
            
            parent::request();
            
            $this->cache = new \fpcm\classes\cache('articlearchive'.$this->page, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);
            
            return true;
        }
        
        /**
         * Controller ausführen
         * @return boolean
         */
        public function process() {
            
            parent::process();
            
            $this->view->assign('showToolbars', false);
            
            $parsed = [];
            
            if ($this->cache->isExpired() || $this->session->exists()) {

                $conditions = new \fpcm\model\articles\search();
                $conditions->limit = [$this->limit, $this->listShowLimit];
                $conditions->archived  = 1;
                $conditions->postponed = 0;

                if ($this->config->articles_archive_datelimit) {
                    $conditions->datefrom = $this->config->articles_archive_datelimit;
                }
                
                if ($this->category !== 0) {
                    $conditions->category = $this->category;
                }
                    
                $articles    = $this->articleList->getArticlesByCondition($conditions);
                $this->users = $this->userList->getUsersForArticles(array_keys($articles));

                foreach ($articles as $article) {
                    $parsed[] = $this->assignData($article);
                }
                
                $countConditions           = new \fpcm\model\articles\search();
                $countConditions->archived = true;
                if ($this->category !== 0) {
                    $countConditions->category = $this->category;
                }
                
                if ($this->config->articles_archive_datelimit) {
                    $countConditions->datefrom = $this->config->articles_archive_datelimit;
                }

                $parsed[] = $this->createPagination($this->articleList->countArticlesByCondition($countConditions), 'fpcm/archive');

                $parsed   = $this->events->runEvent('publicShowArchive', $parsed);
                
                if (!$this->session->exists()) $this->cache->write($parsed, $this->config->system_cache_timeout);
            } else {
                $parsed = $this->cache->read();
            }

            $content = implode(PHP_EOL, $parsed);
            if (!$this->isUtf8) {
                $content = utf8_decode($content);
            }
            
            $this->view->assign('content', $content);
            $this->view->assign('systemMode', $this->config->system_mode);
            $this->view->render();
        }
        
        /**
         * Seitennavigation erzeugen
         * @param int $count
         * @param string $action
         * @return string
         */
        protected function createPagination($count, $action = 'fpcm/list') {

            $res = parent::createPagination($count, $action);
            $res = str_replace('</ul>', '<li><a href="?module=fpcm/list" class="fpcm-pub-pagination-page">'.$this->lang->translate('ARTICLES_PUBLIC_ACTIVE').'</a></li>'.PHP_EOL.'</ul>'.PHP_EOL, $res);
            $res = $this->events->runEvent('publicPageinationShowArchive', $res);
            
            return $res;
        }

    }
?>