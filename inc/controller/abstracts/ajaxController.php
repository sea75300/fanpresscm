<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

/**
 * Basis für AJAX-Controller
 * 
 * @package fpcm\controller\abstracts\ajaxController
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 */
class ajaxController extends controller {

    /**
     * Update-Check de/aktivieren
     * @var bool
     */
    protected $updateCheckEnabled = false;

    /**
     * Cache name
     * @var string
     */
    protected $moduleCheckExit = false;

    /**
     * response object
     * @var \fpcm\model\http\response
     */
    protected $response = null;

    /**
     * Initialises view object
     * @return bool
     */
    protected function initView()
    {
        parent::initView();
        if ($this->view instanceof \fpcm\view\view) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        }

        if (!$this->response instanceof \fpcm\model\http\response) {
            $this->response = new \fpcm\model\http\response();
        }
        
        return true;
    }

    /**
     * Redirect if user is not logged in
     * @return bool
     */
    protected function redirectNoSession()
    {
        $this->response->setCode(401)->addHeaders('HTTP/1.1 401 Unauthorized')->fetch();
    }

    /**
     * Check page token
     * @param string $name
     * @return bool
     * @since 4.3
     */
    final protected function checkPageToken($name = 'token')
    {
        $res = parent::checkPageToken($this->request->fetchAll('module'));
        if (!$res) {
            $this->response->setCode(400)->addHeaders('HTTP/1.1 400 Bad Request')->fetch();
            return false;
        }
        
        return true;
    }

    /**
     * Maintenance mode check
     * @param string $simplemsg
     * @return bool
     * @since 5.2.0-a1
     */
    protected function maintenanceMode($simplemsg = true) : bool
    {
        if (!$this->config->system_maintenance || ($this->session->exists() && $this->session->getCurrentUser()->isAdmin())) {
            return true;
        }
        
        $this->response->setCode(503)->addHeaders('HTTP/1.1 401 Service in is maintenance')->fetch();
    }

}
