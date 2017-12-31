<?php
    /**
     * FanPress CM Category Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\categories;

    /**
     * Kategorie-Objekt
     * 
     * @package fpcm\model\categories
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    class category extends \fpcm\model\abstracts\model {
        
        /**
         * Kategorie-Name
         * @var string
         */
        protected $name;
        
        /**
         * Kategorie-Icon-Pfad
         * @var string
         */
        protected $iconpath;
        
        /**
         * Gruppen, die diese Kategorie nutzen dürfen
         * @var array
         */
        protected $groups;
        
        /**
         * Action-String für edit-Action
         * @var string
         */        
        protected $editAction = 'categories/edit&categoryid=';
        
        /**
         * Wortsperren-Liste
         * @var \fpcm\model\wordban\items
         * @since FPCM 3.2.0
         */
        protected $wordbanList;
        
        /**
         * Konstruktor
         * @param int $id
         */
        public function __construct($id = null) {
            $this->table = \fpcm\classes\database::tableCategories;
            $this->wordbanList = new \fpcm\model\wordban\items();
            
            parent::__construct($id);
        }

        /**
         * Kategorie-Name
         * @return string
         */
        function getName() {
            return $this->name;
        }

        /**
         * Kategorie-Icon-Pfad
         * @var string
         */
        function getIconPath() {
            return $this->iconpath;
        }
        
        /**
         * Gruppen, die diese Kategorie nutzen dürfen
         * @var array
         */
        function getGroups() {
            return $this->groups;
        }

        /**
         * Kategorie-Name setzen
         * @param string $name
         */
        function setName($name) {
            $this->name = $name;
        }

        /**
         * Kategorie-Icon-Pfad setzen
         * @param string $iconpath
         */
        function setIconPath($iconpath) {
            $this->iconpath = $iconpath;
        }
        
        /**
         * Gruppen, die diese Kategorie nutzen dürfen, setzen
         * @param array $groups
         */
        function setGroups($groups) {
            $this->groups = $groups;
        }
                        
        /**
         * Speichert ein Objekt in der Datenbank
         * @return bool
         */
        public function save() {
            if ($this->categoryExists($this->name)) return false;
            
            $this->removeBannedTexts();
            
            $params = $this->getPreparedSaveParams();
            $params = $this->events->runEvent('categorySave', $params);

            $return = false;
            if ($this->dbcon->insert($this->table, implode(',', array_keys($params)), '?, ?, ?', array_values($params))) {
                $return = true;
            }
            
            $this->id = $this->dbcon->getLastInsertId();
            $this->cache->cleanup();
            
            return $return;              
        }

        /**
         * Aktualisiert ein Objekt in der Datenbank
         * @return bool
         */        
        public function update() {
            
            $this->removeBannedTexts();
            
            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = $this->events->runEvent('categoryUpdate', $params);
            
            $return = false;
            if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            $this->init();
            
            return $return;              
        }

        /**
         * existiert Kategorie?
         * @param string $name
         * @return boolean
         */
        private function categoryExists($name) {
            $counted = $this->dbcon->count("categories", 'id', "name like '$name'");
            return ($counted > 0) ? true : false;
        }
        
        /**
         * Liefert <img>-Tag für Kategorie-Icon zurück
         * @return string
         * @since FPCM 3.1.0
         */
        public function getCategoryImage() {           
            return '<img src="'.$this->getIconPath().'" alt="'.$this->getName().'" title="'.$this->getName().'" class="fpcm-pub-category-icon">';
        }
        
        /**
         * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
         * @return boolean
         * @since FPCM 3.2.0
         */
        private function removeBannedTexts() {

            $this->name  = $this->wordbanList->replaceItems($this->name);
            $this->iconpath  = $this->wordbanList->replaceItems($this->iconpath);
            
            return true;
        }
        
    }
