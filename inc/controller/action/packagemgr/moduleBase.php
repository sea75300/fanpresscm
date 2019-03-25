<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class moduleBase extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\packagemgr\initialize;

    /**
     * Module-Keys
     * @var array
     */
    protected $key;

    /**
     * Keep maintenance mode
     * @var bool
     */
    protected $keepMaintenance = false;

    /**
     * 
     * @var array
     */
    protected $jsVars = [];

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
        'checkFs' => false,
        'download' => true,
        'checkPkg' => true,
        'extract' => true,
        'updateFs' => true,
        'updateDb' => true,
        'updateLog' => true,
        'cleanup' => true,
        'keepMaintenance' => false
    ];

    /**
     * 
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'packagemgr/modules';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!\fpcm\classes\baseconfig::canConnect()) {
            return false;
        }

        $this->key = $this->getRequestVar('key', [
            \fpcm\classes\http::FILTER_URLDECODE
        ]);

        $this->keepMaintenance = $this->getRequestVar('keepMaintenance', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $this->updateDb = ($this->getRequestVar('update-db') !== null);

        return trim($this->key) ? true : false;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $updater = (new \fpcm\model\updater\modules())->getDataCachedByKey($this->key);
        $this->steps['pkgKey'] = $this->key;
        $this->steps['pkgurl'] = $updater['packageUrl'];
        $this->steps['pkgname'] = basename($updater['packageUrl']);
        $this->steps['pkgsize'] = isset($updater->size) && $updater->size ? '(' . \fpcm\classes\tools::calcSize($updater->size) . ')' : '';

        $this->view->setViewVars($this->steps);
        $this->view->addJsVars($this->jsVars);

        $this->view->addButton((new \fpcm\view\helper\linkButton('backbtn'))->setText('MODULES_LIST_BACKTOLIST')->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))->setIcon('chevron-circle-left'));
        $this->view->addJsFiles(['moduleinstaller.js']);
        $this->view->render();
    }

}
