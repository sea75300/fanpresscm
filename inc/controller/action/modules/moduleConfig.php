<?php

namespace fpcm\controller\action\modules;

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class moduleConfig extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var \fpcm\module\module
     */
    protected $module;

    /**
     *
     * @var string
     */
    protected $key = '';

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->key = $this->request->fromGET('key');
        if (!$this->key || !\fpcm\module\module::validateKey($this->key)) {
            $this->view = new \fpcm\view\error('MODULES_KEY_INVALID');
            return false;
        }
        
        $this->moduleController = $this->key;
        $this->module = new \fpcm\module\module($this->key);
        
        if (!$this->module->isInstalled() || !$this->module->isActive()) {
            $view = new \fpcm\view\error("The module '{$this->key}' is not installed or enabled!");
            $view->render();
        }
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->modules->configure;
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'configure';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return $this->module->getConfig()->help;
    }

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        parent::hasAccess();
        if (!$this->execDestruct) {
            return false;
        }

        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->key) {
            return false;
        }

        if (!$this->buttonClicked('save')) {
            return true;
        }

        $options = $this->request->fromPOST('config');
        if (!is_array($options)) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS_MODULES');
            return true;
        }

        if (!$this->module->prepareSaveOptions($options) || !$this->module->setOptions($options)) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
            return true;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_OPTIONS');
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {        
        $this->view->setFormAction('modules/configure', [
            'key' => $this->key
        ]);

        $this->view->setViewVars(array_merge(
            $this->module->getConfigViewVars(),
            [
                'options' => $this->module->getOptions()
            ]
        ));

        $path = $this->key . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'moduleConfig.js';
        if (file_exists( \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $path) )) {
            $this->view->addJsFiles([ 
                \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $path)    
            ]);            
        }

        $this->view->addButton(new \fpcm\view\helper\saveButton('save'));
        $this->view->render();
    }

}

?>