<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

/**
 * Basis für AJAX-Controller
 * 
 * @package fpcm\controller\abstracts\ajaxController
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 */
class ajaxController extends controller {

    /**
     * Rückgabe-Daten
     * @var mixed
     * @deprecated since version FPCM 4.5
     */
    protected $returnData;

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
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }

    /**
     * Check page token
     * @param string $name
     * @return bool
     * @since FPCM 4.3
     */
    final protected function checkPageToken($name = 'token')
    {
        $res = parent::checkPageToken($this->request->fetchAll('module'));
        if (!$res) {
            http_response_code(400);
            header('Bad Request');
            return false;
        }
        
        return true;
    }

}

?>