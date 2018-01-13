<?php
    /**
     * Module config controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\modules;
    
    class config extends \fpcm\controller\abstracts\controller {

        /**
         * Modul-Key
         * @var string
         */
        protected $moduleKey = false;

        /**
         * Controller-View
         * @var \fpcm\view\view
         */        
        protected $view;

        public function __construct() {
            parent::__construct();
            
            $this->view = new \fpcm\view\view('ajax', 'common');
            
            $this->checkPermission = array('system' => 'options', 'modules' => 'configure');      
        }
        
        public function request() {

            if (is_null($this->getRequestVar('key'))) $this->redirect('modules/list');
            
            $this->moduleKey = $this->getRequestVar('key');

            $modulePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $this->moduleKey);
            
            if (!is_dir($modulePath)) {
                $this->moduleKey = false;
            }
            
            
            return true;
        }

        public function process() {
            if (!parent::process()) return false;

            if (!$this->moduleKey) {
                $this->view = new \fpcm\view\error();
                $this->view->setMessage('Selected module not found in installed modules!');
                return $this->view->render();
            }
            
            $this->events->runEvent('acpConfig', $this->moduleKey);
            $this->view->setHelpLink('hl_modules');
            $this->view->render();
            
        }
        
    }
?>