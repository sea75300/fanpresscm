<?php
    /**
     * AJAX add message controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\common;
    
    /**
     * AJAX-Controller zum Erzeugen und Ausgeben einer neuen Nachricht
     * 
     * @package fpcm\controller\ajax\commom.addmsg
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class addmsg extends \fpcm\controller\abstracts\ajaxController {

        /**
         * Controller-Processing
         */
        public function process() {
            parent::process();

            $view = new \fpcm\model\view\ajax();
            
            $type = $this->getRequestVar('type');
            $msg  = $this->getRequestVar('msgtxt');
            
            $str  = $this->lang->translate($msg);
            if (!$str) {
                $str = $msg;
            }

            $this->returnData[] = array(
                'txt'  => $str,
                'type' => $type,
                'id'   => md5($type.$msg),
                'icon' => $type === 'error' ? 'exclamation-triangle' : ( $type === 'notice' ? 'check' : 'info-circle' )
            );

            $this->getResponse();
        }

    }
?>