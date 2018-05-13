<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\modules;

/**
 * AJAX-Controller der die Aktionen im Module-Manager ausführt
 * 
 * @package fpcm\controller\ajax\modules\moduleactions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class execAction extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\modules\moduleactions;

    /**
     * Module key
     * @var array
     */
    protected $key;

    /**
     * Action to execute
     * @var string
     */
    protected $action;

    /**
     * From directory
     * @var string
     */
    protected $fromDir;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['modules' => ['configure', 'install', 'uninstall']];
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->key = $this->getRequestVar('key');
        $this->action = $this->getRequestVar('action');
        $this->fromDir = $this->getRequestVar('fromDir', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);
        
        return trim($this->key) ? true : false;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $function = 'process'.ucfirst($this->action);
        
        if (!method_exists($this, $function)) {
            trigger_error('Invalid module module '.$this->action.' action detected!');
            $this->returnData['code'] = 0;
            $this->getSimpleResponse();
        }
        
        call_user_func([$this, $function]);
        $this->getSimpleResponse();
    }

    /**
     * 
     * @return boolean
     */
    private function processInstall()
    {
        $module = new \fpcm\module\module($this->key);

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->install($this->fromDir)
                                    ? \fpcm\module\module::STATUS_INSTALLED
                                    : \fpcm\module\module::STATUS_NOT_INSTALLED;

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processUninstall()
    {
        $this->returnData['code']   = (new \fpcm\module\module($this->key))->uninstall()
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processDelete()
    {
        $this->returnData['code']   = (new \fpcm\module\module($this->key))->uninstall(true)
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processEnable()
    {
        $this->returnData['code']   = (new \fpcm\module\module($this->key))->enable()
                                    ? \fpcm\module\module::STATUS_ENABLED
                                    : \fpcm\module\module::STATUS_NOT_ENABLED;
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processDisable()
    {
        $this->returnData['code']   = (new \fpcm\module\module($this->key))->disable()
                                    ? \fpcm\module\module::STATUS_DISABLED
                                    : \fpcm\module\module::STATUS_NOT_DISABLED;
        return true;
    }

}

?>
