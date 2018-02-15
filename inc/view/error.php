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
        protected $backController;

        public function __construct($errorMessage, $backController = null)
        {
            parent::__construct('common/error');
            $this->errorMessage     = $this->language->translate($errorMessage);
            $this->backController   = trim($backController) ? trim($backController) : '';
            $this->showHeaderFooter(view::INCLUDE_HEADER_NONE);
        }
        
        public function render($exit = true)
        {
            $this->assign('errorMessage', $this->errorMessage);
            $this->assign('backController', \fpcm\classes\tools::getFullControllerLink($this->backController));
            parent::render();
            if ($exit) {
                exit;
            }

            return false;
        }

    }
?>