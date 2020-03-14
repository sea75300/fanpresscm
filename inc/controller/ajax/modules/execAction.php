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
        $this->response = new \fpcm\model\http\response;
        $this->key = $this->request->fromPOST('key');
        $this->fromDir = $this->request->fromPOST('fromDir', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!\fpcm\module\module::validateKey($this->key))
        {
            trigger_error('Module processing step '.$this->step.' not defined!');
            $this->response->setCode(400)->addHeaders('Bad Request')->fetch();
        }
        
        
        if ($this->processByParam('process', 'action') === self::ERROR_PROCESS_BYPARAMS) {
            trigger_error('Invalid module module '.$this->request->fromPOST('action').' action detected!');
            $this->response->setReturnData( new \fpcm\model\http\responseData(0) )->fetch();
        }

        $this->cache->cleanup();
        $this->response->setReturnData( new \fpcm\model\http\responseData($this->returnData) )->fetch();
    }

    /**
     * 
     * @return bool
     */
    protected function processInstall()
    {
        if (!$this->permissions->modules->install) {
            trigger_error('Unable to install module, permission denied!');
            $this->returnData = \fpcm\module\module::STATUS_NOT_INSTALLED;
            return false;
        }

        $this->returnData   = (new \fpcm\module\module($this->key))->install($this->fromDir)
                                    ? \fpcm\module\module::STATUS_INSTALLED
                                    : \fpcm\module\module::STATUS_NOT_INSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processUninstall()
    {
        if (!$this->permissions->modules->uninstall) {
            trigger_error('Unable to uninstall module, permission denied!');
            $this->returnData = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnData   = (new \fpcm\module\module($this->key))->uninstall()
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processDelete()
    {
        if (!$this->permissions->modules->uninstall) {
            trigger_error('Unable to delete module, permission denied!');
            $this->returnData = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnData   = (new \fpcm\module\module($this->key))->uninstall(true)
                                    ? \fpcm\module\module::STATUS_UNINSTALLED
                                    : \fpcm\module\module::STATUS_NOT_UNINSTALLED;

        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processEnable()
    {
        if (!$this->permissions->modules->configure) {
            trigger_error('Unable to enable module, permission denied!');
            $this->returnData = \fpcm\module\module::STATUS_NOT_ENABLED;
            return false;
        }

        $this->returnData   = (new \fpcm\module\module($this->key))->enable()
                                    ? \fpcm\module\module::STATUS_ENABLED
                                    : \fpcm\module\module::STATUS_NOT_ENABLED;
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processDisable()
    {
        if (!$this->permissions->modules->configure) {
            trigger_error('Unable to disable module, permission denied!');
            $this->returnData = \fpcm\module\module::STATUS_NOT_DISABLED;
            return false;
        }

        $this->returnData   = (new \fpcm\module\module($this->key))->disable()
                                    ? \fpcm\module\module::STATUS_DISABLED
                                    : \fpcm\module\module::STATUS_NOT_DISABLED;
        return true;
    }

}

?>
