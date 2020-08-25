<?php

namespace fpcm\controller\action\modules;

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class moduleConfig extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible, \fpcm\controller\interfaces\requestFunctions {

    use \fpcm\module\tools;
    
    /**
     *
     * @var \fpcm\module\module
     */
    protected $module;
    
    /**
     *
     * @var int
     */
    protected $useLegacy = 0;

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $key = $this->request->fromGET('key');
        if (!$key || !\fpcm\module\module::validateKey($key)) {
            $this->view = new \fpcm\view\error('MODULES_KEY_INVALID');
            $this->view->render();
            return false;
        }

        $this->module = $this->getObject($key);
        if (!$this->module->isInstalled() || !$this->module->isActive() || !$this->module->hasConfigure($this->useLegacy)) {
            $this->view = new \fpcm\view\error("The module '{$key}' is not installed or enabled!");
            $this->view->render();
            return false;
        }

        $this->view = new \fpcm\view\view('configure', $this->useLegacy ? $key : false);
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
        return !$this->module->getKey() ? false : true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {        
        $this->view->setFormAction('modules/configure', [
            'key' => $this->module->getKey()
        ]);

        $this->view->setViewVars(array_merge(
            $this->module->getConfigViewVars(),
            [
                'options' => $this->module->getOptions(),
                'fields' => $this->useLegacy ? [] : $this->module->getConfigureFields(),
                'prefix' => $this->module->getFullPrefix()
            ]
        ));

        $path = $this->module->getKey() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'moduleConfig.js';
        if (file_exists( \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $path) )) {
            $this->view->addJsFiles([ 
                \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $path)    
            ]);            
        }

        $this->view->addButton(new \fpcm\view\helper\saveButton('save'));
        $this->view->render();
    }

    /**
     * Save changes
     * @return bool
     */
    protected function onSave() : bool
    {
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

}

?>