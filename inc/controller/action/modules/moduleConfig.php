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
     * @return boolean
     */
    protected function initActionObjects()
    {
        $this->key = $this->getRequestVar('key');
        if (!$this->key) {
            return false;
        }
        
        $this->moduleController = $this->key;
        $this->module = new \fpcm\module\module($this->key);
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
    protected function getViewPath()
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
     * @return boolean
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
     * @return boolean
     */
    public function request()
    {
        if (!$this->key) {
            return false;
        }

        
        return true;
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {        
        $this->view->setFormAction('modules/configure', [
            'key' => $this->key
        ]);

        $this->view->assign('options', $this->module->getOptions());
        $this->view->addButton(new \fpcm\view\helper\saveButton('save'));
        $this->view->render();
    }

}

?>