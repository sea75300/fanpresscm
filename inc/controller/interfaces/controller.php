<?php
    /**
     * Base controller interface
     * 
     * Controller base interface
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
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
         * Request processing
         * @return boolean, false prevent execution of @see process()
         */ 
        public function request();

        /**
         * Access check processing
         * @return boolean, false prevent execution of @see request() @see process()
         */ 
        public function hasAccess();

        /**
         * Controller-Processing
         * @return boolean
         */ 
        public function process();

    }
?>