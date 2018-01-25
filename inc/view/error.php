<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view;
    
    /**
     * Error View Objekt
     * 
     * @package fpcm\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    final class error extends \fpcm\view\view {
        
        protected $errorMessage;

        public function __construct($errorMessage)
        {
            parent::__construct('common/error');
            $this->errorMessage = $this->language->translate($errorMessage);
        }
        
        public function render()
        {
            $this->assign('errorMessage', $this->errorMessage);
            return parent::render();
            exit;
        }

    }
?>