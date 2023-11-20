<?php

namespace fpcm\controller\action\modules;

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class moduleConfig extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\requestFunctions
{

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
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'modules/configure';
    }

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
        if (!$this->module->isInstalled() || !$this->module->isActive() || !$this->module->hasConfigure()) {
            $this->view = new \fpcm\view\error("The module '{$key}' is not installed or enabled!");
            $this->view->render();
            return false;
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
            $this->prepareFields()
        ));
        
        $this->view->assign('prefix', $this->module->getFullPrefix());
        
        
        $path = $this->module->getKey() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'moduleConfig.js';
        if (file_exists( \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $path) )) {
            $this->view->addFromModule(['moduleConfig.js'], [], $this->module->getKey());
        }

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('backbtn'))->setText('MODULES_LIST_BACKTOLIST')->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))->setIcon('chevron-circle-left'),
            (new \fpcm\view\helper\saveButton('save'))->setPrimary()
        ]);
        $this->view->addTabs('-module'.$this->module->getKey(), [
            (new \fpcm\view\helper\tabItem('configure-modul'.$this->module->getKey()))->setFile($this->getViewPath())->setText('SYSTEM_HL_OPTIONS_GENERAL')
        ]);
        $this->view->render();
    }
    
    private function prepareFields() : array
    {
        $data = [
            'options' => $this->module->getOptions(),
            'fields' => []
        ];

        if ($this->useLegacy) {
            return $data;
        }

        $optionDef = $this->module->getConfigureFields();
        $data['descriptions'] = $optionDef['descriptions'] ?? null;
        unset($optionDef['descriptions']);

        $viewVars = $this->module->getConfigViewVars();

        foreach ($optionDef as $option => $field) {
            
            $fullOption = $this->module->getFullPrefix($option);
            if (!isset($data['options'][$fullOption])) {
                continue;
            }
            
            $class = '\\fpcm\\view\\helper\\' . $field['type'];

            if (!class_exists($class)) {
                trigger_error('Undefined field type ' . $field['type']);
                return [];
            }

            if (isset($field['conf']['text'])) {
                $field['conf']['text'] = \fpcm\module\module::getLanguageVarPrefixed($this->module->getKey()).$field['conf']['text'];
            }
            
            $obj = ( new $class('config[' . $fullOption . ']') )->initFromYml( $field['conf'] ?? [] , $viewVars );
            $valueFunc = method_exists($obj, 'setSelected') ? 'setSelected' : (method_exists($obj, 'setValue') ? 'setValue' :  false);
            if ($valueFunc !== false) {
                $obj->{$valueFunc}($data['options'][$fullOption]);
            }
            
            if (method_exists($obj, 'prependLabel')) {
                $obj->prependLabel();
            }
            
            if (method_exists($obj, 'setLabelTypeFloat')) {
                $obj->setLabelTypeFloat();
            }

            $data['fields'][$option] = (string) $obj;
            unset($obj,$valueFunc);
        }

        return $data;
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

        if (!$this->module->setOptions($options)) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
            return true;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_OPTIONS');
        return true;
    }

}

?>