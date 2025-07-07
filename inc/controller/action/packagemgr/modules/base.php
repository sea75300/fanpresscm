<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\modules;

abstract class base extends \fpcm\controller\action\packagemgr\abstracts\base
{

    protected \fpcm\model\updater\modules $updater;

    /**
     * Module-Keys
     * @var array
     */
    protected $key;

    /**
     * Add multiple button
     * @var bool
     */
    protected $updateMultiple = false;

    /**
     *
     * @var array
     */
    protected $jsVars = [];

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->modules->install;
    }

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

        $this->key = $this->request->fromGET('key', [
            \fpcm\model\http\request::FILTER_URLDECODE
        ]);

        $this->updateMultiple = $this->key === \fpcm\module\modules::MODULES_ALL;

        if (!$this->updateMultiple && !\fpcm\module\module::validateKey($this->key)) {
            $this->view = new \fpcm\view\error('MODULES_KEY_INVALID');
            return false;
        }

        parent::request();

        $this->steps['backupFs'] = false;

        return trim($this->key) ? true : false;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->updater = new \fpcm\model\updater\modules();

        $modPkg = $this->updater->getDataCachedByKey($this->key);

        $keys = (new \fpcm\module\modules())->getInstalledUpdates();
        
        $jsData = [
            'pkgKey' => $this->key,
            'multiple' => $this->updateMultiple,
            'pkgKeys' => $keys,
            'pkgHashes' => array_map(function($key) {
                return \fpcm\classes\tools::getHash($key);
            }, $keys)
        ];

        if ($this->updateDb) {
            $this->steps = array_map([$this, 'invert'], $this->steps);
            $this->steps['updateDb'] = true;
        }
        else {
            $jsData['pkgurl'] = $modPkg['packageUrl'] ?? '';
            $jsData['pkgname'] = basename($modPkg['packageUrl'] ?? '');
            $jsData['pkgsize'] = isset($modPkg['size']) ? \fpcm\classes\tools::calcSize($modPkg['size']) : $this->language->translate('GLOBAL_UNKNOWN');
        }

        $count = 0;

        $jsData['steps'] = $this->getActiveSteps($count);
        $this->steps['stepcount'] = $count;

        $this->view->setViewVars($this->steps);
        $this->view->addJsVars([
            'pkgdata' => $jsData,
            'stepcount' => $this->steps['stepcount'],
            'action' => $this->getMode()
        ]);

        $buttons = [
            (new \fpcm\view\helper\linkButton('backbtn'))
                ->setText('MODULES_LIST_BACKTOLIST')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))
                ->setIcon('chevron-circle-left'),
            (new \fpcm\view\helper\linkButton('protobtn'))
                ->setText('HL_LOGS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/logs'))
                ->setIcon('exclamation-triangle')
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW),
        ];

        $this->view->addButtons($buttons);
        $this->view->addJsFiles(['packages/manager.js', 'packages/modules.js']);

        parent::process();

    }

    /**
     * Gte default step definition
     * @return array
     */
    protected function initStepsDef(): array
    {
        $steps = parent::initStepsDef();

        $steps['finish'] = new \fpcm\model\packages\step(
            $this->language->translate('GLOBAL_FINISHED'),
            '',
            'stopTimer',
            new \fpcm\view\helper\icon('circle-check'),
            '',
            ''
        );

        return $steps;
    }

    abstract protected function getMode() : string;
}
