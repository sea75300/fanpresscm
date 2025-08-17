<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\pub;

define('FPCM_NOTOKEN', true);

/**
 * AJAX controlelr to work comments
 *
 * @package fpcm\controller\ajax\pub
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class comments extends \fpcm\controller\abstracts\ajaxController {

    /**
     *
     * @return bool
     */
    public function hasAccess() : bool
    {
        if (!$this->checkReferer(true)) {
            $this->response->setCode(500)->fetch();
        }

        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request() : bool
    {
        if (!$this->config->system_comments_enabled) {
            $this->response->setCode(500)->fetch();
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process() : bool
    {
        fpcmLogSystem($this->request->fromPOST(null));
        
        $act = $this->request->fromPOST('action');
        if (!$act) {
            $this->response->setCode(500)->fetch();
            return true;
        }

        $actFn = 'process'.ucfirst($act);
        if (!method_exists($this, $actFn)) {
            $this->response->setCode(500)->fetch();
            return true;
        }

        return true;
    }
    
    protected function processSave()
    {
        
        
        $comment = $this->request->fromPOST('comment');
    }

}
