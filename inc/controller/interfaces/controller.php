<?php
    /**
     * Base controller interface
     * 
     * Controller base interface
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\interfaces;
    
    /**
     * Controller interface
     * 
     * @package fpcm\controller\interfaces\controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    interface controller {

        /**
         * Request-Handler
         * @return boolean, false verhindert Ausführung von @see process()
         */ 
        public function request();

        /**
         * Controller-Processing
         */ 
        public function process();

    }
?>