<?php

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\abstracts;

abstract class base extends \fpcm\controller\abstracts\controller
{

    /**
     *
     * @var bool
     */
    protected $updateDb;

    /**
     *
     * @var array
     */
    protected $steps = [
        'checkFs'   => true,
        'checkPkg'  => true,
        'backupFs'  => true,
        'updateDb'  => true,
        'download'  => true,
        'extract'   => true,
        'updateFs'  => true,
        'updateLog' => true,
        'cleanup'   => true,
    ];

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'packagemgr/installer';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
        $this->updateDb = ($this->request->fromGET('update-db') !== null);
        return true;
    }

    /**
     *
     * @return bool
     */
    public function process() {

        $this->view->addJsLangVars(['PACKAGEMANAGER_SUCCESS', 'PACKAGEMANAGER_FAILED', 'PACKAGEMANAGER_NEWVERSION']);

    }


    /**
     *
     * @param bool $data
     * @return bool
     */
    protected function invert($data)
    {
        return !$data;
    }

    /**
     *
     * @param int $count
     * @return array
     */
    protected function getActiveSteps(int &$count) : array
    {
        $return = array_filter($this->initStepsDef(), function($key) {

            if (!isset($this->steps[$key])) {
                return true;
            }

            return $this->steps[$key];
        }, ARRAY_FILTER_USE_KEY);

        $count = count($return);

        return array_values($return);
    }

    /**
     *
     * @return array
     */
    protected function initStepsDef() : array
    {
        return [
            'maintenanceOn'   => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_MAINTENANCE_EN'),
                'maintenanceOn',
                'startTimer',
                new \fpcm\view\helper\icon('person-digging')
            ),
            'checkFs'   => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_CHECKLOCAL'),
                'checkFiles',
                '',
                new \fpcm\view\helper\icon('medkit')
            ),
            'backupFs'  => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_BACKUPFS'),
                'backupFs',
                '',
                new \fpcm\view\helper\icon('life-ring')
            ),
            'download'  => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_DOWNLOAD', [
                    '{{var}}' => $this->updater->url,
                    '{{var2}}' => $this->updater->size ? '('.\fpcm\classes\tools::calcSize($this->updater->size).')' : '',
                ]),
                'download',
                '',
                new \fpcm\view\helper\icon('cloud-arrow-down')
            ),
            'checkPkg'  => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_CHECKPKG', [
                    '{{var}}' => $this->steps['pkgname'] ?? ''
                ]),
                'checkPkg',
                '',
                new \fpcm\view\helper\icon('file-signature')
            ),
            'extract'   => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_EXTRACT', [
                    '{{var}}' => $this->steps['pkgname'] ?? ''
                ]),
                'extract',
                '',
                new \fpcm\view\helper\icon('file-archive', 'far')
            ),
            'updateFs'  => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_UPDATEFS'),
                'updateFs',
                '',
                new \fpcm\view\helper\icon('copy')
            ),
            'updateDb'  => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_UPDATEDB'),
                'updateDb',
                '',
                new \fpcm\view\helper\icon('database')
            ),
            'updateLog' => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_UPDATELOG'),
                'updateLog',
                '',
                new \fpcm\view\helper\icon('file-alt', 'far')
            ),
            'cleanup'   => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_CLEANUP'),
                'cleanup',
                '',
                new \fpcm\view\helper\icon('eraser')
            ),
            'maintenanceOff'   => new \fpcm\model\packages\step(
                $this->language->translate('PACKAGEMANAGER_MAINTENANCE_DIS'),
                'maintenanceOff',
                '',
                new \fpcm\view\helper\icon('bolt')
            ),
            'finish'   => new \fpcm\model\packages\step(
                $this->language->translate('GLOBAL_FINISHED'),
                'getVersion',
                '',
                new \fpcm\view\helper\icon('circle-check'),
                'version',
                'stopTimer'
            )
        ];
    }
}
