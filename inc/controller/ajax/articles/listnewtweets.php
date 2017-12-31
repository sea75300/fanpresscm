<?php
    /**
     * AJAX article list new tweets controller
     * 
     * AJAX controller for tweet creation
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\articles;
    
    /**
     * AJAX Controller zum erzeugen von Tweets aus Artikelliste
     * 
     * @package fpcm\controller\ajax\articles
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class listnewtweets extends \fpcm\controller\abstracts\ajaxController {
        
        /**
         * Artikel-Listen-objekt
         * @var \fpcm\model\articles\articlelist
         */
        protected $articleList;
        
        /**
         * Array mit Artikel-Objekten
         * @var array
         */
        protected $articleItems;
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->session->exists()) {
                return false;
            }
                        
            if (is_null($this->getRequestVar('ids'))) {
                return false;
            }

            $conditions = new \fpcm\model\articles\search();
            $conditions->ids = array_map('intval', json_decode($this->getRequestVar('ids', array(1,4,7)), true)); 
            
            $articleList = new \fpcm\model\articles\articlelist();            
            $this->articleItems = $articleList->getArticlesByCondition($conditions, false);
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $resOk     = [];
            $resError  = [];

            foreach ($this->articleItems as $article) {
                if (!$article->createTweet()) {
                    $resError[] = $article->getTitle();
                    continue;
                }
                
                $resOk[] = $article->getTitle();
                
                sleep(1);
            }
            
            $messages = array('notice' => 0, 'error' => 0);
            if (count($resOk)) {
                $messages['notice'] = $this->lang->translate('SAVE_SUCCESS_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resOk)));
            }

            if (count($resError)) {
                $messages['error'] = $this->lang->translate('SAVE_FAILED_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resError)));
            }

            print json_encode($messages);
            
            return true;
            
        }

    }
?>