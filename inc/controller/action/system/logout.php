<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Logout controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class logout extends \fpcm\controller\abstracts\controller {

    /**
     * Frontend-Redirect
     * @var bool
     */
    protected $redirectFE = false;

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->session->exists()) {
            $this->redirect('system/login');
            return true;
        }

        $this->redirectFE = $this->request->fromGET('redirect') !== null ? true : false;
        $this->session->setLogout(time());
        $this->session->update();
        $this->session->deleteCookie();

        return true;
    }

    /**
     * Controller-Processing
     * @return type
     */
    public function process()
    {
        if ($this->redirectFE) {
            header('Location: ' . $this->config->system_url);
            return;
        }

        $this->redirect('system/login');
    }

    /**
     * 
     * @return bool
     */
    protected function initPermissionObject(): bool
    {
        return true;
    }

}

?>
