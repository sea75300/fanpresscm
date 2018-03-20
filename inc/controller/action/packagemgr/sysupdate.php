<?php

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class sysupdate extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\packagemgr\initialize;

    /**
     * Update-Prüfung aktiv
     * @var bool
     */
    protected $updateCheckEnabled = false;

    /**
     * Auszuführender Schritt
     * @var mixed
     */
    protected $forceStep = false;

    /**
     * Auszuführender Schritt
     * @var bool
     */
    protected $legacy = false;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'update'];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return 'packagemgr/sysupdater';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if ($this->getRequestVar('step')) {
            $this->forceStep = $this->getRequestVar('step');
        }

        if ($this->getRequestVar('file')) {
            $tmpFile = new \fpcm\model\files\tempfile('forceUpdateFile');
            $tmpFile->setContent($this->getRequestVar('file'));
            $tmpFile->save();
        }

        if (!$this->forceStep) {
//            \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
        }

        return parent::request();
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $updater = new \fpcm\model\updater\system();
        $updater->checkUpdates();
        $remoteFilePath = $updater->getRemoteData('filepath');

        $params = $this->initPkgManagerData();
        $params['fpcmUpdaterStartStep'] = ($this->forceStep ? $this->forceStep : (\fpcm\classes\baseconfig::canConnect() ? \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD : \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB));

        $params['fpcmUpdaterForce'] = $this->forceStep ? 1 : 0;
        $params['fpcmUpdaterMessages'][\fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_START'] = $this->lang->translate('PACKAGES_RUN_DOWNLOAD', ['{{pkglink}}' => is_array($remoteFilePath) ? '' : $remoteFilePath]);
        $params['fpcmUpdaterMessages']['EXIT_1'] = $this->lang->translate('UPDATES_SUCCESS');
        $params['fpcmUpdaterStepMap'] = [
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD => 1,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT => 2,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES => 3,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY => 4,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB => 4,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP => 6,
            \fpcm\model\packages\package::FPCMPACKAGE_STEP_FINISH => 7
        ];
        $params['fpcmUpdaterMaxStep'] = count($params['fpcmUpdaterStepMap']);

        $this->view->addButton( (new \fpcm\view\helper\linkButton('backbtn'))->setText('PACKAGES_BACKTODASHBOARD')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/dashboard'))->setIcon('chevron-circle-left') );
        $this->view->addJsVars($params);
        $this->view->addJsFiles(['updater.js']);
        $this->view->render();
    }

}
