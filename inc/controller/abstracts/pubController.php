<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

if (!defined('FPCM_MODE_NOPAGETOKEN')) {
    define('FPCM_MODE_NOPAGETOKEN', true);
}

if (!defined('FPCM_MODE_PUBVIEW')) {
    define('FPCM_MODE_PUBVIEW', true);
}


/**
 * Basis für "public"-Controller
 *
 * @abstract
 * @package fpcm\controller\abstracts\pubController
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class pubController extends controller {

    /**
     * View events namespace
     * @var bool
     */
    protected $viewEvents = false;

    /**
     * Constructor
     * @ignore
     */
    public function __construct()
    {
        $this->request = \fpcm\classes\loader::getObject('\fpcm\model\http\request');
        if (\fpcm\classes\baseconfig::installerEnabled() || !\fpcm\classes\baseconfig::dbConfigExists()) {
            exit;
        }

        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $this->config->setUserSettings();

        $this->ipList = \fpcm\classes\loader::getObject('\fpcm\model\ips\iplist');
        $this->crons = \fpcm\classes\loader::getObject('\fpcm\model\crons\cronlist');

        $this->crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->config->system_lang);

        $this->hasActiveModule();
        $this->initActionObjects();
        $this->initView();
    }

    /**
     * Access check processing,
     * false prevent execution of @see request() @see process()
     * @return bool
     */
    public function hasAccess()
    {
        if (!$this->maintenanceMode(false) && !$this->session->exists()) {
            return false;
        }

        if ($this->ipList->ipIsLocked()) {
            $this->view = null;
            print $this->language->translate('ERROR_IP_LOCKED');
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);

        $currentUserId = false;
        $isAdmin = false;

        if ($this->session->exists()) {
            $currentUserId = $this->session->getUserId();
            $isAdmin = $this->session->getCurrentUser()->isAdmin();
        }

        $this->view->setViewVars([
            'currentUserId' => $currentUserId,
            'isAdmin' => $isAdmin,
            'hideDebug' => false,
        ]);

        $jsfiles = [];
        if (!defined('FPCM_PUBJS_LOADED')) {
            $jsfiles[]  = \fpcm\model\pubtemplates\template::getPublicJavascript();
        }

        $evJs = $this->events->trigger('pub\addJsFiles', $jsfiles);
        if (!$evJs->getSuccessed() || !$evJs->getContinue()) {
            trigger_error(sprintf("Event pub\addJsFiles failed. Returned success = %s, continue = %s", $evJs->getSuccessed(), $evJs->getContinue()));
            return false;
        }

        $evCss = $this->events->trigger('pub\addCssFiles', []);
        if (!$evCss->getSuccessed() || !$evCss->getContinue()) {
            trigger_error(sprintf("Event pub\addCssFiles failed. Returned success = %s, continue = %s", $evCss->getSuccessed(), $evCss->getContinue()));
            return false;
        }

        $this->view->addJsVars([
            'spinnerUrl' => \fpcm\classes\dirs::getPublicAssetUrl('spinner.gif')
        ]);

        $this->view->overrideJsFiles($evJs->getData());
        $this->view->overrideCssFiles($evCss->getData());
        $this->view->addJsLangVars([], true);
        return true;
    }

}
