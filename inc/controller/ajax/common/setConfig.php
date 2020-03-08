<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX-Controller zum Erzeugen und Ausgeben einer neuen Nachricht
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class setConfig extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\isAccessibleTrue;

    private $whiteList = ['file_view', 'dashboardpos'];

    /**
     * Controller-Processing
     */
    public function request()
    {
        $var    = $this->request->fromPOST('var');
        if (!in_array($var, $this->whiteList)) {
            trigger_error('Invalid variable '.$var);
            return false;
        }

        $usrmeta = $this->session->currentUser->getUserMeta();
        $usrmeta[$var] = $this->request->fromPOST('value');

        $this->session->currentUser->disablePasswordSecCheck();
        $this->session->currentUser->setPassword(null);
        $this->session->currentUser->setUserMeta($usrmeta);
        return $this->session->currentUser->update() === true ? true : false;
    }

}

?>