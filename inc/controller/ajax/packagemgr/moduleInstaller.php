<?php

/**
 * AJAX system updates controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\packagemgr;

/**
 * AJAX-Controller Paketmanager - System-Updater
 * 
 * @package fpcm\controller\ajax\packagemgr\sysupdater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class moduleInstaller extends \fpcm\controller\abstracts\ajaxController {

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
     * @return array
     */
    protected function getPermissions()
    {
        return ['modules' => 'install'];
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->key = $this->getRequestVar('key');
        $this->step = 'exec'.$this->getRequestVar('step', [\fpcm\classes\http::FILTER_FIRSTUPPER]);
        $this->mode = $this->getRequestVar('mode', [\fpcm\classes\http::FILTER_FIRSTUPPER]);
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!method_exists($this, $this->step)) {
            trigger_error('Module processing step '.$this->step.' not defined!');
            $this->returnData = [
                'code' => $this->res,
                'pkgdata' => $this->pkgdata
            ];

            $this->getSimpleResponse();
        }

        $this->init();

        call_user_func([$this, $this->step]);
        $this->returnData = [
            'code' => $this->res,
            'pkgdata' => $this->pkgdata
        ];

        usleep(500000);
        $this->getSimpleResponse();
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
//        $success = $this->pkg->checkFiles();
//        if ($success === \fpcm\model\packages\package::FILESCHECK_ERROR) {
//            $this->addErrorMessage('UPDATE_WRITEERROR');
//        }
//
//        $this->res = $success === true ? true : false;
//
//        if (!$this->res) {
//            return false;
//        }
//
//        fpcmLogSystem('Local file system check was successful');
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
//        
//        $this->res = $this->pkg->download();
//        if ($this->res === true) {
            $this->res = true;
            fpcmLogSystem('Download of package '.$this->pkg->getRemotePath().' was successful.');
            return true;
//        }
//
//        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
//        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
//        $this->res = false;
    }
    
    private function execCheckPkg()
    {
//        $this->res = $this->pkg->checkPackage();
//        if ($this->res === true) {
//            fpcmLogSystem('Package integity check for '.basename($this->pkg->getLocalPath()).' was successful.');
//            return true;
//        }
//
//        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
//        $this->res = false;
    }

    private function execExtract()
    {
//        $this->res = $this->pkg->extract();
//        if ($this->res === true) {
//            fpcmLogSystem('Package extraction for '.basename($this->pkg->getLocalPath()).' was successful.');
//            return true;
//        }
//
//        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
//        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
//        $this->res = false;
    }

    private function execUpdateFs()
    {
//        $this->res = $this->pkg->copy();
//        if ($this->res === true) {
//            fpcmLogSystem('File system update from '.basename($this->pkg->getLocalPath()).' was successful.');
//            return true;
//        }
//
//        $this->addErrorMessage('PACKAGES_FAILED_ERROR'.$this->res);
//        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
//        $this->res = false;
    }

    private function execUpdateDb()
    {
//        $finalizer = new \fpcm\model\updater\finalizer();
//        $this->res = $finalizer->runUpdate();
//
//        if ($this->res === true) {
//            fpcmLogSystem('Database update was successful!');
//            return true;
//        }
//
//        fpcmLogSystem('Database update failed. See error and database log for further information.');
    }
    
    private function execUpdateLog()
    {
//        $this->res = $this->pkg->updateLog();
//        return;
    }

    private function execCleanup()
    {
//        $this->init();
//        $this->res = $this->pkg->cleanup();
//        \fpcm\classes\loader::getObject('\fpcm\classes\cache')->cleanup();
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
        $this->pkgdata['errorMsg'] = $this->lang->translate($var, $params);
    }
}

?>