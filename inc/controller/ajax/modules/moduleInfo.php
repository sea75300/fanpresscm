<?php

namespace fpcm\controller\ajax\modules;

/**
 * Module details info controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class moduleInfo extends \fpcm\controller\abstracts\ajaxController
{

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
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'modules/info';
    }

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

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        $config = $this->module->getConfig();

        $this->view->addJsFiles(['modules/info.js']);

        $this->view->assign('moduleKey', $this->module->getKey());
        $this->view->assign('moduleName', $config->name);
        $this->view->assign('moduleAuthor', $config->author);
        $this->view->assign('moduleLink', $config->link);
        $this->view->assign('moduleHelp', $config->help);
        $this->view->assign('moduleLicence', $config->licence ?? 'GPLv3');
        $this->view->assign('moduleLicenceUrl', $config->licenceUrl ?? 'https://www.gnu.org/licenses/gpl-3.0.html');
        $this->view->assign('moduleSupport', $config->support ?? $config->link);
        $this->view->assign('moduleSysVer', $config->requirements->system);
        $this->view->assign('modulePhpVer', $config->requirements->php);
        $this->view->assign('moduleDescription', $config->description);
        $this->view->assign('moduleKeyHash', \fpcm\classes\tools::getHash($this->module->getKey()));
        $this->view->assign('moduleDataPath', $config->useDataFolder ? \fpcm\model\files\ops::removeBaseDir($this->module->getDataPath(), true) : '-');
        $this->view->assign('moduleChangelogUrl', $this->module->getFullChangelogUrl());

        $data = \fpcm\classes\loader::getObject('\fpcm\model\updater\modules')->getDataCachedByKey($this->key);
        $this->view->assign('moduleDownload', $data['packageUrl'] ?? false);
        $this->view->assign('moduleInstall', $this->module->isInstallable() && !$this->module->isInstalled());
        $this->view->assign('moduleHash', $data['hash'] ?? false);
        $this->view->assign('moduleVersion', $data['version'] ?? '');

        $this->view->assign('statusInstalled', $this->module->isInstalled() );
        $this->view->assign('statusInstallable', $this->module->isInstallable() );
        $this->view->assign('statusFilesList', $this->module->hasFilesListFile() );
        $this->view->assign('statusWritable', $this->module->isWritable() );

        $this->view->render();
    }

}
