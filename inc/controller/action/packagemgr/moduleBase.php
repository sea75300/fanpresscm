<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class moduleBase extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\packagemgr\initialize;

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
        'cleanup' => true
    ];

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
        
        if (!\fpcm\module\module::validateKey($this->key)) {
            $this->view = new \fpcm\view\error('MODULES_KEY_INVALID');
            return false;
        }

        $this->updateDb = ($this->request->fromGET('update-db') !== null);
        $this->updateMultiple = $this->request->fromGET('updateKeys') ? true : false;

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
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('backbtn'))->setText('MODULES_LIST_BACKTOLIST')->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))->setIcon('chevron-circle-left'),
            (new \fpcm\view\helper\linkButton('protobtn'))->setText('HL_LOGS')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/logs'))->setIcon('exclamation-triangle')->setTarget('_blank'),
        ]);
        
        $tabText = $this->language->translate($this->steps['tabHeadline']).': '. $this->key;
        
        $this->view->addTabs('updater', [
            (new \fpcm\view\helper\tabItem('sysupdate'))->setText($tabText)->setFile($this->getViewPath())
        ]);
        
        $this->assignMultipleUpdates();
        $this->view->addJsFiles(['modules/installer.js']);
        $this->view->render();
  
    }

    /**
     * Adds button to update next module
     * @return bool
     */
    private function assignMultipleUpdates() : bool
    {
        if (!$this->updateMultiple) {
            return true;
        }

        $updateKeys = $this->request->fromGET('updateKeys', [
            \fpcm\model\http\request::FILTER_URLDECODE,
            \fpcm\model\http\request::FILTER_BASE64DECODE,
            \fpcm\model\http\request::FILTER_DECRYPT
        ]);
        
        if ($updateKeys === null || !trim($updateKeys)) {
            return false;
        }
        
        $updateKeys = explode(';', $updateKeys);
        if (!count($updateKeys)) {
            return false;
        }

        $this->view->addButton(
            (new \fpcm\view\helper\linkButton('runUpdateNext'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('package/modupdate', [
                    'key' => array_shift($updateKeys),
                    'updateKeys' => base64_decode(implode(';', $updateKeys))
                ])
            )->setText('MODULES_LIST_UPDATE_NEXT')
            ->setIcon('sync')
            ->setClass('fpcm ui-hidden')
            ->setPrimary()
        );

        return true;
    }
    
}
