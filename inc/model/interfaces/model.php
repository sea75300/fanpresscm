<?php
    /**
     * FanPress CM Model Interface
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\interfaces;

    /**
     * Model-Interface
     * 
     * @package fpcm\model\interfaces
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    interface model {     
        
        /**
         * Gibt Object-ID zurück
         * @return int
         */
        public function getId();
        
        /**
         * Speichert ein Objekt in der Datenbank
         * @return bool
         */
        public function save();

        /**
         * Aktualisiert ein Objekt in der Datenbank
         * @return bool
         */        
        public function update();
        
        /**
         * Löscht ein Objekt in der Datenbank
         * @return bool
         */        
        public function delete();       
        
    }
