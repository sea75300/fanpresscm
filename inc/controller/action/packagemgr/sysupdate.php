<?php

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class sysupdate extends \fpcm\controller\abstracts\controller {

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
        'download'  => true,
        'checkPkg'  => true,
        'extract'   => true,
        'updateFs'  => true,
        'updateDb'  => true,
        'updateLog' => true,
        'cleanup'   => true
    ];

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
        $this->updateDb = ($this->getRequestVar('update-db') !== null);

        return parent::request();
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $jsData = [];
        
        if ($this->updateDb) {
            $this->steps = array_map([$this, 'invert'], $this->steps);
            $this->steps['updateDb'] = true;
        }
        else {
            $updater = new \fpcm\model\updater\system();
            $this->steps['pkgurl'] = $updater->url;
            $this->steps['pkgname'] = basename($updater->url);
        }

        
        $this->view->setViewVars($this->steps);
        $this->view->addJsVars([
            'pkgdata' => [
                'update' => $jsData
            ]
        ]);
        
        $this->view->addButton( (new \fpcm\view\helper\linkButton('backbtn'))->setText('PACKAGES_BACKTODASHBOARD')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/dashboard'))->setIcon('chevron-circle-left') );
        $this->view->addJsFiles(['updater.js']);
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

}
