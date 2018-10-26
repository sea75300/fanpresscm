<?php

namespace fpcm\controller\action\modules;

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class moduleConfig extends \fpcm\controller\abstracts\controller {

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
        $this->key = $this->getRequestVar('key');
        if (!$this->key) {
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
     * @return array
     */
    protected function getPermissions()
    {
        return ['modules' => 'configure'];
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

        $options = $this->getRequestVar('config');
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
        $this->view->addButton(new \fpcm\view\helper\saveButton('save'));
        $this->view->render();
    }

}

?>