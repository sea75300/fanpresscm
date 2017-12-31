<?php
    /**
     * FanPress CM User Roll Model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\users;

    /**
     * Benutzerrolle Objekt
     * 
     * @package fpcm\model\user
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class userRoll extends \fpcm\model\abstracts\model {
        
        /**
         * Bezeichnung der Benutzer-Rolle
         * @var string
         */
        protected $leveltitle = '';
        
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
            $this->table = \fpcm\classes\database::tableRoll;
            $this->wordbanList = new \fpcm\model\wordban\items();
            
            parent::__construct($id);
        }

        /**
         * Liefert Rollenname zurück
         * @return string
         */
        public function getRollName() {
            return $this->leveltitle;
        }

        /**
         * Setzt Rollenname
         * @param string $leveltitle
         */
        public function setRollName($leveltitle) {
            $this->leveltitle = $leveltitle;
        }        

        /**
         * Speichert einen neuen Kommentar in der Datenbank
         * @return boolean
         */         
        public function save() {
            if ($this->rollExists()) return false;
            
            $this->removeBannedTexts();
            
            $this->leveltitle = $this->events->runEvent('userrollSave', $this->leveltitle);
            
            $newId = $this->dbcon->insert($this->table, 'leveltitle', '?', array($this->leveltitle));
            if (!$newId) {
                trigger_error('Failed to create new user roll "'.$this->leveltitle.'"');
                return false;
            }

            $permission = new \fpcm\model\system\permissions();            
            $return     = $permission->addDefault($newId);

            $this->id    = $newId;
            $this->cache->cleanup();
            
            return $return;
        }
        
        /**
         * Löscht eine Benutzer-Rolle in der Datenbank
         * @return boolean
         */         
        public function delete() {
            if ($this->id <= 3) {
                trigger_error('Tried to delete system roll with: '.$this->id);
                return false;
            }            
            
            $return = parent::delete();
            
            $permissions = new \fpcm\model\system\permissions($this->getId());
            
            if ($permissions->delete()) {
                $return = $return && true;
            }
            
            return $return;
        }
        
        /**
         * Aktualisiert eine Benutzer-Rolle in der Datenbank
         * @return boolean
         */          
        public function update() {

            if ($this->id <= 3) {
                trigger_error('Tried to delete system roll with: '.$this->id);
                return false;
            }
            
            $this->removeBannedTexts();
            
            $return = false;
            
            $this->leveltitle = $this->events->runEvent('userrollUpdate', $this->leveltitle);
            
            if ($this->dbcon->update($this->table, array('leveltitle'), array($this->leveltitle, $this->id), 'id = ?')) {
                $return = true;
            }            
            
            $this->cache->cleanup();
            $this->init();            
            
            return $return;
        }

        /**
         * Prüft, ob Benutzer existiert
         * @return bool
         */
        private function rollExists() {
            $result = $this->dbcon->count($this->table, "id", "leveltitle ".$this->dbcon->dbLike()." ?", array($this->leveltitle));
            return ($result > 0 ? true : false);
        }
        
        /**
         * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
         * @return boolean
         * @since FPCM 3.2.0
         */
        private function removeBannedTexts() {

            $this->leveltitle  = $this->wordbanList->replaceItems($this->leveltitle);
            
            return true;
        }
        
    }
