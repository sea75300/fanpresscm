<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX-Controller - System Check
 *
 * @package fpcm\controller\ajax\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class syscheck extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'system/syscheck';
    }

    /**
     * is accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::hasAccess()
     * @return bool
     */
    public function hasAccess()
    {
        $dbConf= \fpcm\classes\baseconfig::dbConfigExists();

        if (!\fpcm\classes\baseconfig::installerEnabled() && !$dbConf) {
            return true;
        }

        if ($dbConf && $this->session->exists() && $this->isAccessible()) {
            return true;
        }

        return false;
    }

    /**
     * Controller processing
     * @return bool
     */
    public function process()
    {
        $check = new \fpcm\model\system\check\check();

        $this->view->assign('checkOptions', $check->runCheck());
        $this->view->render();
        return true;
    }

}
