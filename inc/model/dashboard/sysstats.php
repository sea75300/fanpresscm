<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * System stats dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class sysstats extends \fpcm\model\abstracts\dashcontainer {

    use \fpcm\model\traits\dashContainerCols;
    
    /**
     * Container table content
     * @var array
     */
    protected $tableContent = [];

    /**
     * Coutn of deleted items
     * @var int
     */
    protected $deletedCount = 0;

    /**
     * Databased stats values
     * @var array
     * @since 4.5
     */
    protected $dbStats = [];

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'sysstats';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        $this->getCacheName();
        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('book')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ALL'),
            $this->dbStats['articles_all']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('newspaper', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ACTIVE'),
            $this->dbStats['articles_active']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('archive')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ARCHIVE'),
            $this->dbStats['articles_archived']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('file-alt', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_DRAFT'),
            $this->dbStats['articles_draft']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('thumbs-up', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_APPROVAL'),
            $this->dbStats['articles_unapproved'],
            ($this->dbStats['articles_unapproved'] > 0 ? 'color-red' : '')
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('calendar-plus')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_POSTPONED'),
            $this->dbStats['articles_postponed'],
            ($this->dbStats['articles_postponed'] > 0 ? 'color-red' : '')
        );
        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('comments')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_ALL'),
            $this->dbStats['comments_all']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('check-circle', 'far')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_UNAPPR'),
            $this->dbStats['comments_unapproved'],
            ($this->dbStats['comments_unapproved'] > 0 ? 'color-red' : '')
        );          

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('eye-slash')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_PRIVATE'),
            $this->dbStats['comments_private'],
            ($this->dbStats['comments_private'] > 0 ? 'color-red' : '')
        );          

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('flag')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_SPAM'),
            $this->dbStats['comments_spam'],
            ($this->dbStats['comments_spam'] > 0 ? 'color-red' : '')
        );        

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('users')).' '.$this->language->translate('SYSTEM_STATS_USERS'),
            "{$this->dbStats['users_all']} ({$this->dbStats['users_active']})"
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('file-alt', 'far')).' '.$this->language->translate('SYSTEM_STATS_CATEGORIES'),
            $this->dbStats['categories']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('copy', 'far')).' '.$this->language->translate('SYSTEM_STATS_UPLOAD_COUNT'),
            $this->dbStats['upload_count']
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('calculator')).' '.$this->language->translate('SYSTEM_STATS_UPLOAD_SIZE'),
            \fpcm\classes\tools::calcSize($this->dbStats['upload_size'] ?? 0)
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('hdd')).' '.$this->language->translate('SYSTEM_STATS_CACHE_SIZE'),
            \fpcm\classes\tools::calcSize($this->cache->getSize())
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('flag')).' '.$this->language->translate('SYSTEM_STATS_TRASHCOUNT'),
            $this->dbStats['articles_deleted'] + $this->dbStats['comments_deleted']
        );

        $return = PHP_EOL.'<div class="row">'.implode('</div>'.PHP_EOL.'<div class="row">'.PHP_EOL, $this->tableContent).'</div>'.PHP_EOL;
        $this->cache->write($this->cacheName, $return);

        return $return;
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'SYSTEM_STATS';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 6;
    }

    /**
     * Returns container height
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_SMALL_MEDIUM;
    }

    /**
     * Fetch stats from database
     * @return bool
     * @since 4.5
     */
    protected function initObjects() : bool
    {
        $countStr = 'count(id) as value';

        $this->dbStats = $this->dbcon->unionSelectFetch([

            // Articles
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_all' AS descr, {$countStr}")->setWhere('deleted = :deleted')->setParams([':deleted' => 0]),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_active' AS descr, {$countStr}")->setWhere('deleted = 0 AND archived = 0 AND draft = 0 AND approval = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_archived' AS descr, {$countStr}")->setWhere('deleted = 0 AND archived = 1 AND approval = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_draft' AS descr, {$countStr}")->setWhere('deleted = 0 AND draft = 1 AND approval = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_unapproved' AS descr, {$countStr}")->setWhere('deleted = 0 AND approval = 1'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_postponed' AS descr, {$countStr}")->setWhere('deleted = 0 AND postponed = 1 AND draft = 0 AND approval = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles))->setItem("'articles_deleted' AS descr, {$countStr}")->setWhere('deleted = 1'),

            // Comments
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableComments))->setItem("'comments_all' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableComments))->setItem("'comments_unapproved' AS descr, {$countStr}")->setWhere('approved = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableComments))->setItem("'comments_private' AS descr, {$countStr}")->setWhere('private = 1'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableComments))->setItem("'comments_spam' AS descr, {$countStr}")->setWhere('spammer = 1'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableComments))->setItem("'comments_deleted' AS descr, {$countStr}")->setWhere('deleted = 1'),

            // Other stats
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableAuthors))->setItem("'users_all' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableAuthors))->setItem("'users_active' AS descr, {$countStr}")->setWhere('disabled = 0'),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableCategories))->setItem("'categories' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableFiles))->setItem("'upload_count' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableFiles))->setItem("'upload_size' AS descr, SUM(filesize)"),
        ], \PDO::FETCH_KEY_PAIR);

        return is_array($this->dbStats) && count($this->dbStats);
    }

}
