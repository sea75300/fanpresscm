<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX session check controller
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.1.0
 */
class sessioncheck extends \fpcm\controller\abstracts\ajaxController {

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!is_object($this->session) || !$this->session->exists()) {
            exit('0');
        }

        if ($this->getIpLockedModul() && $this->ipList->ipIsLocked($this->getIpLockedModul())) {
            exit('0');
        }
        
        exit('1');
    }

}

?>