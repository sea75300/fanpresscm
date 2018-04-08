<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\packagemgr;

/**
 * Trait für Initialisierung von Paket Manager
 * 
 * @package fpcm\controller\traits\packagemgr\initialize
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait initialize {

    /**
     * Gibt gemeinsame initiale Daten für Paketmanager zurück,
     * beeinhaltet Benachrichtigungen, Statusinfos, etc.
     * @return array
     */
    public function initPkgManagerData()
    {
        return array(
            'fpcmUpdaterProcessTime' => $this->lang->translate('PACKAGES_PROCESS_TIME'),
            'fpcmUpdaterMaxStep' => $this->forceStep ? $this->forceStep : \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP,
            'fpcmUpdaterProgressbar' => (int) \fpcm\classes\baseconfig::canConnect(),
            'fpcmUpdaterMessages' => array(
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_START' => $this->lang->translate('PACKAGES_RUN_DOWNLOAD'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES . '_START' => $this->lang->translate('PACKAGES_RUN_FILECHECK'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT . '_START' => $this->lang->translate('PACKAGES_RUN_EXTRACT'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY . '_START' => $this->lang->translate('PACKAGES_RUN_COPY'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB . '_START' => $this->lang->translate('PACKAGES_RUN_ADDITIONAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP . '_START' => $this->lang->translate('PACKAGES_FILE_LIST'),
                'EXIT_1' => $this->lang->translate('UPDATES_SUCCESS'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_1' => $this->lang->translate('PACKAGES_SUCCESS_DOWNLOAD'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_0' => $this->lang->translate('PACKAGES_FAILED_GENERAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES . '_1' => $this->lang->translate('PACKAGES_SUCCESS_FILECHECK'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES . '_0' => $this->lang->translate('PACKAGES_FAILED_GENERAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT . '_1' => $this->lang->translate('PACKAGES_SUCCESS_EXTRACT'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT . '_0' => $this->lang->translate('PACKAGES_FAILED_GENERAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY . '_1' => $this->lang->translate('PACKAGES_SUCCESS_COPY'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY . '_0' => $this->lang->translate('PACKAGES_FAILED_GENERAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB . '_1' => $this->lang->translate('PACKAGES_SUCCESS_ADDITIONAL'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB . '_0' => $this->lang->translate('UPDATES_FAILED'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP . '_1' => $this->lang->translate('PACKAGES_SUCCESS_LOGDONE'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_FINISH . '_1' => $this->lang->translate('PACKAGES_UPDATE_NEW_VERSION'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_' . \fpcm\model\packages\package::FPCMPACKAGE_REMOTEFILE_ERROR => $this->lang->translate('PACKAGES_FAILED_REMOTEFILE'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_' . \fpcm\model\packages\package::FPCMPACKAGE_LOCALFILE_ERROR => $this->lang->translate('PACKAGES_FAILED_LOCALFILE'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_' . \fpcm\model\packages\package::FPCMPACKAGE_LOCALWRITE_ERROR => $this->lang->translate('PACKAGES_FAILED_LOCALWRITE'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_' . \fpcm\model\packages\package::FPCMPACKAGE_LOCALEXISTS_ERROR => $this->lang->translate('PACKAGES_FAILED_LOCALEXISTS'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD . '_' . \fpcm\model\packages\package::FPCMPACKAGE_HASHCHECK_ERROR => $this->lang->translate('PACKAGES_FAILED_HASHCHECK'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT . '_' . \fpcm\model\packages\package::FPCMPACKAGE_ZIPOPEN_ERROR => $this->lang->translate('PACKAGES_FAILED_ZIPOPEN'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT . '_' . \fpcm\model\packages\package::FPCMPACKAGE_ZIPEXTRACT_ERROR => $this->lang->translate('PACKAGES_FAILED_ZIPEXTRACT'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY . '_' . \fpcm\model\packages\package::FPCMPACKAGE_FILESCOPY_ERROR => $this->lang->translate('PACKAGES_FAILED_FILESCOPY'),
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES . '_' . \fpcm\model\packages\package::FPCMPACKAGE_FILESCHECK_ERROR => $this->lang->translate('UPDATE_WRITEERROR')
            )
        );
    }

}
