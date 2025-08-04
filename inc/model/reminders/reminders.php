<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\reminders;

/**
 * Resubmissions list object
 *
 * @package fpcm\model\reminders
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class reminders
extends \fpcm\model\abstracts\tablelist
implements \fpcm\model\interfaces\isObjectInstancable {

    use \fpcm\model\traits\getObjectInstance;

    /**
     *  Table name
     * @var string
     */
    protected $table = 'reminders';

    /**
     * Fetch reminders for given type and object ids
     * @param string $type
     * @param int $start
     * @param array $oids
     * @param int|null $uid
     * @return array|\fpcm\model\reminders\reminder
     */
    public function getRemindersForDatasets(string $type = '', int $start = 0, array $oids = [], ?int $uid = null)
    {
        $ch = \fpcm\classes\tools::getHash(__METHOD__.$type.implode('', $oids));

        $c = $this->data[$ch] ?? null;
        if ($c !== null) {
            return $c;
        }

        $uid = (int) ( $uid ?? \fpcm\model\system\session::getInstance()?->getUserId() );
        if (!$uid) {
            return [];
        }

        $query = 'user_id = ?';
        $params = [$uid];

        if ($type) {
            $query .= ' AND obj_name = ?';
            $params[] = $type;
        }

        if ($start) {
            $query .= ' AND resubtime <= ?';
            $params[] = $start;
        }

        if (count($oids)) {
            $query .= ' AND ' . $this->dbcon->inQuery('oid', $oids);
            $params = array_merge($params, $oids);
        }

        $query .= $this->dbcon->orderBy(['resubtime DESC']);
        
        $select = new \fpcm\model\dbal\selectParams($this->table);
        $select->setWhere($query);
        $select->setParams($params);
        $select->setFetchAll();

        $return = $this->dbcon->selectFetch($select);
        if (!$return) {
            return [];
        }

        $res = [];

        /* @var reminder $reminder */
        foreach ($return as $dataset) {
            $reminder = new reminder();
            if (!$reminder->createFromDbObject($dataset)) {
                continue;
            }

            $res[$reminder->getOid()] = $reminder;
        }

        $this->data[$ch] = $res;
        return $res;
    }

    /**
     * Append reminders to notifications
     * @param \fpcm\model\theme\notifications $notifications
     * @return bool
     */
    public function appendNotifications(\fpcm\model\theme\notifications &$notifications)
    {
        $list = $this->getRemindersForDatasets('', time() - 60);
        if (!count($list)) {
            return false;
        }

        /* @var $rem reminder */
        foreach ($list as $rem) {

            $icon = new \fpcm\view\helper\icon('bell');
            $icon->setText($rem->getDescription());

            $delBtn = new \fpcm\view\helper\button('set-read-notify-'.$rem->getId());
            $delBtn->setIcon('envelope-circle-check')
                    ->setText('GLOBAL_DELETE')
                    ->overrideButtonType('outline-secondary')
                    ->setData([
                        'set-read-notify' => $rem->getId(),
                        'set-read-type' => self::mapPublic($rem)
                    ]);

            $item = new \fpcm\model\theme\notificationItem($icon, '', $delBtn);

            $notifications->addNotification($item);
        }

        return true;
    }

    /**
     * Map internal type to public type
     * @param reminder $rem
     * @return string
     */
    public static function mapPublic(reminder $rem) : string
    {
        return match ($rem->getObjName()) {
            'fpcm\model\files\image' => 'files',
            default => ''
        };
    }

}
