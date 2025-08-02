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
class deleteReminder extends \fpcm\controller\abstracts\ajaxController
{
    use \fpcm\controller\traits\common\isAccessibleTrue,
        \fpcm\controller\traits\reminder\check;

    /**
     * Object id
     * @var int
     */
    private int $rid;

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->rid = $this->request->fetchAll('rid', [\fpcm\model\http\request::FILTER_CASTINT]);
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!$this->rid || !$this->processByParam('check', 'type')) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('REMINDER_DELETE_FAILED'),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
            return false;
        }

        $rem = new \fpcm\model\reminders\reminder($this->rid);
        if (!$rem->exists()) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('REMINDER_DELETE_FAILED'),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
            return false;
        }


        try {
            $res = $rem->delete();
        } catch (\Exception $exc) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate($exc->getMessage()),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
            return false;
        }

        $this->response->setReturnData([
            'reload' => $res
        ])->fetch();

        return true;
    }

}
