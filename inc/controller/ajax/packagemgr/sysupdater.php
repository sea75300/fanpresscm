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
class sysupdater extends \fpcm\controller\abstracts\ajaxController {

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
        return ['system' => 'update'];
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->step = 'exec'.ucfirst($this->getRequestVar('step'));
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!method_exists($this, $this->step)) {
            trigger_error('Update step '.$this->step.' not defined!');
            $this->returnData = [
                'code' => $this->res,
                'pkgdata' => $this->pkgdata
            ];

            $this->getSimpleResponse();
        }

        fpcmLogSystem($this->step);
        
        call_user_func([$this, $this->step]);
        $this->returnData = [
            'code' => $this->res,
            'pkgdata' => $this->pkgdata
        ];

        $this->getSimpleResponse();


//            
//            if ($this->canConnect) {
//                
//                $this->versionDataFile = new \fpcm\model\files\tempfile('newversion');
//                if ($this->versionDataFile->exists() && $this->versionDataFile->getContent()) {
//                    $remoteData = json_decode($this->versionDataFile->getContent(), true);
//                }
//                else {
//                    $updater = new \fpcm\model\updater\system();
//                    $updater->checkUpdates();
//
//                    $remoteData = $updater->getRemoteData();
//                    $this->versionDataFile->setContent(json_encode($remoteData));
//                    $this->versionDataFile->save();
//                }
//
//                $fileInfo = pathinfo($remoteData['filepath'], PATHINFO_FILENAME);
//
//                $tmpFile = new \fpcm\model\files\tempfile('forceUpdateFile');
//                if ($tmpFile->exists()) {
//                    $fileInfo = $tmpFile->getContent();
//                }
//
//                $signature = isset($remoteData['signature']) ? $remoteData['signature'] : '';
//                $this->pkg = new \fpcm\model\packages\update('update', $fileInfo, '', $signature);
//            }
    }

    private function execMaintenanceOn()
    {
        $this->res = $this->config->setMaintenanceMode(true);
    }

    private function execMaintenanceOff()
    {
        $this->res = $this->config->setMaintenanceMode(false) && \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
    }

    private function execCheckFiles()
    {
        $this->res = true;
        
//        $this->res = $this->pkg->checkFiles();
//
//        if ($this->res === \fpcm\model\packages\package::FPCMPACKAGE_FILESCHECK_ERROR) {
//            $this->versionDataFile->delete();
//        }
//
//        if ($this->res === true) {
//            $this->syslog('All local files are writable ' . $this->pkg->getRemoteFile());
//            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY;
//            return true;
//        }
//
//        $this->syslog('A few files in local file system where not writable ' . $this->pkg->getRemoteFile());
//        $this->syslog(implode(PHP_EOL, $this->pkg->getCopyErrorPaths()));
    }

    private function execDownload()
    {
        $this->init();
        $this->res = true;

        
//        $this->res = $this->pkg->download();
//
//        if ($this->res === \fpcm\model\packages\package::FPCMPACKAGE_REMOTEFILE_ERROR) {
//            $this->versionDataFile->delete();
//        }
//
//        if ($this->res === true) {
//            $this->syslog('Downloaded update package successfully from ' . $this->pkg->getRemoteFile());
//            return true;
//        }
//
//        $this->syslog('Error while downloading update package from ' . $this->pkg->getRemoteFile());
    }
    
    private function execCheckPkg()
    {
        $this->res = true;
    }

    private function execExtract()
    {
        $this->res = true;
        return;

        $this->res = $this->pkg->extract();
        $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getLocalFile());

        if ($this->res === true) {
            $this->syslog('Extracted update package successfully from ' . $from);
            return true;
        }

        $this->syslog('Error while extracting update package from ' . $from);
    }

    private function execUpdateFs()
    {
        $this->res = true;
        return;

        $this->res = $this->pkg->copy();

        $dest = \fpcm\model\files\ops::removeBaseDir(\fpcm\classes\dirs::getFullDirPath(''));
        $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getExtractPath());

        if ($this->res === true) {
            $this->syslog('Moved update package content successfully from ' . $from . ' to ' . $dest);
            return true;
        }

        $this->syslog('Error while moving update package content from ' . $from . ' to ' . $dest);
        $this->syslog(implode(PHP_EOL, $this->pkg->getCopyErrorPaths()));
    }

    private function execUpdateDb()
    {
        $finalizer = new \fpcm\model\updater\finalizer();
        $this->res = $finalizer->runUpdate();

        if ($this->res === true) {
            fpcmLogSystem('Run final update steps successfully!');
            return true;
        }

        fpcmLogSystem('Databse update failed. See error and database log for further information.');
    }
    
    private function execUpdateLog()
    {
        $this->res = true;
        return;
    }

    private function execCleanup()
    {
        $this->res = true;
        return;

        if ($this->canConnect) {

            $list = [];
            if (method_exists($this->pkg, 'getProtocol')) {
                $list = $this->pkg->getProtocol();
            }

            if (!count($list)) {
                $this->pkg->loadPackageFileListFromTemp();
                $list = $this->pkg->getFiles();
            }


            $this->pkglog($this->pkg->getKey() . ' ' . $this->pkg->getVersion(), $list);
        }

        
        $this->cache->cleanup();

        $this->res = true;
    }

    private function execGetVersion()
    {
        $this->pkgdata['version'] = $this->config->system_version;
        $this->res = true;

//        if ($this->versionDataFile->exists()) {
//            $this->versionDataFile->delete();
//        }
    }

    private function init()
    {
        $updater = new \fpcm\model\updater\system();
        $this->pkgdata['pkgname'] = basename($updater->url);

        $this->pkg = new \fpcm\model\packages\update($this->pkgdata['pkgname']);
    }
    
}

?>