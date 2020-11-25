<?php

namespace fpcm\controller\action\modules;

/**
 * Module details info controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class moduleInfo extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var \fpcm\module\module
     */
    protected $module;

    /**
     *
     * @var string
     */
    protected $key = '';

    /**
     *
     * @var bool
     */
    protected $repo = true;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->modules->configure || $this->permissions->modules->install || $this->permissions->modules->uninstall;
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->key = $this->request->fromGET('key');
        if (!$this->key || !\fpcm\module\module::validateKey($this->key)) {
            $this->view = new \fpcm\view\error('MODULES_KEY_INVALID');
            return false;
        }

        $this->repo = $this->request->fromGET('repo', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($this->repo) {
            $this->module = (new \fpcm\module\modules)->getFromRepository()[$this->key] ??  false;
            return true;
        }

        $this->module = new \fpcm\module\module($this->key);
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        if (!$this->module) {
            return false;
        }
        
        $this->view = new \fpcm\view\view();
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->addTabs('moduleInfo', [
            (new \fpcm\view\helper\tabItem('info-first'))->setText('MODULES_LIST_INFORMATIONS')->setFile('modules/info.php')
        ], 'm-2');

        $config = $this->module->getConfig();
                                        
        $this->view->assign('moduleName', $config->name);
        $this->view->assign('moduleAuthor', $config->author);
        $this->view->assign('moduleLink', $config->link);
        $this->view->assign('moduleSysVer', $config->requirements['system']);
        $this->view->assign('modulePhpVer', $config->requirements['php']);
        $this->view->assign('moduleDescription', $config->description);

        $data = \fpcm\classes\loader::getObject('\fpcm\model\updater\modules')->getDataCachedByKey($this->key);
        $this->view->assign('moduleDownload', $data['packageUrl'] ?? false);
        $this->view->assign('moduleHash', $data['hash'] ?? false);
        $this->view->assign('moduleVersion', $data['version']);

        $this->view->render();
    }

}

?>