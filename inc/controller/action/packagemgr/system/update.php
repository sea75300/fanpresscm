<?php

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\system;

class update extends \fpcm\controller\action\packagemgr\abstracts\base
{

    protected \fpcm\model\updater\system $updater;

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
        
        $count = 0;

        $jsData['steps'] = $this->getActiveSteps($count);
        $this->steps['stepcount'] = $count;

        $this->view->setViewVars($this->steps);
        $this->view->addJsVars([
            'pkgdata' => [
                'update' => $jsData,
            ],
            'stepcount' => $this->steps['stepcount']
        ]);
        
        parent::process();

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

}
