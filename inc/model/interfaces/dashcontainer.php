<?php
    /**
     * FanPress CM Model Dashboard container Interface
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\interfaces;

    /**
     * Dashboard Container Interface
     * 
     * @package fpcm\model\interfaces
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    interface dashcontainer {     
   
        /**
         * Gibt Container-Name zurück
         * @return string
         */
        public function getName();
   
        /**
         * Gibt Container-Kopfzeile zurück
         * @return string
         */        
        public function getHeadline();
   
        /**
         * Gibt Container-Inhalt zurück
         * @return string
         */
        public function getContent();
   
        /**
         * Gibt Container-Position zurück
         * @return int
         */        
        public function getPosition();
   
        /**
         * Breite des Containers
         * * true = big
         * * false = small
         * @var bool
         */       
        public function getWidth();
   
        /**
         * Höhe des Containers
         * * 0 = small
         * * 1 = middle
         * * 2 = big
         * @var int
         */       
        public function getHeight();
   
        /**
         * Gibt nötige Berechtigungen zurück
         * @return array
         */        
        public function getPermissions();

        /**
         * Gibt benötigte Javascript-Variablen zurück
         * @return array
         * @since FPCM 3.1.3
         */
        public function getJavascriptVars();

        /**
         * Gibt Liste mit zu ladenden Javascript-Dateien zurück
         * @return array
         * @since FPCM 3.1.3
         */
        public function getJavascriptFiles();

        /**
         * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
         * @return array
         * @since FPCM 3.1.3
         */
        public function getControllerViewVars();
    }
