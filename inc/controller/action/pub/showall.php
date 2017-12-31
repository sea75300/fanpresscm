<?php
    /**
     * Public article list controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\pub;
    
    class showall extends showcommon {

        /**
         * Konstruktor
         * @param bool $apiMode API-Modus
         */
        public function __construct($apiMode = false) {
            
            $this->apiMode      = $apiMode;
            
            parent::__construct();
            
            $this->view = new \fpcm\model\view\pub('showall', 'public');

            $this->view->setShowHeader($this->apiMode ? false : true);
            $this->view->setShowFooter($this->apiMode ? false : true);
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->maintenanceMode()) {
                return false;
            }

            $this->limit = defined('FPCM_PUB_LIMIT_LISTALL') ? FPCM_PUB_LIMIT_LISTALL : $this->config->articles_limit;
            
            parent::request();

            $this->cache = new \fpcm\classes\cache('articlelist'.$this->page.$this->category, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);
            
            return true;
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            parent::process();
            
            $parsed = [];
            
            if ($this->cache->isExpired() || $this->session->exists()) {
                
                $conditions = new \fpcm\model\articles\search();
                $conditions->limit = [$this->limit, $this->listShowLimit];
                $conditions->archived  = 0;
                $conditions->postponed = 0;
                $conditions->orderby   = ['pinned DESC, '.$this->config->articles_sort.' '.$this->config->articles_sort_order];

                if ($this->category !== 0) {
                    $conditions->category = $this->category;
                }

                $articles    = $this->articleList->getArticlesByCondition($conditions);
                $this->users = $this->userList->getUsersForArticles(array_keys($articles));

                foreach ($articles as $article) {
                    $parsed[] = $this->assignData($article);
                }

                $countConditions         = new \fpcm\model\articles\search();
                $countConditions->active = 1;
                if ($this->category !== 0) {
                    $countConditions->category = $this->category;
                }
                
                $parsed[] = $this->createPagination($this->articleList->countArticlesByCondition($countConditions));                
                $parsed   = $this->events->runEvent('publicShowAll', $parsed);
                
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
            if ($this->config->articles_archive_show) {
                $res = str_replace('</ul>', '<li><a href="?module=fpcm/archive" class="fpcm-pub-pagination-archive">'.$this->lang->translate('ARTICLES_PUBLIC_ARCHIVE').'</a></li>'.PHP_EOL.'</ul>'.PHP_EOL, $res);
            }
            
            $res = $this->events->runEvent('publicPageinationShowAll', $res);
                        
            return $res ? $res : '';
        }

    }
?>