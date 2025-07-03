<?php

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\system;

class update extends \fpcm\controller\abstracts\controller
{

    /**
     *
     * @var bool
     */
    protected $updateDb;
    
    protected \fpcm\model\updater\system $updater;

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
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options && $this->permissions->system->update;
    }

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'packagemgr/sysupdater';
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
     * Controller-Processing
     */
    public function process()
    {
        $this->updater = new \fpcm\model\updater\system();

        if ($this->updateDb) {
            $this->steps = array_map([$this, 'invert'], $this->steps);
            $this->steps['updateDb'] = true;
        }
        else {
            $this->steps['pkgurl'] = $this->updater->url;
            $this->steps['pkgsize'] = $this->updater->size ? '('.\fpcm\classes\tools::calcSize($this->updater->size).')' : '';
            $this->steps['pkgname'] = basename($this->updater->url);
        }
        
        $jsData = [];

        $jsData['steps'] = array_filter($this->initStepsDef(), function($key) {
            
            if (!isset($this->steps[$key])) {
                return true;
            }

            return $this->steps[$key];
        }, ARRAY_FILTER_USE_KEY);
        

        $jsData['steps'] = array_values($jsData['steps']);        
        $this->steps['stepcount'] = count($jsData['steps']);
        
        $this->view->setViewVars($this->steps);
        $this->view->addJsVars([
            'pkgdata' => [
                'update' => $jsData,
            ],
            'stepcount' => $this->steps['stepcount']
        ]);
        $this->view->addJsLangVars(['PACKAGEMANAGER_SUCCESS', 'PACKAGEMANAGER_FAILED', 'PACKAGEMANAGER_NEWVERSION']);

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('backbtn'))
                ->setText('PACKAGES_BACKTODASHBOARD')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/dashboard'))
                ->setIcon('chevron-circle-left'),
            (new \fpcm\view\helper\linkButton('protobtn'))
                ->setText('HL_LOGS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/logs'))
                ->setIcon('exclamation-triangle')
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW),
            (new \fpcm\view\helper\linkButton('optionsBtn'))
                ->setText('HL_OPTIONS_SYSTEM')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/options', ['rg' => 4]))
                ->setIcon('cog')
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
                ->setIconOnly()
        ]);

        $this->view->addTabs('updater', [
            (new \fpcm\view\helper\tabItem('sysupdate'))->setText('HL_PACKAGEMGR_SYSUPDATES')->setFile($this->getViewPath())
        ]);

        $this->view->addJsFiles(['packages/updater.js']);
        $this->view->render();
    }

    /**
     *
     * @param bool $data
     * @return bool
     */
    private function invert($data)
    {
        return !$data;
    }

    /**
     *
     * @return array
     */
    private function initStepsDef() : array
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
