<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\reminders;

/**
 * Resubmission object
 *
 * @package fpcm\model\reminders
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class reminder
extends \fpcm\model\abstracts\model
implements \JsonSerializable {

    use \fpcm\model\traits\getFieldsParam;

    /**
     *  Table name
     * @var string
     */
    protected $table = 'reminders';

    /**
     * User id
     * @var int
     */
    protected int $user_id = 0;

    /**
     * Object id
     * @var int
     */
    protected int $oid = 0;

    /**
     * Resubmission time
     * @var int
     */
    protected int $resubtime = 0;

    /**
     * Object path
     * @var string
     */
    protected string $obj_name = '';

    /**
     * Remidner comment
     * @var string
     */
    protected string $comment = '';

    /**
     * Save reminder
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        $params = $this->getPreparedSaveParams();

        $res = $this->dbcon->insert($this->table, $params);
        if ($res === false) {
            throw new \Exception('REMINDER_SAVE_FAILED');
        }

        $this->id = $this->dbcon->getLastInsertId();
        if (!$this->id) {
            return false;
        }

        return true;
    }

    /**
     * Update existing reminder
     * @return bool
     * @throws Exception
     */
    public function update(): bool
    {
        $params = $this->getPreparedSaveParams();
        $params[] = $this->id;

        $fields = $this->getFieldFromSaveParams($params);
        $res = $this->dbcon->update($this->table, $fields, array_values($params), 'id = ?');
        if ($res === false) {
            throw new \Exception('REMINDER_SAVE_FAILED');
        }

        return true;
    }

    /**
     * Delete reminder
     * @return bool
     * @throws \Exception
     */
    public function delete(): bool
    {
        $res = parent::delete();
        if ($res === false) {
            throw new \Exception('REMINDER_DELETE_FAILED');
        }

        return true;
    }

    /**
     * return user id or -1
     * @return int
     */
    public function getUserID(): int {
        return $this->user_id;
    }

    /**
     *
     * @return int
     */
    public function getOid(): int {
        return $this->oid;
    }

    /**
     * Returns reminder time
     * @return int
     */
    public function getTime(): int {
        return $this->resubtime;
    }

    /**
     * Returns reminder object path
     * @return string
     */
    public function getObjName(): string {
        return $this->obj_name;
    }

    /**
     * Set user id
     * @param int $user_id
     * @return $this
     */
    public function setUserID(int $user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Set object id
     * @param int $oid
     * @return $this
     */
    public function setOid(int $oid) {
        $this->oid = $oid;
        return $this;
    }

    /**
     * Set reminder time
     * @param int $resubtime
     * @return $this
     */
    public function setTime(int $resubtime) {
        $this->resubtime = $resubtime;
        return $this;
    }

    /**
     * Set reminder object path
     * @param string $obj_name
     * @return $this
     */
    public function setObjName(string $obj_name) {
        $this->obj_name = $obj_name;
        return $this;
    }

    /**
     * Fetch reminder comment
     * @return string
     */
    public function getComment(): string {
        return $this->comment;
    }

    /**
     * Set reminder comment
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * JSON data
     * @return array
     * @ignore
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uid' => $this->user_id,
            'oid' => $this->oid,
            'dateTime' => [
                'date' => date('Y-m-d', $this->resubtime),
                'time' => date('H:i:s', $this->resubtime),
            ],
            'comment' => $this->comment
        ];
    }

    /**
     * Returns description for reminder
     * @return string
     */
    public function getDescription() : string
    {
        $pattern = "%s: %s";
        $params = [
            (string) new \fpcm\view\helper\dateText($this->resubtime),
            $this->language->translate('HL_REMINDER'),
        ];

        if ($this->comment) {
            $params[1] = $this->comment;
        }

        return vsprintf($pattern, $params);
    }
}
