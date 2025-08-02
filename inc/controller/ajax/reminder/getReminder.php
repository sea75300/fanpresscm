<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\reminder;

/**
 * Reminder controller
 *
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class getReminder extends \fpcm\controller\abstracts\ajaxController
{
    use \fpcm\controller\traits\common\isAccessibleTrue,
        \fpcm\controller\traits\reminder\check;

    /**
     * Object id
     * @var int
     */
    private int $oid;

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->oid = $this->request->fetchAll('oid', [\fpcm\model\http\request::FILTER_CASTINT]);
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!$this->oid || !$this->processByParam('check', 'type')) {
            $this->response->setReturnData([])->fetch();

            return false;
        }
        
        $rem = new \fpcm\model\reminders\reminder($this->oid);
        if (!$rem->exists()) {
            $this->response->setReturnData([])->fetch();

            return false;
        }        
        
        $this->response->setReturnData($rem)->fetch();
        return true;
    }

}
