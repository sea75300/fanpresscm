<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\modules;

/**
 * Execute actions in module manager
 *
 * @package fpcm\controller\ajax\modules\moduleactions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class execAction extends \fpcm\controller\abstracts\ajaxController
{
    
    const CMD_RELOAD = 'reload';

    /**
     * Module key
     * @var string
     */
    protected string $key = '';

    /**
     * From directory action flag
     * @var bool
     */
    protected bool $fromDir = false;

    /**
     *
     * @var bool
     */
    protected int $returnCode ;

    /**
     * Returned command
     * @var bool
     */
    protected string $command = '';

    /**
     * Permission check
     * @return bool
     */
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
        $this->key = $this->request->fromPOST('key');
        $this->fromDir = (bool) $this->request->fromPOST('fromDir', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        return true;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        if (!\fpcm\module\module::validateKey($this->key))
        {
            trigger_error('Invalid module key ' . $this->key . '!');
            $this->response->setCode(400)->addHeaders('Bad Request')->fetch();
        }


        if ($this->processByParam('process', 'action') === self::ERROR_PROCESS_BYPARAMS) {
            trigger_error('Invalid module action '.$this->request->fromPOST('action').'!');
            $this->response->setReturnData( new \fpcm\model\http\responseData(0) )->fetch();
        }

        $this->cache->cleanup();
        $this->response->setReturnData( new \fpcm\model\http\responseData($this->returnCode, $this->command) )->fetch();
    }

    /**
     *
     * @return bool
     */
    protected function processInstall()
    {
        if (!$this->permissions->modules->install) {
            trigger_error('Unable to install module, permission denied!');
            $this->returnCode = \fpcm\module\module::STATUS_NOT_INSTALLED;
            return false;
        }

        $this->returnCode   = (new \fpcm\module\module($this->key))->install($this->fromDir)
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
            $this->returnCode = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnCode   = (new \fpcm\module\module($this->key))->uninstall()
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
            $this->returnCode = \fpcm\module\module::STATUS_NOT_UNINSTALLED;
            return false;
        }

        $this->returnCode   = (new \fpcm\module\module($this->key))->uninstall(true)
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
            $this->returnCode = \fpcm\module\module::STATUS_NOT_ENABLED;
            return false;
        }

        $module = (new \fpcm\module\module($this->key));

        $this->returnCode   = $module->enable()
                            ? \fpcm\module\module::STATUS_ENABLED
                            : \fpcm\module\module::STATUS_NOT_ENABLED;

        $has_nav_event = $module->hasEvents('navigation\add');
        if (!$has_nav_event || $this->returnCode === \fpcm\module\module::STATUS_NOT_ENABLED) {
            return true;
        }

        $this->command = self::CMD_RELOAD;
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
            $this->returnCode = \fpcm\module\module::STATUS_NOT_DISABLED;
            return false;
        }

        $module = (new \fpcm\module\module($this->key));

        $this->returnCode   = $module->disable()
                            ? \fpcm\module\module::STATUS_DISABLED
                            : \fpcm\module\module::STATUS_NOT_DISABLED;

        $has_nav_event = $module->hasEvents('navigation\add');
        if (!$has_nav_event || $this->returnCode === \fpcm\module\module::STATUS_NOT_DISABLED) {
            return true;
        }

        $this->command = self::CMD_RELOAD;
        return true;
    }

}
