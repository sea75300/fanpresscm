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
     * @param array $oids
     * @return array|\fpcm\model\reminders\reminder
     */
    public function getRemindersForDatasets(string $type = '', int $start = 0, array $oids = [])
    {
        $ch = \fpcm\classes\tools::getHash(__METHOD__.$type.implode('', $oids));

        $c = $this->data[$ch] ?? null;
        if ($c !== null) {
            return $c;
        }

        $uid = \fpcm\model\system\session::getInstance()?->getUserId();
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
            $icon->setData([
                'rid' => $rem->getId()
            ]);

            $notifications->addNotification( new \fpcm\model\theme\notificationItem($icon, '', '', 'text-success') );
        }

    }

}
