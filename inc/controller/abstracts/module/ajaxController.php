<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts\module;

/**
 * Module AJAX controller base
 * 
 * @package fpcm\module
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 * @since FPCM 4.1
 */
class ajaxController extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\modules\tools;
    
    /**
     * Konstruktor
     * @return void
     */
    final public function __construct()
    {
        parent::__construct();
        $this->initConstruct();
    }

    /**
     * Initialises view object
     * @return bool
     */
    final protected function initView()
    {
        return parent::initView();
    }

    /**
     * Description @see \fpcm\controller\abstracts\ajaxController::getResponse
     * @return void
     */
    final protected function getResponse()
    {
        parent::getResponse();
    }

    /**
     * Description @see \fpcm\controller\abstracts\ajaxController::getSimpleResponse
     * @return void
     */
    final protected function getSimpleResponse()
    {
        parent::getSimpleResponse();
    }

}