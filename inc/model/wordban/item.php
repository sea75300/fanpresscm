<?php
    /**
     * FanPress CM Word Ban Item Model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.2.0
     */

    namespace fpcm\model\wordban;

    /**
     * Word Ban Item Object
     * 
     * @package fpcm\model\wordban
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.2.0
     */ 
    class item extends \fpcm\model\abstracts\model {
        
        /**
         * gesuchter Text
         * @var string
         */
        protected $searchtext;
        
        /**
         * Text für Ersetzung
         * @var string
         */
        protected $replacementtext;
        
        /**
         * Text ersetzen
         * @var bool
         * @since FPCM 3.5
         */
        protected $replacetxt;
        
        /**
         * Artikel muss freigeschalten werden
         * @var bool
         * @since FPCM 3.5
         */
        protected $lockarticle;
        
        /**
         * Kommentar muss freigegeben werden
         * @var bool
         * @since FPCM 3.5
         */
        protected $commentapproval;
        
        /**
         * Action-String für edit-Action
         * @var string
         */        
        protected $editAction = 'wordban/edit&itemid=';

        /**
         * Konstruktor
         * @param int $id
         */
        public function __construct($id = null) {

            $this->table = \fpcm\classes\database::tableTexts;
            
            parent::__construct($id);
        }

        /**
         * gesuchter Text zurückgeben
         * @return string
         */
        public function getSearchtext() {
            return $this->searchtext;
        }

        /**
         * Text für Ersetzung zurückgeben
         * @return string
         */
        public function getReplacementtext() {
            return $this->replacementtext;
        }

        /**
         * Status das Text ersetzt wird
         * @return bool
         * @since FPCM 3.5
         */
        function getReplaceTxt() {
            return $this->replacetxt;
        }

        /**
         * Status ob Artikel überprüft werden muss
         * @return bool
         * @since FPCM 3.5
         */
        function getLockArticle() {
            return $this->lockarticle;
        }

        /**
         * Status ob Kommentar freigegeben werden muss
         * @return bool
         * @since FPCM 3.5
         */
        function getCommentApproval() {
            return $this->commentapproval;
        }

        /**
         * gesuchter Text setzen
         * @param string $searchtext
         * @since FPCM 3.5
         */
        public function setSearchtext($searchtext) {
            $this->searchtext = $searchtext;
        }

        /**
         * Text für Ersetzung setzen
         * @param string $replacementtext
         * @since FPCM 3.5
         */
        public function setReplacementtext($replacementtext) {
            $this->replacementtext = $replacementtext;
        }

        /**
         * Status das Text ersetzt wird setzen
         * @param bool $replacetxt
         * @since FPCM 3.5
         */
        function setReplaceTxt($replacetxt) {
            $this->replacetxt = (int) $replacetxt;
        }

        /**
         * Status ob Artikel überprüft werden muss setzen
         * @param bool $lockarticle
         * @since FPCM 3.5
         */
        function setLockArticle($lockarticle) {
            $this->lockarticle = (int) $lockarticle;
        }

        /**
         * Status ob Kommentar freigegeben werden muss setzen
         * @param bool $commentapproval
         * @since FPCM 3.5
         */
        function setCommentApproval($commentapproval) {
            $this->commentapproval = (int) $commentapproval;
        }
        
        /**
         * Speichert Wortsperre
         * @return bool
         */
        public function save() {

            $params = $this->getPreparedSaveParams();
            $params = $this->events->runEvent('wordbanItemSave', $params);

            $return = false;
            if ($this->dbcon->insert($this->table, implode(',', array_keys($params)), implode(', ', $this->getPreparedValueParams(count($params))), array_values($params))) {
                $return = true;
            }

            $this->id = $this->dbcon->getLastInsertId();

            $this->cache->cleanup();
            
            return $return;     
        }

        /**
         * Aktualisiert Wortsperre
         * @return bool
         */
        public function update() {
            $params     = $this->getPreparedSaveParams();
            $params     = $this->events->runEvent('wordbanItemUpdate', $params);
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            
            $return = false;
            if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup();
            
            $this->init();
            
            return $return;   
        }

    }