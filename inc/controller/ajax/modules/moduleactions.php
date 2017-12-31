<?php
    /**
     * AJAX module manager actions controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\modules;
    
    /**
     * AJAX-Controller der die Aktionen im Module-Manager ausführt
     * 
     * @package fpcm\controller\ajax\modules\moduleactions
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class moduleactions extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\modules\moduleactions;
        
        /**
         * Liste von Modulkeys
         * @var array
         */
        protected $keys;
        
        /**
         * auszuführende Aktion
         * @var string
         */
        protected $action;

        /**
         * Modulelist-Objekt
         * @var \fpcm\model\modules\modulelist
         */
        protected $modulelist;
        
        /**
         * Controller-View
         * @var \fpcm\model\view\ajax
         */
        protected $view;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();

            $this->checkPermission = array('system' => 'options', 'modules' => 'configure');
            
            $this->modulelist = new \fpcm\model\modules\modulelist();
            
            $this->view = new \fpcm\model\view\ajax('list_inner', 'modules');
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->session->exists() && !$this->permissions->check($this->checkPermission)) {
                return false;
            }
            
            if (is_null($this->getRequestVar('action')) || is_null($this->getRequestVar('keys'))) return true;
            
            $this->cache->cleanup();
            
            $this->action   = $this->getRequestVar('action');            
            $this->keys     = $this->getRequestVar('keys', array(1,4,7));

            if (!is_array($this->keys)) {
                $this->keys = json_decode($this->keys, true);
            }
            
            if (!is_array($this->keys)) return true;
            
            $this->keys     = array_map('trim', $this->keys);
            $this->keys     = array_map('base64_decode', $this->keys);
            
            switch ($this->action) {
                case 'disable' :
                    if (!$this->permissions->check(array('modules' => 'enable'))) return true;
                    $res = $this->modulelist->disableModules($this->keys);
                    break;
                case 'enable' :
                    if (!$this->permissions->check(array('modules' => 'enable'))) return true;
                    $res = $this->modulelist->enableModules($this->keys);
                    break;
                case 'uninstall' :
                    if (!$this->permissions->check(array('modules' => 'uninstall'))) return true;
                    $res = $this->modulelist->uninstallModules($this->keys);
                    break;
                case 'install' :
                    if (!$this->permissions->check(array('modules' => 'install'))) return true;
                    
                    $this->keys = array_diff($this->keys, $this->modulelist->getInstalledModules());
                    $tempFile = new \fpcm\model\files\tempfile('installkeys', json_encode($this->keys));
                    if (!$tempFile->save()) {
                        trigger_error('Unable to save module keys to temp file');
                        return true;
                    }
                    break;
                case 'update' :
                    if (!$this->permissions->check(array('modules' => 'install'))) return true;
                    
                    $updater = new \fpcm\model\updater\modules();
                    $updater->getModulelist();
                    
                    $remotes = $updater->getRemoteData();

                    $this->keys = array_intersect($this->keys, $this->modulelist->getInstalledModules());
                    
                    $versionKeys = [];
                    foreach ($this->keys as $key) {
                        $versionKeys[] = $key.'_version'.$remotes[$key]['version'];
                    }

                    $tempFile = new \fpcm\model\files\tempfile('installkeys', json_encode($versionKeys));
                    if (!$tempFile->save()) {
                        trigger_error('Unable to save module keys to temp file');
                        return true;
                    }
                    break;
            }
            
            if (!isset($res)) return true;
            
            if ($res) {
                $this->view->addNoticeMessage('MODULES_SUCCESS_'.strtoupper($this->action));
            } else {
                $this->view->addErrorMessage('MODULES_FAILED_'.strtoupper($this->action));
            }            
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            
            if (!parent::process()) return false;

            $this->assignModules($this->modulelist, false);
            $this->view->setExcludeMessages(true);
            $this->view->initAssigns();
            $this->view->render();
            
        }

    }
?>
