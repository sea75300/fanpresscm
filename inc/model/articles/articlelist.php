<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * FanPress CM Article List Model
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\articles
 */
class articlelist extends \fpcm\model\abstracts\tablelist {

    use permissions;

    /**
     * Permission Object
     * @var \fpcm\model\system\permissions
     * @since FPCM 3.3
     */
    protected $permissions = false;

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableArticles;

        if (is_object(\fpcm\classes\loader::getObject('\fpcm\model\system\session')) && \fpcm\classes\loader::getObject('\fpcm\model\system\session')->exists()) {
            $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\system\permissions');
        }

        parent::__construct();
    }

    /**
     * Gibt Liste mit allen nicht gelöschten Artikeln zurück
     * @param bool $monthIndex Liste mit Monatsindex zurückgeben
     * @param array $limits Anzahl der zurückgegebenen Artikel einschränken array(Start,Anzahl)
     * @param bool $countOnly Verfügbare Artikel nur zählen
     * @return array
     */
    public function getArticlesAll($monthIndex = false, array $limits = array(), $countOnly = false)
    {
        $where = 'draft = 0 AND deleted = 0';

        if ($countOnly)
            return (int) $this->dbcon->count($this->table, 'id', $where);

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits))
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);

        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', $where), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen aktiven Artikeln zurück
     * @param bool $monthIndex Liste mit Monatsindex zurückgeben
     * @param array $limits Anzahl der zurückgegebenen Artikel einschränken array(Start,Anzahl)
     * @param bool $countOnly Verfügbare Artikel nur zählen
     * @return array
     */
    public function getArticlesActive($monthIndex = false, array $limits = array(), $countOnly = false)
    {
        $where = 'archived = 0 AND deleted = 0';

        if ($countOnly)
            return (int) $this->dbcon->count($this->table, 'id', $where);

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits))
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);

        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', $where), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen archivierten Artikeln zurück
     * @param bool $monthIndex Liste mit Monatsindex zurückgeben
     * @param array $limits Anzahl der zurückgegebenen Artikel einschränken array(Start,Anzahl)
     * @param bool $countOnly Verfügbare Artikel nur zählen
     * @param bool $dateLimit Einschränkung auf nach Datum
     * @return array
     */
    public function getArticlesArchived($monthIndex = false, array $limits = array(), $countOnly = false, $dateLimit = false)
    {
        $where = 'archived = 1 AND deleted = 0';
        if ($dateLimit && $this->config->articles_archive_datelimit) {
            $where .= ' createtime >= ' . $this->config->articles_archive_datelimit;
        }

        if ($countOnly) {
            return (int) $this->dbcon->count($this->table, 'id', $where);
        }

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits))
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);

        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', $where), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen Artikeln zurück, welche automatisch freigeschalten werden sollen
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesPostponed($monthIndex = false)
    {
        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'postponed = 1 AND approval = 0 AND createtime <= ? AND deleted = 0 AND draft = 0' . $this->dbcon->orderBy(array('createtime DESC')), array(time())), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit Artikel-IDs zurück, welche automatisch freigeschalten werden sollen
     * @return array
     */
    public function getArticlesPostponedIDs()
    {
        $list = $this->dbcon->fetch($this->dbcon->select($this->table, 'id', 'postponed = 1 AND approval = 0 AND createtime <= ? AND deleted = 0 AND draft = 0' . $this->dbcon->orderBy(array('createtime DESC')), array(time())), true);

        $ids = [];
        foreach ($list as $item) {
            $ids[] = (int) $item->id;
        }

        return $ids;
    }

    /**
     * Gibt Liste mit allen gelöschten Artikeln zurück (Papierkorb)
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesDeleted($monthIndex = false)
    {
        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'deleted = 1' . $this->dbcon->orderBy(array('createtime DESC'))), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen gelöschten Artikeln zurück (Papierkorb)
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesDraft($monthIndex = false)
    {
        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'draft = 1 AND deleted = 0' . $this->dbcon->orderBy(array('createtime DESC'))), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste von Artikeln anhand einer Bedingung zurück
     * @param search $conditions
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesByCondition(search $conditions, $monthIndex = false)
    {
        $where = [];
        $valueParams = [];

        $this->assignSearchParams($conditions, $where, $valueParams);

        $combination = $conditions->combination !== null ? $conditions->combination : 'AND';

        $eventData = $this->events->trigger('article\getByCondition', [
            'conditions' => $conditions,
            'where' => $where,
            'values' => $valueParams
        ]);

        $conditions = $eventData['conditions'];
        $where = $eventData['where'];
        $valueParams = $eventData['values'];

        $where = implode(" {$combination} ", $where);

        $where2 = [];
        $where2[] = $this->dbcon->orderBy(
            $conditions->orderby !== null ? $conditions->orderby : [$this->config->articles_sort . ' ' . $this->config->articles_sort_order]
        );

        if ($conditions->limit !== null) {
            $where2[] = $this->dbcon->limitQuery($conditions->limit[0], $conditions->limit[1]);
        }

        $where .= ' ' . implode(' ', $where2);

        $list = $this->dbcon->fetch($this->dbcon->select($this->table, '*', $where, $valueParams), true);
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Verschiebt Artikel in Papierkorb
     * @param array $ids
     * @return bool
     */
    public function deleteArticles(array $ids)
    {
        if (!count($ids)) {
            return false;
        }

        /* @var $session \fpcm\model\system\session */
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $userId = $session->exists() ? $session->getUserId() : 0;

        $res = $this->dbcon->update(
                $this->table, ['deleted', 'pinned', 'changetime', 'changeuser'], [1, 0, time(), $userId], 'id IN (' . implode(', ', $ids) . ')'
        );

        if ($res) {
            $commentList = new \fpcm\model\comments\commentList();
            $commentList->deleteCommentsByArticle($ids);
        }

        $this->cache->cleanup();
        return $res;
    }

    /**
     * Stellt Artikel aus Papierkorb wieder her
     * @param array $ids
     * @return bool
     */
    public function restoreArticles(array $ids)
    {
        $this->cache->cleanup();

        /* @var $session \fpcm\model\system\session */
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $userId = $session->exists() ? $session->getUserId() : 0;

        return $this->dbcon->update(
            $this->table,
            ['deleted', 'changetime', 'changeuser'],
            [0, time(), $userId],
            'id IN (' . implode(', ', $ids) . ') AND deleted = 1'
        );
    }

    /**
     * Veröffentlicht Article, die freigeschlaten werden sollen
     * @param array $ids
     * @return bool
     */
    public function publishPostponedArticles(array $ids)
    {
        $this->cache->cleanup();
        return $this->dbcon->update($this->table, array('postponed'), array(0), 'id IN (' . implode(', ', $ids) . ') AND postponed = 1 AND approval = 0 AND deleted = 0 AND draft = 0');
    }

    /**
     * Leert Papierkorb
     * @param array $ids
     * @return bool
     */
    public function emptyTrash()
    {
        return $this->dbcon->delete($this->table, 'deleted = 1');
    }

    /**
     * Gibt Artikel-Anzahl für jeden Benutzer zurück
     * @param array $userIds
     * @return array
     */
    public function countArticlesByUsers(array $userIds = array())
    {
        $where = count($userIds) ? "createuser IN (" . implode(',', $userIds) . ")" : '1=1';
        $articleCounts = $this->dbcon->fetch($this->dbcon->select($this->table, 'createuser, count(id) AS count', "$where AND deleted = 0 GROUP BY createuser"), true);

        $res = [];
        if (!count($articleCounts))
            return $res;

        foreach ($articleCounts as $articleCount) {
            $res[$articleCount->createuser] = (int) $articleCount->count;
        }

        return $res;
    }

    /**
     * Zählt Artikel anhand von Bedingung
     * @param search $conditions
     * @return int
     */
    public function countArticlesByCondition(search $conditions)
    {
        $where = [];
        $valueParams = [];

        $this->assignSearchParams($conditions, $where, $valueParams);
        $combination = $conditions->combination !== null ? $conditions->combination : 'AND';

        $eventData = $this->events->trigger('article\getByConditionCount', [
            'where' => $where,
            'values' => $valueParams
        ]);

        return $this->dbcon->count(
            $this->table,
            '*',
            implode(" {$combination} ", $eventData['where']),
            $eventData['values']
        );
    }

    /**
     * Gibt Liste mit Artikel-IDs für übergebenen Benutzer zurück
     * @param int $userId
     * @return array
     */
    public function getArticleIDsByUser($userId)
    {
        $articles = $this->dbcon->fetch($this->dbcon->select($this->table, 'id', "createuser = ? AND deleted = 0", array($userId)), true);

        $res = [];
        if (!count($articles))
            return $res;

        foreach ($articles as $article) {
            $res[] = (int) $article->id;
        }

        return $res;
    }

    /**
     * Liefert minimalen und maximalen createtime-Timestamp
     * @param int $archived
     * @return array
     * @since FPCM 3.3.3
     */
    public function getMinMaxDate($archived = false)
    {
        $where = 'deleted = 0';
        $params = [];

        if ($archived !== false) {
            $where .= " AND archived = ?";
            $params[] = (int) $archived;
        }

        $result = $this->dbcon->select($this->table, 'MAX(createtime) AS maxdate, MIN(createtime) AS mindate', $where, $params);
        $data = $this->dbcon->fetch($result);

        return [
            'maxDate' => $data->maxdate === null ? time() : $data->maxdate,
            'minDate' => $data->mindate === null ? 0 : $data->mindate
        ];

    }

    /**
     * Verschiebt Artikel von einem Benutzer zu einem anderen
     * @param int $userIdFrom
     * @param int $userIdTo
     * @since FPCM 3.5.1
     * @return bool
     */
    public function moveArticlesToUser($userIdFrom, $userIdTo)
    {
        if (!$userIdFrom || !$userIdTo) {
            return false;
        }

        $return = $this->dbcon->update($this->table, ['createuser'], [$userIdTo, $userIdFrom], 'createuser = ?');
        $this->cache->cleanup();
        return $return;
    }

    /**
     * Löscht alle Artikel eines Benutzers
     * @param int $userId
     * @since FPCM 3.5.1
     * @return bool
     */
    public function deleteArticlesByUser($userId)
    {
        if (!$userId) {
            return false;
        }

        $res = $this->dbcon->update(
            $this->table,
            ['deleted', 'pinned'],
            [1, 0, $userId],
            'createuser = ?'
        );

        $this->cache->cleanup();

        return $res;
    }

    /**
     * Massenbearbeitung
     * @param array $articleIds
     * @param array $fields
     * @since FPCM 3.6
     */
    public function editArticlesByMass(array $articleIds, array $fields)
    {
        if (!count($articleIds)) {
            return false;
        }

        $result = $this->events->trigger('article\massEditBefore', [
            'fields' => $fields,
            'articleIds' => $articleIds
        ]);

        foreach ($result as $key => $val) {
            ${$key} = $val;
        }

        if (isset($fields['categories']) && is_array($fields['categories'])) {
            unset($fields['categories']);
        }

        if (isset($fields['createuser']) && $fields['createuser'] === -1) {
            unset($fields['createuser']);
        }

        if (isset($fields['comments']) && $fields['comments'] === -1) {
            unset($fields['comments']);
        }

        if (isset($fields['pinned']) && $fields['pinned'] === -1) {
            unset($fields['pinned']);
        }

        if (isset($fields['approval']) && $fields['approval'] === -1) {
            unset($fields['approval']);
        }

        if (isset($fields['draft']) && $fields['draft'] === -1) {
            unset($fields['draft']);
        }

        if (isset($fields['archived']) && $fields['archived'] === -1) {
            unset($fields['archived']);
        }

        if (!count($fields)) {
            return false;
        }

        $where = 'id IN (' . implode(',', $articleIds) . ')';
        $result = $this->dbcon->update($this->table, array_keys($fields), array_values($fields), $where);

        $this->cache->cleanup();

        $result = $this->events->trigger('article\massEditAfter', [
            'result' => $result,
            'fields' => $fields,
            'articleIds' => $articleIds
        ]);

        return $result['result'];
    }

    /**
     * Erzeugt Listen-Result-Array
     * @param array $list
     * @param bool $monthIndex
     * @return array
     */
    private function createListResult($list, $monthIndex)
    {
        $res = [];
        foreach ($list as $item) {
            $article = new article();
            if (!$article->createFromDbObject($item)) {
                continue;
            }

            $this->checkEditPermissions($article);

            if ($monthIndex) {
                $index = mktime(0, 0, 0, date('m', $article->getCreatetime()), 1, date('Y', $article->getCreatetime()));
                $res[$index][$article->getId()] = $article;
            } else {
                $res[$article->getId()] = $article;
            }
        }

        return $res;
    }

    /**
     * 
     * @param \fpcm\model\articles\search $conditions
     * @param array $where
     * @param array $valueParams
     * @return boolean
     */
    private function assignSearchParams(search $conditions, array &$where, array &$valueParams)
    {
        if ($conditions->ids !== null && is_array($conditions->ids)) {
            $where[] = "id IN (" . implode(', ', $conditions->ids) . ")";
        }

        if ($conditions->title !== null) {
            $where[] = "title " . $this->dbcon->dbLike() . " ?";
            $valueParams[] = "%{$conditions->title}%";
        }

        if ($conditions->content !== null) {
            $where[] = "content " . $this->dbcon->dbLike() . " ?";
            $valueParams[] = "%{$conditions->content}%";
        }

        if ($conditions->user !== null) {
            $where[] = "createuser = ?";
            $valueParams[] = $conditions->user;
        }

        if ($conditions->category !== null) {
            $catId = (int) $conditions->category;
            $where[] = "(categories " . $this->dbcon->dbLike() . " ? OR categories " . $this->dbcon->dbLike() . " ? OR categories " . $this->dbcon->dbLike() . " ? OR categories " . $this->dbcon->dbLike() . " ?)";
            $valueParams[] = "[{$catId}]";
            $valueParams[] = "%,{$catId},%";
            $valueParams[] = "[{$catId},%";
            $valueParams[] = "%,{$catId}]";
        }

        if ($conditions->datefrom !== null) {
            $where[] = "createtime >= ?";
            $valueParams[] = $conditions->datefrom;
        }

        if ($conditions->dateto !== null) {
            $where[] = "createtime <= ?";
            $valueParams[] = $conditions->dateto;
        }

        if ($conditions->postponed !== null) {
            $where[] = "postponed = ?";
            $valueParams[] = $conditions->postponed;
        }

        if ($conditions->archived !== null) {           
            $where[] = "archived = ?";
            $valueParams[] = $conditions->archived;
        }

        if ($conditions->pinned !== null) {
            $where[] = "pinned = ?";
            $valueParams[] = $conditions->pinned;
        }

        if ($conditions->comments !== null) {
            $where[] = "comments = ?";
            $valueParams[] = $conditions->comments;
        }

        if ($conditions->deleted !== null) {
            $where[] = "deleted = ?";
            $valueParams[] = $conditions->deleted;
        } else {
            $where[] = "deleted = 0";
        }

        if ($conditions->draft !== null && $conditions->draft > -1) {
            $where[] = "draft = ?";
            $valueParams[] = $conditions->draft;
        } if ($conditions->draft === null) {
            $where[] = "draft = 0";
        }

        if ($conditions->approval !== null && $conditions->approval > -1) {
            $where[] = "approval = ?";
            $valueParams[] = $conditions->approval;
        } elseif ($conditions->approval === null) {
            $where[] = "approval = 0";
        }

        if ($conditions->active !== null) {
            $where = [($conditions->active === -1 ? 'archived = 0' : 'archived = 0 AND draft = 0')];
        }

        return true;
    }

}
