<?php
    /**
     * Cronjob manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class crons extends \fpcm\controller\abstracts\controller {

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();

            $this->checkPermission = array('system' => 'crons');
            $this->view   = new \fpcm\view\view('system/cronjobs');

        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            

            $cronlist = new \fpcm\model\crons\cronlist();
            $this->view->assign('cronjobList', $cronlist->getCronsData());
            $this->view->assign('currentTime', time());
            $this->view->addJsFiles(['crons.js']);
            $this->view->setHelpLink('hl_options');
            $this->view->render();
        }
        
    }
?>
