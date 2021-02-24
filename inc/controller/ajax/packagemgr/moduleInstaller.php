<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\packagemgr;

/**
 * AJAX module installer controller
 * 
 * @package fpcm\controller\ajax\packagemgr\sysupdater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class moduleInstaller extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * Module key
     * @var int
     */
    protected $key;

    /**
     * Step to execute
     * @var int
     */
    protected $step;

    /**
     * Action
     * @var int
     */
    protected $mode;

    /**
     * allow_url_fopen = 1
     * @var bool
     */
    protected $canConnect;

    /**
     * Update-Package-Object
     * @var \fpcm\model\packages\module
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
        return $this->permissions->modules->install;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->key = $this->request->fromPOST('key');
        $this->step = 'exec'.$this->request->fromPOST('step', [\fpcm\model\http\request::FILTER_FIRSTUPPER]);
        $this->mode = $this->request->fromPOST('mode');
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
            (new \fpcm\model\http\response)->setCode(400)->addHeaders('Bad Request')->fetch();
        }

        if (!method_exists($this, $this->step)) {
            trigger_error('Module processing step '.$this->step.' not defined!');
            
            $this->response->setReturnData([
                'code' => $this->res,
                'pkgdata' => $this->pkgdata
            ])->fetch();

        }

        $this->init();

        call_user_func([$this, $this->step]);
        
        $this->response->setReturnData([
            'code' => $this->res,
            'pkgdata' => $this->pkgdata
        ]);

        usleep(500000);
        $this->response->fetch();
    }

    private function execMaintenanceOn()
    {
        $this->res = $this->config->setMaintenanceMode(true) && \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
    }

    private function execMaintenanceOff()
    {
        $this->res = $this->config->setMaintenanceMode(false) && \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
    }

    private function execCheckFiles()
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

    private function execDownload()
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
    
    private function execCheckPkg()
    {
        $this->res = $this->pkg->checkPackage();
        if ($this->res === true) {
            fpcmLogSystem('Package integity check for '.basename($this->pkg->getLocalPath()).' was successful.');
            return true;
        }

        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
        $this->res = false;
    }

    private function execExtract()
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

    private function execUpdateFs()
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

    private function execUpdateDb()
    {
        $module = new \fpcm\module\module($this->key);
        if (!method_exists($module, $this->mode)) {
            fpcmLogSystem('Undefined function '.$this->mode.' for module database update '.$this->key.'!');
            return false;
        }
        
        if (!in_array($this->mode, ['install', 'update'])) {
            fpcmLogSystem('Function '.$this->mode.' for module database update '.$this->key.' is not whitelisted!!');
            return false;
        }

        $this->res = call_user_func([$module, $this->mode]);
        if ($this->res === true) {
            fpcmLogSystem('Database update was successful for module '.$this->key.'!');
            return true;
        }

        fpcmLogSystem('Database update failed for module '.$this->key.'. See error and database log for further information.');
    }
    
    private function execUpdateLog()
    {
        $this->res = $this->pkg->updateLog();
        return;
    }

    private function execCleanup()
    {
        $this->res = $this->pkg->cleanup();
        \fpcm\classes\loader::getObject('\fpcm\classes\cache')->cleanup();
    }

    private function init()
    {
        $this->pkg = new \fpcm\model\packages\module($this->key);
    }

    /**
     * 
     * @param string $var
     */
    private function addErrorMessage($var, $params = [])
    {
        $this->pkgdata['errorMsg'] = $this->language->translate($var, $params);
    }
}

?>