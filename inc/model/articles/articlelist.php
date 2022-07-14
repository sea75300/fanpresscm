<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * FanPress CM Article List Model
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\articles
 */
class articlelist extends \fpcm\model\abstracts\tablelist {

    use permissions;

    /**
     * Permission Object
     * @var \fpcm\model\permissions\permissions
     * @since 3.3
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
            $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');
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
    public function getArticlesAll($monthIndex = false, array $limits = [], $countOnly = false)
    {
        $where = 'draft = 0 AND deleted = 0';

        if ($countOnly) {
            return (int) $this->dbcon->count($this->table, 'id', $where);
        }

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits)) {
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);
        }

        $list = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true)->setWhere($where));
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen aktiven Artikeln zurück
     * @param bool $monthIndex Liste mit Monatsindex zurückgeben
     * @param array $limits Anzahl der zurückgegebenen Artikel einschränken array(Start,Anzahl)
     * @param bool $countOnly Verfügbare Artikel nur zählen
     * @return array
     */
    public function getArticlesActive($monthIndex = false, array $limits = [], $countOnly = false)
    {
        $where = 'archived = 0 AND deleted = 0';

        if ($countOnly) {
            return (int) $this->dbcon->count($this->table, 'id', $where);
        }

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits)) {
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);
        }

        $list = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true)->setWhere($where));
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
    public function getArticlesArchived($monthIndex = false, array $limits = [], $countOnly = false, $dateLimit = false)
    {
        $where = 'archived = 1 AND deleted = 0';
        if ($dateLimit && $this->config->articles_archive_datelimit) {
            $where .= ' createtime >= ' . $this->config->articles_archive_datelimit;
        }

        if ($countOnly) {
            return (int) $this->dbcon->count($this->table, 'id', $where);
        }

        $where .= $this->dbcon->orderBy(array('createtime DESC'));

        if (count($limits)) {
            $where .= $this->dbcon->limitQuery($limits[0], $limits[1]);
        }

        $list = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true)->setWhere($where));
        return $this->createListResult($list, $monthIndex);
    }

    /**
     * Gibt Liste mit allen Artikeln zurück, welche automatisch freigeschalten werden sollen
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesPostponed($monthIndex = false)
    {
        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setWhere('postponed = 1 AND approval = 0 AND createtime <= ? AND deleted = 0 AND draft = 0' . $this->dbcon->orderBy(['createtime DESC']))
                ->setParams([time()]);

        return $this->createListResult($this->dbcon->selectFetch($obj), $monthIndex);
    }

    /**
     * Gibt Liste mit Artikel-IDs zurück, welche automatisch freigeschalten werden sollen
     * @return array
     */
    public function getArticlesPostponedIDs()
    {
        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setItem('id')
                ->setFetchAll(true)
                ->setWhere('postponed = 1 AND approval = 0 AND createtime <= ? AND deleted = 0 AND draft = 0' . $this->dbcon->orderBy(['createtime DESC']))
                ->setParams([time()]);

        $list = $this->dbcon->selectFetch($obj);

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
        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setWhere('deleted = 1' . $this->dbcon->orderBy(['createtime DESC']));

        return $this->createListResult($this->dbcon->selectFetch($obj), $monthIndex);
    }

    /**
     * Gibt Liste mit allen gelöschten Artikeln zurück (Papierkorb)
     * @param bool $monthIndex
     * @return array
     */
    public function getArticlesDraft($monthIndex = false)
    {
        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setWhere('draft = 1 AND deleted = 0' . $this->dbcon->orderBy(['createtime DESC']));

        return $this->createListResult($this->dbcon->selectFetch($obj), $monthIndex);
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

        if ( $conditions->isMultiple() ) {
            $this->assignMultipleSearchParams($conditions, $where, $valueParams);
            $combination = '';
        }
        else {
            $this->assignSearchParams($conditions, $where, $valueParams);
            $combination = $conditions->combination !== null ? $conditions->combination : 'AND';
        }

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

        $item   = $conditions->metaOnly
                ? 'id, title, categories, createtime, createuser, changetime, changeuser, draft, archived, pinned, postponed, deleted, comments, approval, imagepath, sources, inedit'
                : '*';

        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setItem($item)
                ->setFetchAll(true)
                ->setWhere($where)
                ->setParams($valueParams);

        $result = $this->dbcon->selectFetch($obj);
        if ($this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            return \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX;
        }

        return $this->createListResult($result, $monthIndex);
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

        $this->cache->cleanup();

        /* @var $session \fpcm\model\system\session */
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $userId = $session->exists() ? $session->getUserId() : 0;

        $res = $this->dbcon->update(
            $this->table,
            ['deleted', 'pinned', 'changetime', 'changeuser'],
            array_merge([1, 0, time(), $userId], $ids),
            $this->dbcon->inQuery('id', $ids)
        );

        if ($res) {
            $commentList = new \fpcm\model\comments\commentList();
            $commentList->deleteCommentsByArticle($ids);
        }

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
            array_merge([0, time(), $userId], $ids),
            $this->dbcon->inQuery('id', $ids) . ' AND deleted = 1'
        );
    }

    /**
     * Veröffentlicht Article, die freigeschlaten werden sollen
     * @param array $ids
     * @return bool
     */
    public function publishPostponedArticles(array $ids)
    {
        if (!count($ids)) {
            return true;
        }
        
        $return = $this->dbcon->update(
            $this->table,
            ['postponed'],
            array_merge([0], $ids),
            $this->dbcon->inQuery('id', $ids) . ' AND postponed = 1 AND approval = 0 AND deleted = 0 AND draft = 0'
        );
        
        if (!$return) {
            return false;
        }
        
        $this->cache->cleanup();
        return true;
    }

    /**
     * Empty trash
     * @return bool
     */
    public function emptyTrash() : bool
    {
        $this->cache->cleanup();
        return $this->dbcon->delete($this->table, 'deleted = ?', [1]);
    }

    /**
     * Empty trash by date
     * @return bool
     */
    public function emptyTrashByDate() : bool
    {
        $this->cache->cleanup();
        return $this->dbcon->delete($this->table, 'deleted = ? AND changetime <= ?', [
            1, time() - $this->config->system_trash_cleanup * FPCM_DATE_SECONDS
        ]);
    }

    /**
     * Gibt Artikel-Anzahl für jeden Benutzer zurück
     * @param array $userIds
     * @return array
     */
    public function countArticlesByUsers(array $userIds = [])
    {
        $where = count($userIds) ? "createuser IN (?)" : '1=1';
        $params = count($userIds) ? [implode(',', $userIds)] : [];

        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setWhere("{$where} AND deleted = 0 GROUP BY createuser")
                ->setParams($params)
                ->setItem('createuser, count(id) AS count');

        $articleCounts = $this->dbcon->selectFetch($obj);

        $res = [];
        if (!count($articleCounts)) {
            return $res;
        }

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
        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setItem('id')
                ->setWhere('createuser = ? AND deleted = 0')
                ->setParams([$userId]);

        $articles = $this->dbcon->selectFetch($obj);

        $res = [];
        if (!count($articles)) {
            return $res;
        }

        foreach ($articles as $article) {
            $res[] = (int) $article->id;
        }

        return $res;
    }

    /**
     * Liefert minimalen und maximalen createtime-Timestamp
     * @param int $archived
     * @return array
     * @since 3.3.3
     */
    public function getMinMaxDate($archived = false)
    {
        $where = 'deleted = 0';
        $params = [];

        if ($archived !== false) {
            $where .= " AND archived = ?";
            $params[] = (int) $archived;
        }

        $obj = (new \fpcm\model\dbal\selectParams($this->table))
                ->setItem('MAX(createtime) AS maxdate, MIN(createtime) AS mindate')
                ->setWhere($where)
                ->setParams($params);

        $data = $this->dbcon->selectFetch($obj);

        return [
            'maxDate' => $data->maxdate === null ? time() : $data->maxdate,
            'minDate' => $data->mindate === null ? 0 : $data->mindate
        ];

    }

    /**
     * Verschiebt Artikel von einem Benutzer zu einem anderen
     * @param int $userIdFrom
     * @param int $userIdTo
     * @since 3.5.1
     * @return bool
     */
    public function moveArticlesToUser($userIdFrom, $userIdTo)
    {
        if (!$userIdFrom || !$userIdTo) {
            return false;
        }

        $return = $this->dbcon->update(
            $this->table, [
                'createuser', 'changeuser', 'changetime'
            ], [
                $userIdTo,
                \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId(),
                time(),
                $userIdFrom
            ],
            'createuser = ?'
        );

        $this->cache->cleanup();
        return $return;
    }

    /**
     * Löscht alle Artikel eines Benutzers
     * @param int $userId
     * @since 3.5.1
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
     * @since 3.6
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
     * Fetch counts of comments amnd shares for articles
     * @param array $ids
     * @return array
     * @since 4.5
     */
    public function getRelatedItemsCount(array $ids = []) : array
    {
        if (isset($this->data[__METHOD__])) {
            return $this->data[__METHOD__];
        }
        
        $obj = (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::viewArticleCounts))
                ->setItem('*')
                ->setFetchAll(true);
        
        if (count($ids)) {
            $obj->setWhere($this->dbcon->inQuery('article_id', $ids));
            $obj->setParams($ids);
        }
        
        $data = $this->dbcon->selectFetch($obj);
        if (!is_array($data) || !count($data)) {
            return [];
        }
        
        $return = [];
        array_walk($data, function ($value) use (&$return) {
           
            $return[$value->article_id] = new relatedCountItem(
                (int) $value->article_id,
                (int) $value->ccount,
                (int) $value->cprivunapp,
                (int) $value->shares
            );

        });

        return $this->data[__METHOD__] = $return;
    }

    /**
     * Erzeugt Listen-Result-Array
     * @param array $list
     * @param bool $monthIndex
     * @return array
     */
    private function createListResult($list, $monthIndex)
    {
        if (!is_array($list)) {
            return [];
        }

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
                continue;
            }

            $res[$article->getId()] = $article;
        }

        return $res;
    }

    /**
     * Assigns search params from search object to where condition
     * @param \fpcm\model\articles\search $conditions
     * @param array $where
     * @param array $valueParams
     * @return bool
     */
    private function assignSearchParams(search $conditions, array &$where, array &$valueParams)
    {
        if ($conditions->ids !== null && is_array($conditions->ids)) {
            $where[] = $this->dbcon->inQuery('id', $conditions->ids);
            $valueParams = array_merge($valueParams, $conditions->ids);
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

        if ($conditions->postponed === article::POSTPONED_SEARCH_FE) {
            $where[] = "(postponed = ? OR (postponed = ? AND createtime <= ?))";
            $valueParams[] = article::POSTPONED_INACTIVE;
            $valueParams[] = article::POSTPONED_ACTIVE;
            $valueParams[] = time();
        }
        elseif ($conditions->postponed !== null) {
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

        if ($conditions->draft !== null) {
            $where[] = "draft = ?";
            $valueParams[] = $conditions->draft > -1 ? $conditions->draft : 0;
        }

        if ($conditions->approval !== null) {
            $where[] = "approval = ?";
            $valueParams[] = $conditions->approval > -1 ? $conditions->approval : 0;
        }

        $where[] = "deleted = ?";
        $valueParams[] = $conditions->deleted !== null ? $conditions->deleted : 0;

        return true;
    }

    /**
     * Assigns search params object to value arrays
     * @param \fpcm\model\comments\search $conditions
     * @param array $where
     * @param array $valueParams
     * @since 4.3
     */
    private function assignMultipleSearchParams(search $conditions, array &$where, array &$valueParams) : bool
    {
        if ($conditions->title !== null && $conditions->content !== null && $conditions->combination !== null) {
            $where[] = "(title " . $this->dbcon->dbLike() . " :title {$conditions->combination} content " . $this->dbcon->dbLike() . " :content)";
            $valueParams[':title'] = "%{$conditions->title}%";
            $valueParams[':content'] = "%{$conditions->content}%";
        }
        elseif ($conditions->title !== null) {
            $where[] = "title " . $this->dbcon->dbLike() . " :title";
            $valueParams[':title'] = "%{$conditions->title}%";
        }
        elseif ($conditions->content !== null) {
            $where[] = "content " . $this->dbcon->dbLike() . " :content";
            $valueParams[':content'] = "%{$conditions->content}%";
        }

        if ($conditions->datefrom !== null) {
            $where[] = $conditions->getCondition('datefrom', 'createtime >= :createtime');
            $valueParams[':createtime'] = $conditions->datefrom;
        }

        if ($conditions->dateto !== null) {
            $where[] = $conditions->getCondition('dateto', 'createtime <= :createtime');
            $valueParams[':createtime'] = $conditions->dateto;
        }

        if ($conditions->user !== null) {
            $where[] = $conditions->getCondition('userid', 'createuser = :createuser');
            $valueParams[':createuser'] = $conditions->user;
        }

        if ($conditions->category !== null) {
            $catId = (int) $conditions->category;
            $where[] = $conditions->getCondition('categoryid', "(categories " . $this->dbcon->dbLike() . " :categories1 OR categories " . $this->dbcon->dbLike() . " :categories2 OR categories " . $this->dbcon->dbLike() . " :categories3 OR categories " . $this->dbcon->dbLike() . " :categories4)");
            $valueParams[':categories1'] = "[{$catId}]";
            $valueParams[':categories2'] = "%,{$catId},%";
            $valueParams[':categories3'] = "[{$catId},%";
            $valueParams[':categories4'] = "%,{$catId}]";
        }

        if ($conditions->pinned !== null) {
            $where[] = $conditions->getCondition('pinned', 'pinned = :pinned');
            $valueParams[':pinned'] = $conditions->pinned;
        }

        if ($conditions->postponed !== null) {
            $where[] = $conditions->getCondition('postponed', 'postponed = :postponed');
            $valueParams[':postponed'] = $conditions->postponed;
        }

        if ($conditions->comments !== null) {
            $where[] = $conditions->getCondition('comments', 'comments = :comments');
            $valueParams[':comments'] = $conditions->comments;
        }

        if ($conditions->approval !== null) {
            $where[] = $conditions->getCondition('approval', 'approval = :approval');
            $valueParams[':approval'] = $conditions->approval > -1 ? $conditions->approval : 0;
        }

        if ($conditions->draft !== null) {
            $where[] = $conditions->getCondition('draft', 'draft = :draft');
            $valueParams[':draft'] = $conditions->draft > -1 ? $conditions->draft : 0;
        }

        if ($conditions->archived !== null) {           
            $where[] = $conditions->getCondition('archived', 'archived = :archived');
            $valueParams[':archived'] = $conditions->archived;
        }

        $where[] = $conditions->getCondition('deleted', "deleted = :deleted");
        $valueParams[':deleted'] = $conditions->deleted !== null ? $conditions->deleted : 0;

        return true;
    }

}
