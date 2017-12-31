<?php
    /**
     * Smiley add controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\smileys;
    
    class smileyadd extends \fpcm\controller\abstracts\controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\files\smiley
         */
        protected $smiley;

        public function __construct() {
            parent::__construct();   
            
            $this->checkPermission = array('system' => 'smileys');

            $this->view = new \fpcm\model\view\acp('add', 'smileys');            
        }

        public function request() {
            
            if ($this->buttonClicked('saveSmiley')) {
                $smileyData = $this->getRequestVar('smiley');
                
                if (empty($smileyData['filename']) || !$smileyData['code']) {
                    $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
                    return true;                    
                }
                
                $this->smiley = new \fpcm\model\files\smiley($smileyData['filename']);
                $this->smiley->setSmileycode($smileyData['code']);
                
                if (!$this->smiley->save()) {
                    $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
                    return true;
                }
                
                $this->cache->cleanup();
                $this->redirect('smileys/list', array('added' => 1));
            }
            
            return true;            
        }
        
        public function process() {
            if (!parent::process()) return false;
            
            if (!is_object($this->smiley)) {
                $this->smiley = new \fpcm\model\files\smiley();
            }

            $smileyList = new \fpcm\model\files\smileylist();

            $files = [];
            foreach ($smileyList->getFolderList() as $file) {
                
                $fileName   = basename($file);
                $url        = \fpcm\classes\baseconfig::$smileyRootPath.$fileName;
                
                $files[] = [
                    'label' => $url,
                    'value' => $fileName
                ];
            }
            
            $this->view->addJsVars([
                'fpcmSmileyFiles'            => $files,
                'fpcmNavigationActiveItemId' => 'submenu-itemnav-item-smileys',
                'fpcmFieldSetAutoFocus'      => 'smileycode'
            ]);

            $this->view->setViewJsFiles(['smileys.js']);
            $this->view->setHelpLink('hl_options');
            $this->view->assign('smiley', $this->smiley);
            $this->view->assign('files', $files);
            $this->view->render();
            
            
        }

    }
?>
