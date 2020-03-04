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
class execAction extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

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

    public function isAccessible(): bool
    {
        return $this->permissions->modules->configure || $this->permissions->modules->install || $this->permissions->modules->uninstall;
    }

    /**
     * Request-Handler
     * @return bool
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
        if (!\fpcm\module\module::validateKey($this->key))
        {
            trigger_error('Module processing step '.$this->step.' not defined!');
            (new \fpcm\model\http\response)->setCode(400)->addHeaders('Bad Request')->fetch();
        }

        $function = 'process'.ucfirst($this->action);
        
        if (!method_exists($this, $function)) {
            trigger_error('Invalid module module '.$this->action.' action detected!');
            $this->returnData['code'] = 0;
            $this->getSimpleResponse();
        }
        
        call_user_func([$this, $function]);
        $this->cache->cleanup();
        $this->getSimpleResponse();
    }

    /**
     * 
     * @return bool
     */
    private function processInstall()
    {
        if (!$this->permissions->modules->install) {
            trigger_error('Unable to install module, permission denied!');
            $this->returnData['code'] = \fpcm\module\module::STATUS_NOT_INSTALLED;
            return false;
        }

        $module = new \fpcm\module\module($this->key);

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->install($this->fromDir)
                                    ? \fpcm\module\module::STATUS_INSTALLED
                                    : \fpcm\module\module::STATUS_NOT_INSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    private function processUninstall()
    {
        if (!$this->permissions->modules->uninstall) {
            trigger_error('Unable to uninstall module, permission denied!');
            $this->returnData['code'] = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->uninstall()
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    private function processDelete()
    {
        if (!$this->permissions->modules->uninstall) {
            trigger_error('Unable to delete module, permission denied!');
            $this->returnData['code'] = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->uninstall(true)
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    private function processEnable()
    {
        if (!$this->permissions->modules->configure) {
            trigger_error('Unable to enable module, permission denied!');
            $this->returnData['code'] = \fpcm\module\module::STATUS_NOT_ENABLED;
            return false;
        }

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->enable()
                                    ? \fpcm\module\module::STATUS_ENABLED
                                    : \fpcm\module\module::STATUS_NOT_ENABLED;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function processDisable()
    {
        if (!$this->permissions->modules->configure) {
            trigger_error('Unable to disable module, permission denied!');
            $this->returnData['code'] = \fpcm\module\module::STATUS_NOT_DISABLED;
            return false;
        }

        $this->returnData['code']   = (new \fpcm\module\module($this->key))->disable()
                                    ? \fpcm\module\module::STATUS_DISABLED
                                    : \fpcm\module\module::STATUS_NOT_DISABLED;
        return true;
    }

}

?>
