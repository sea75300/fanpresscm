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
class setReminder extends \fpcm\controller\abstracts\ajaxController
{
    use \fpcm\controller\traits\common\isAccessibleTrue,
        \fpcm\controller\traits\reminder\check;

    /**
     * Reminder id
     * @var int
     */
    private int $rid;

    /**
     * Object id
     * @var int
     */
    private int $oid;

    /**
     * User id
     * @var int
     */
    private int $uid;

    /**
     * Reminder time
     * @var int
     */
    private array $time;

    /**
     * Remidner comment
     * @var string
     */
    protected string $comment;

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->rid = $this->request->fetchAll('rid', [\fpcm\model\http\request::FILTER_CASTINT]);
        $this->oid = $this->request->fetchAll('oid', [\fpcm\model\http\request::FILTER_CASTINT]);
        $this->uid = $this->request->fetchAll('uid', [\fpcm\model\http\request::FILTER_CASTINT]);
        $this->time = $this->request->fetchAll('time');
        $this->comment = $this->request->fetchAll('comment');
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!$this->oid || !$this->uid || !is_array($this->time) || !$this->processByParam('check', 'type')) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('REMINDER_SAVE_FAILED'),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();

            return false;
        }
        
        $dt = \fpcm\classes\tools::getTimestampFromString($this->time['date'], $this->time['time']);

        $id = $this->rid ?? null;

        $obj = new \fpcm\model\reminders\reminder($id);
        $obj->setOid($this->oid);
        $obj->setUserID($this->uid);
        $obj->setObjName($this->type);
        $obj->setComment($this->comment);
        $obj->setTime($dt);

        $fn = $this->rid ? 'update' : 'save';
        
        try {
            $res = $obj->{$fn}();
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
