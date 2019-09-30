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
     * Rückgabe-Code
     * @var string
     */
    protected $returnCode;

    /**
     * Rückgabe-Daten
     * @var mixed
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
     * Initialises view object
     * @return bool
     */
    protected function initView()
    {
        parent::initView();
        if ($this->view instanceof \fpcm\view\view) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        }

        return true;
    }

    /**
     * JSON-codiertes Array mit Rückgabe-Code und ggf. Rückgabe-Daten erzeugen
     * @return void
     * @since FPCM 3.2
     */
    protected function getResponse()
    {
        $data = array(
            'code' => $this->returnCode,
            'data' => $this->returnData
        );

        exit(json_encode($data));
    }

    /**
     * JSON-codiertes Array nur mit Nutzdaten als Rückgabe erzeugen
     * @return void
     * @since FPCM 3.6
     */
    protected function getSimpleResponse()
    {
        exit(json_encode($this->returnData));
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
     * Sets Header to return JSON data
     * @return boolean
     * @since FPCm 4.3.0
     */
    protected function setReturnJson()
    {
        header('Content-Type: application/json');
        return true;
    }

}

?>