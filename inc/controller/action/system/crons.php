<?php
    /**
     * Cronjob manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class crons extends \fpcm\controller\abstracts\controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();

            $this->checkPermission = array('system' => 'crons');
            $this->view   = new \fpcm\model\view\acp('cronjobs', 'system');

        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $cronlist = new \fpcm\model\crons\cronlist();
            $this->view->assign('cronjobList', $cronlist->getCronsData());
            $this->view->assign('currentTime', time());
            $this->view->setViewJsFiles(['crons.js']);
            $this->view->setHelpLink('hl_options');
            $this->view->render();
        }
        
    }
?>
