<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class moduleInstaller extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\packagemgr\initialize;

    /**
     * Module-Keys
     * @var array
     */
    protected $key;

    /**
     *
     * @var array
     */
    protected $steps = [
        'download'  => true,
        'checkPkg'  => true,
        'extract'   => true,
        'updateFs'  => true,
        'updateDb'  => true,
        'updateLog' => true,
        'cleanup'   => true
    ];

    protected function getViewPath()
    {
        return 'packagemgr/modules';
    }

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
        if (!\fpcm\classes\baseconfig::canConnect()) {
            return false;
        }

        $this->key = $this->getRequestVar('key', [
            \fpcm\classes\http::FILTER_URLDECODE
        ]);

        return trim($this->key) ? true : false;
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        $jsData = [];

        $updater = (new \fpcm\model\updater\modules())->getDataCachedByKey($this->key);
        $this->steps['pkgurl'] = $updater['packageUrl'];
        $this->steps['pkgname'] = basename($updater['packageUrl']);

        $this->view->setViewVars($this->steps);
        $this->view->addJsVars([
            'pkgdata' => [
                'module' => $jsData
            ],
            'modinstaller' => [
                'action' => 'install',
                'key' => $this->key
            ]
        ]);
        
        $this->view->addButton( (new \fpcm\view\helper\linkButton('backbtn'))->setText('MODULES_LIST_BACKTOLIST')->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))->setIcon('chevron-circle-left') );
        $this->view->addJsFiles(['moduleinstaller.js']);
        $this->view->render();
    }

}
