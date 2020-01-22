<?php

/**
 * AJAX add message controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX-Controller zum Erzeugen und Ausgeben einer neuen Nachricht
 * 
 * @package fpcm\controller\ajax\commom.addmsg
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class addmsg extends \fpcm\controller\abstracts\ajaxControllerJSON {

    /**
     * Controller-Processing
     */
    public function process()
    {
        $type = $this->getRequestVar('type');
        $msg = $this->getRequestVar('msgtxt');

        $icon   = ($type === \fpcm\view\message::ICON_ERROR
                ? \fpcm\view\message::ICON_ERROR
                : ($type === \fpcm\view\message::TYPE_NOTICE ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_NEUTRAL));

        $this->returnData[] = new \fpcm\view\message($this->language->translate($msg), $type, $icon);
        $this->getResponse();
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