<?php
    /**
     * FanPress CM Module Interface
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\interfaces;
    
    /**
     * Modul-Interface
     * 
     * @package fpcm\model\interfaces
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    interface module {
        
        /**
         * Modul-Key
         * @return string
         */
        public function getKey();
        
        /**
         * Modul-Name
         * @return string
         */
        public function getName();
        
        /**
         * Modul-Beschreibung
         * @return string
         */
        public function getDescription();
        
        /**
         * Modul-Author
         * @return string
         */
        public function getAuthor();
        
        /**
         * Modul-Info-Link
         * @return string
         */
        public function getLink();
        
        /**
         * Modul-Version lokal
         * @return string
         */
        public function getVersion();
        
        /**
         * Modul-Name
         * @param string $filename Dateiname der Config-Datei
         * @return string
         */
        public function getConfig($filename);

        /**
         * Modul-Installation ausführen
         * @return bool
         */
        public function runInstall();

        /**
         * Modul-Deinstallation ausführen
         * @return bool
         */
        public function runUninstall();

        /**
         * Modul-Update ausführen
         * @return bool
         */
        public function runUpdate();
        
        /**
         * Modul aktivieren
         * @return bool
         */
        public function enable();
        
        /**
         * Modul deaktivieren
         * @return bool
         */
        public function disable();
        
    }