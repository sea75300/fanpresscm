<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Check if password is powned
 * 
 * @package fpcm\controller\ajax\system\cronasync
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class passcheck extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->returnData = (new \fpcm\model\users\passCheck($this->getRequestVar('password')))->isPowned() ? 1 : 0;
        $this->getSimpleResponse();
    }

}

?>