<?php
    /**
     * FanPress CM Category List Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\categories;

    /**
     * Kategorie-Listen-Objekt
     * 
     * @package fpcm\model\categories
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    class categoryList extends \fpcm\model\abstracts\tablelist {
        
        /**
         * Konstruktor
         */
        public function __construct() {
            $this->table = \fpcm\classes\database::tableCategories;
            
            parent::__construct();
        }
        
        /**
         * Liefert ein array aller Kategorien
         * @return array
         */
        public function getCategoriesAll() {
            $list = $this->dbcon->fetch($this->dbcon->select($this->table), true);
            
            $res = [];
            
            foreach ($list as $listItem) {
                $object = new category();
                if ($object->createFromDbObject($listItem)) {
                    $res[$object->getId()] = $object;
                }
            }
            
            return $res;
        }
        
        /**
         * Liefert ein array aller Kategorien
         * @return array
         * @since FPCM 3.3
         */
        public function getCategoriesNameListAll() {

            $categories = $this->dbcon->fetch($this->dbcon->select($this->table, 'id, name'), true);

            $res = [];
            foreach ($categories as $category) {
                $res[$category->name] = $category->id;
            }

            return $res;

        }

        /**
         * Liefert ein array aller Kategorien, auf welche die angegebene Gruppe zugreifen darf
         * @param int $groupId
         * @return array
         */
        public function getCategoriesByGroup($groupId) {
            
            $where = "groups = ? OR groups ".$this->dbcon->dbLike()." ? OR groups ".$this->dbcon->dbLike()." ? OR groups ".$this->dbcon->dbLike()." ?";
            
            $valueParams = [];
            $valueParams[] = "{$groupId}";
            $valueParams[] = "%;{$groupId};%";
            $valueParams[] = "{$groupId};%";
            $valueParams[] = "%;{$groupId}";
            
            $list = $this->dbcon->fetch(
                    $this->dbcon->select($this->table, '*', $where, $valueParams),
                    true
            );            
            
            $res = [];
            
            foreach ($list as $listItem) {
                $object = new category();
                if ($object->createFromDbObject($listItem)) {
                    $res[$object->getId()] = $object;
                }
            }
            
            return $res;
        }

        /**
         * Liefert ein array aller Kategorien, auf die der aktuelle Benutzer zugreifen darf
         * @return array
         */
        public function getCategoriesCurrentUser() {

            if (!is_object(\fpcm\classes\baseconfig::$fpcmSession) || !\fpcm\classes\baseconfig::$fpcmSession->exists()) {
                return [];
            }

            $groupId = \fpcm\classes\baseconfig::$fpcmSession->getCurrentUser()->getRoll();

            return $this->getCategoriesByGroup($groupId);
        }
        
        /**
         * Liefert ein array aller Kategorie-Namen
         * @return array
         */
        public function getCategoriesNameListCurrent() {
            $categories = $this->getCategoriesCurrentUser();
            
            $res = [];

            foreach ($categories as $category) {
                $res[$category->getName()] = $category->getId();
            }
            
            return $res;
        }

        /**
         * Prüft ob Kategorie existiert
         * @param string $name
         * @return bool
         */
        public function categorieExists($name) {
            $result = $this->dbcon->count($this->table,"id", "name ".$this->dbcon->dbLike()." ?", array($name));
            return ($result > 0) ? true : false;
        }

    }
