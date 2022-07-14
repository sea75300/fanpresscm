<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\packagemgr;

/**
 * AJAX system updates controller
 * 
 * @package fpcm\controller\ajax\packagemgr\sysupdater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class processUpdate extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * Auszuführender Schritt
     * @var int
     */
    protected $step;

    /**
     * allow_url_fopen = 1
     * @var bool
     */
    protected $canConnect;

    /**
     * Update-Package-Object
     * @var \fpcm\model\packages\update
     */
    protected $pkg;
    
    /**
     *
     * @var bool
     */
    protected $res = false;

    /**
     *
     * @var array
     */
    protected $pkgdata = [];

    /**
     * Returned message
     * @var \fpcm\view\message
     */
    protected $message;

    /**
     * Version data file
     * @var \fpcm\model\files\tempfile
     */
    protected $versionDataFile = false;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options && $this->permissions->system->update;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->step = 'exec'.ucfirst($this->request->fromPOST('step'));
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {

        $this->init();
        if ($this->processByParam('exec', 'step') === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setReturnData(new \fpcm\model\http\responseDataPkgMgr(false))->fetch();
        }
        
        $this->response->setReturnData(new \fpcm\model\http\responseDataPkgMgr($this->res, $this->pkgdata, $this->message));

        usleep(500000);
        $this->response->fetch();
    }

    protected function execMaintenanceOn()
    {
        $this->res = $this->config->setMaintenanceMode(true) && \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
    }

    protected function execMaintenanceOff()
    {
        $this->res = $this->config->setMaintenanceMode(false) && \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
    }

    protected function execCheckFiles()
    {
        $success = $this->pkg->checkFiles();
        if ($success === \fpcm\model\packages\package::FILESCHECK_ERROR) {
            $this->addErrorMessage('UPDATE_WRITEERROR');
        }

        $this->res = $success === true ? true : false;

        if (!$this->res) {
            return false;
        }

        fpcmLogSystem('Local file system check was successful');
    }

    protected function execDownload()
    {
        if (!$this->pkg->isTrustedPath()) {
            $this->addErrorMessage('PACKAGES_FAILED_DOWNLOAD_UNTRUSTED', [
                '{{var}}' => $this->pkg->getRemotePath()
            ]);
            $this->res = false;
            return false;
        }
        
        $this->res = $this->pkg->download();
        if ($this->res === true) {
            fpcmLogSystem('Download of package '.$this->pkg->getRemotePath().' was successful.');
            return true;
        }

        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }
    
    protected function execCheckPkg()
    {
        $this->res = $this->pkg->checkPackage();
        if ($this->res === true) {
            fpcmLogSystem('Package integity check for '.basename($this->pkg->getLocalPath()).' was successful.');
            return true;
        }

        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }

    protected function execExtract()
    {
        $this->res = $this->pkg->extract();
        if ($this->res === true) {
            fpcmLogSystem('Package extraction for '.basename($this->pkg->getLocalPath()).' was successful.');
            return true;
        }

        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }

    protected function execBackupFs()
    {
        $this->res = $this->pkg->backup();
        if ($this->res === true) {
            fpcmLogSystem('File system back from '.basename($this->pkg->getLocalPath()).' was successful.');
            return true;
        }

        $this->addErrorMessage('PACKAGES_FAILED_ERROR_BACKUPS');
        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }

    protected function execUpdateFs()
    {
        $this->res = $this->pkg->copy();
        if ($this->res === true) {
            fpcmLogSystem('File system update from '.basename($this->pkg->getLocalPath()).' was successful.');
            return true;
        }

        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }

    protected function execUpdateDb()
    {
        $finalizer = new \fpcm\model\updater\finalizer();
        $this->res = $finalizer->runUpdate();

        if ($this->res === true) {
            fpcmLogSystem('Database update was successful!');
            return true;
        }

        fpcmLogSystem('Database update failed. See error and database log for further information.');
    }
    
    protected function execUpdateLog()
    {
        fpcmLogSystem('Update package manager logfile!');
        $this->res = $this->pkg->updateLog();
        (new \fpcm\model\crons\updateCheck())->run();
        return;
    }

    protected function execCleanup()
    {
        fpcmLogSystem('Cleanup of outdated and temporary files!');
        $this->pkg->cleanupFiles();
        $this->pkg->cleanup();
        \fpcm\classes\loader::getObject('\fpcm\classes\cache')->cleanup();
        $this->res = true;
    }

    protected function execGetVersion()
    {
        $this->pkgdata['version'] = $this->config->system_version;
        $this->res = true;
    }

    private function init()
    {
        $this->pkg = new \fpcm\model\packages\update(basename((new \fpcm\model\updater\system())->url));
    }

    /**
     * 
     * @param string $var
     */
    private function addErrorMessage($var, $params = [])
    {
        $this->message = new \fpcm\view\message($this->language->translate($var, $params), \fpcm\view\message::TYPE_ERROR);
    }
}

?>