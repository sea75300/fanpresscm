<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Check if password is powned
 * 
 * @package fpcm\controller\ajax\system\passcheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class passcheck extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\isAccessibleTrue;

    /**
     * Check controlelr acccess
     * @return boolean
     */
    public function hasAccess()
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return true;
        }

        return parent::hasAccess();
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        (new \fpcm\model\http\response)->setReturnData(new \fpcm\model\http\responseData(
            (new \fpcm\model\users\passCheck($this->request->fromPOST('password')))->isPowned() ? 1 : 0
        ))->fetch();
    }

}

?>