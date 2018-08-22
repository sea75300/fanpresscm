<?php

/**
 * FanPress CM User List Model
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Benutzer-Liste Objekt
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class userList extends \fpcm\model\abstracts\tablelist {

    /**
     *
     * @var array
     */
    protected $userNameList = [];

    /**
     *
     * @var array
     */
    protected $userEmailList = [];

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableAuthors;

        parent::__construct();
    }

    /**
     * Return a list of all users
     * @param bool $byGroup
     * @return array
     */
    public function getUsersAll($byGroup = false)
    {
        $item = $this->dbcon->getTablePrefixed($this->table) . '.*, ';
        $item .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.leveltitle AS groupname';

        $where = $this->dbcon->getTablePrefixed($this->table) . '.roll = ';
        $where .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.id';
        $result = $this->dbcon->select(array($this->table, \fpcm\classes\database::tableRoll), $item, $where);
        $users = $this->dbcon->fetch($result, true);

        if (!$users || !count($users)) {
            return [];
        }

        return $this->getUserListResult($users, $byGroup);
    }

    /**
     * Liefert ein array aller Benutzer-Namen
     * @return array
     */
    public function getUsersNameList()
    {
        if (count($this->userNameList)) {
            return $this->userNameList;
        }

        $users = $this->dbcon->fetch($this->dbcon->select($this->table, 'id, displayname'), true);
        foreach ($users as $user) {
            $this->userNameList[$user->displayname] = $user->id;
        }

        return $this->userNameList;
    }

    /**
     * Liefert ein array aller Benutzer-E-Mail-Adressen
     * @return array
     */
    public function getUsersEmailList()
    {
        if (count($this->userEmailList)) {
            return $this->userEmailList;
        }

        $users = $this->dbcon->fetch($this->dbcon->select($this->table, 'id, email'), true);
        foreach ($users as $user) {
            $this->userEmailList[$user->email] = $user->id;
        }

        return $this->userEmailList;
    }

    /**
     * Liefert ein array aller aktiven Benutzer
     * @param bool $byGroup (@since FPCM 3.2.0)
     * @return array of \fpcm\model\users\author
     */
    public function getUsersActive($byGroup = false)
    {
        $item = $this->dbcon->getTablePrefixed($this->table) . '.*, ';
        $item .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.leveltitle AS groupname';

        $where = $this->dbcon->getTablePrefixed($this->table) . '.roll = ';
        $where .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.id';
        $where .= ' AND disabled = 0 ' . $this->dbcon->orderBy(array('id ASC'));

        $result = $this->dbcon->select(array($this->table, \fpcm\classes\database::tableRoll), $item, $where);
        $users = $this->dbcon->fetch($result, true);

        if (!$users || !count($users)) {
            return [];
        }

        return $this->getUserListResult($users, $byGroup);
    }

    /**
     * Liefert ein array aller aktiven Benutzer
     * @param bool $byGroup (@since FPCM 3.2.0)
     * @return array
     */
    public function getUsersDisabled($byGroup = false)
    {
        $item = $this->dbcon->getTablePrefixed($this->table) . '.*, ';
        $item .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.leveltitle AS groupname';

        $where = $this->dbcon->getTablePrefixed($this->table) . '.roll = ';
        $where .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.id';
        $where .= ' AND disabled = 1 ' . $this->dbcon->orderBy(array('id ASC'));

        $result = $this->dbcon->select(array($this->table, \fpcm\classes\database::tableRoll), $item, $where);
        $users = $this->dbcon->fetch($result, true);

        if (!$users || !count($users)) {
            return [];
        }

        return $this->getUserListResult($users, $byGroup);
    }

    /**
     * Gibt ID für den gegebenen Benutzer zurück
     * @param string $username
     * @return int
     */
    public function getUserIdByUsername($username)
    {
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, "id", "username = ?", array($username)));
        return isset($result->id) ? $result->id : false;
    }

    /**
     * Return a author object by user name
     * @param string $username
     * @return author
     */
    public function getUserByUsername($username)
    {
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, "*", "username = ?", [$username]));

        /* @var $user author */
        $user = \fpcm\classes\loader::getObject('\fpcm\model\users\author');
        $user->createFromDbObject($result);

        return $user;
    }

    /**
     * Gibt array mit Benutzern der übergebenen IDs zurück
     * @param array $ids
     * @return array
     */
    public function getUsersByIds(array $ids)
    {
        $item = $this->dbcon->getTablePrefixed($this->table) . '.*, ';
        $item .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.leveltitle AS groupname';

        $where = $this->dbcon->getTablePrefixed($this->table) . '.roll = ';
        $where .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll) . '.id AND ';
        $where .= $this->dbcon->getTablePrefixed($this->table) . '.id IN (' . implode(', ', $ids) . ') ';

        $result = $this->dbcon->select(array($this->table, \fpcm\classes\database::tableRoll), $item, $where);
        $users = $this->dbcon->fetch($result, true);

        if (!$users || !count($users)) {
            return [];
        }

        return $this->getUserListResult($users);
    }

    /**
     * Gibt E-Mail-Adresse für übergebene Benutzer-ID zurück
     * @param int $userId
     * @return string
     */
    public function getEmailByUserId($userId)
    {
        $res = $this->dbcon->fetch($this->dbcon->select($this->table, 'email', 'id = ?', array($userId)));
        return $res->email;
    }

    /**
     * mehrere Benutzer anhand von IDs löschen
     * @param array $ids
     * @return bool
     */
    public function deleteUsers(array $ids)
    {
        return $this->dbcon->delete($this->table, 'id IN (' . implode(',', $ids) . ')');
    }

    /**
     * Benutzer deaktivieren
     * @param array $ids
     * @return bool
     */
    public function diableUsers(array $ids)
    {
        return $this->dbcon->update($this->table, ['disabled'], [1], 'id IN (' . implode(',', $ids) . ')');
    }

    /**
     * Benutzer aktivieren
     * @param array $ids
     * @return bool
     */
    public function enableUsers(array $ids)
    {
        return $this->dbcon->update($this->table, ['disabled'], [0], 'id IN (' . implode(',', $ids) . ')');
    }

    /**
     * aktive Benutzer zählen
     * @return int
     */
    public function countActiveUsers()
    {
        return $this->dbcon->count($this->table, '*', 'disabled = 0');
    }

    /**
     * Liste von Benutzern zurückgeben, die in den übergebenen Artikeln verwendet wurden
     * @param array $articleIds
     * @return \fpcm\model\users\author[]
     * @since FPCM 3.6
     */
    public function getUsersForArticles(array $articleIds)
    {
        if (!count($articleIds)) {
            return [];
        }

        $where = '( id IN ( SELECT createuser FROM ' . $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableArticles) . ' WHERE id IN (' . implode(',', $articleIds) . ') ) )';
        $where .= ' OR ( id IN ( SELECT changeuser FROM ' . $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableArticles) . ' WHERE id IN (' . implode(',', $articleIds) . ') ) )';

        $result = $this->dbcon->select($this->table, 'id, displayname, email, username, usrinfo', $where, [], true);
        $users = $this->dbcon->fetch($result, true);

        if (!$users || !count($users)) {
            return [];
        }

        $data = [];
        foreach ($users as $value) {
            $usr = new author();
            $usr->createFromDbObject($value);
            $data[$usr->getId()] = $usr;
        }

        return $data;
    }

    /**
     * Erzeugt Array aus Benutzer-Liste
     * @param array $users
     * @param bool $byGroup
     * @return array
     * @since FPCM 3.2.0
     */
    private function getUserListResult(array $users, $byGroup = false)
    {
        $res = [];

        $functionName = 'userListResultBy';
        $functionName .= $byGroup ? 'Group' : 'Id';

        foreach ($users as $user) {
            $author = new author();
            if ($author->createFromDbObject($user) === false) {
                $author = null;
                continue;
            }

            $res = call_user_func(array($this, $functionName), $author, $res);
        }


        return $res;
    }

    /**
     * Fügt Eintrag aus Benutzer-Liste, gruppiert nach Gruppe, in Ergebnisliste ein
     * @param \fpcm\model\users\author $author
     * @param array $data
     * @return array
     * @since FPCM 3.2.0
     */
    private function userListResultByGroup(author $author, array $data)
    {
        $data[$author->getRoll()][$author->getId()] = $author;

        return $data;
    }

    /**
     * Fügt Eintrag aus Benutzer-Liste in Ergebnisliste ein
     * @param \fpcm\model\users\author $author
     * @param array $data
     * @return array
     * @since FPCM 3.2.0
     */
    private function userListResultById(author $author, array $data)
    {
        $data[$author->getId()] = $author;

        return $data;
    }

}
