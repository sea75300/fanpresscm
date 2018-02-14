<?php
    /**
     * Module list entry object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\modules;

    /**
     * Module-Objekt für Module-Liste
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class listitem extends \fpcm\model\abstracts\module {

        /**
         * Installation durchführen
         * @return bool
         */
        public function runInstall() {
            return true;
        }

        /**
         * Deinstallation durchführen
         * @return bool
         */
        public function runUninstall() {
            return true;
        }

        /**
         * Update durchführen
         * @return bool
         */
        public function runUpdate() {
            return true;
        }
        
        /**
         * Module in DB speichern
         * @return bool
         */
        final public function save() {
            return $this->dbcon->insert($this->table, ['modkey' => $this->modkey, 'version' => $this->versionRemote, 'status' => $this->version]);
        }
        
        /**
         * Module in DB speichern
         * @return bool
         */
        final public function update() {
            return $this->dbcon->update($this->table, array('version'), array($this->versionRemote, $this->modkey), 'modkey = ?');
        }
        
        /**
         * Prüfen, ob Version von Modul-Server größer als lokale Version ist
         * @return bool
         */
        final public function hasUpdates() {
            if (!$this->isInstalled) return false;
            
            return version_compare($this->versionRemote, $this->version, '>') ? 1 : 0;
        }

    }
