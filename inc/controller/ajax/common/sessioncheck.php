<?php
    /**
     * AJAX session check controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.0
     */
    namespace fpcm\controller\ajax\common;
    
    /**
     * AJAX session check controller
     * 
     * @package fpcm\controller\ajax\commom.addmsg
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.1.0
     */
    class sessioncheck extends \fpcm\controller\abstracts\ajaxController {

        /**
         * Controller-Processing
         */
        public function process() {
            
            if (!is_object($this->session) || !$this->session->exists()) {
                die('0');
            }
            
            die('1');
            
        }

    }
?>