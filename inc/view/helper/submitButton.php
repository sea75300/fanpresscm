<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Submit view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    class submitButton extends button {

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            parent::init();
            $this->type   = 'submit';
            $this->class .= ' fpcm-ui-button-submit';
        }

    }
?>