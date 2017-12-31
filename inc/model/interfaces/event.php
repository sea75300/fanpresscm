<?php
    /**
     * FanPress CM event interface
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\interfaces;
    
    /**
     * Event-Interface
     * 
     * @package fpcm\model\interfaces
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    interface event {
        
        /**
         * Event ausführen
         */
        public function run();
        
    }
