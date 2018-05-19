<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

/**
 * Basis für "public"-Controller
 * 
 * @abstract
 * @package fpcm\controller\abstracts\pubController
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class pubController extends controller {


    /**
     *
     * @var bool
     */
    protected $viewEvents = false;

    /**
     * 
     * @return boolean
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

        return $this->hasActiveModule();
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        if ($this->config->system_mode) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        }

        $showToolbars = false;
        $permAdd = false;
        $permEditOwn = false;
        $permEditAll = false;
        $currentUserId = false;
        $isAdmin = false;

        if ($this->session->exists()) {
            $showToolbars = true;
            $permAdd = $this->permissions->check(['article' => 'add']);
            $permEditOwn = $this->permissions->check(['article' => 'edit']);
            $permEditAll = $this->permissions->check(['article' => 'editall']);
            $currentUserId = $this->session->getUserId();
            $isAdmin = $this->session->getCurrentUser()->isAdmin();
        }

        $this->view->setViewVars([
            'showToolbars' => $showToolbars,
            'permAdd' => $permAdd,
            'permEditOwn' => $permEditOwn,
            'permEditAll' => $permEditAll,
            'currentUserId' => $currentUserId,
            'isAdmin' => $isAdmin,
            'hideDebug' => false,
            'systemMode' => $this->config->system_mode
        ]);

        $jsfiles = [];
        if ($this->config->system_loader_jquery) {
            $jsfiles[] = \fpcm\classes\dirs::getLibUrl('jquery/jquery-3.3.1.min.js');
        }
        $jsfiles[] = \fpcm\classes\dirs::getRootUrl('js/fpcm.js');

        $cssfiles = [];
        if ($this->config->system_mode == 0 && trim($this->config->system_css_path)) {
            $cssfiles[] = trim($this->config->system_css_path);
        }

        $this->view->overrideJsFiles($this->events->trigger('pub\addJsFiles', $jsfiles));
        $this->view->overrideCssFiles($this->events->trigger('pub\addCssFiles', $cssfiles));

        return true;
    }

}

?>