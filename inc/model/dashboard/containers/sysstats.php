<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * System stats dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class sysstats extends \fpcm\model\dashboard\types\dataview {

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
     * Returns cols
     * @return array
     */
    public function getCols(): array
    {
        return [
            'icon',
            'label',
            'value'
        ];
    }

    /**
     * Returns rows
     * @return array
     */
    public function getRows(): array
    {
        $rows = [];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('book'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_ARTICLES_ALL',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: sprintf(
                    "%s %s",
                    $this->dbStats['articles_all'],
                    (new \fpcm\view\helper\badge('acc'))
                        ->setValue($this->dbStats['articles_active'])
                        ->setText('')
                        ->setClass('text-bg-success')
                ),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('archive'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_ARTICLES_ARCHIVE',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_archived'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('pen-ruler'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_ARTICLES_DRAFT',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_draft'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $uac = $this->dbStats['articles_unapproved'] ? 'list-group-item-info' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('thumbs-up'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $uac
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_ARTICLES_APPROVAL',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $uac
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_unapproved'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $uac
            )
        ];

        $ppc = $this->dbStats['articles_postponed'] ? 'list-group-item-secondary' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('calendar'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $ppc
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_ARTICLES_POSTPONED',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $ppc
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_postponed'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $ppc
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('comments'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_COMMENTS_ALL',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_all'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $uacc = $this->dbStats['comments_unapproved'] ? 'list-group-item-info' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('check-circle', 'far'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $uacc
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_COMMENTS_UNAPPR',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $uacc . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_unapproved'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $uacc
            )
        ];

        $prcc = $this->dbStats['comments_private'] ? 'list-group-item-secondary' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('eye-slash'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $prcc
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_COMMENTS_PRIVATE',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $prcc
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_private'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $prcc
            )
        ];

        $scc = $this->dbStats['comments_spam'] ? 'list-group-item-info' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('flag'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $scc
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_COMMENTS_SPAM',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $scc
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_spam'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $scc
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('users'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_USERS',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: sprintf(
                    "%s %s",
                    $this->dbStats['users_all'],
                    (new \fpcm\view\helper\badge('acc'))
                        ->setValue($this->dbStats['users_active'])
                        ->setText('')
                        ->setClass('text-bg-success')
                ),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $dic = $this->dbStats['articles_deleted'] + $this->dbStats['comments_deleted'];
        $dicc = $dic ? 'list-group-item-warning' : '';

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('trash'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
                class: $dicc
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_TRASHCOUNT',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: $dicc
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $dic,
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $dicc
            )
        ];



        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('folder-open'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_UPLOAD_COUNT',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['upload_count'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('weight'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_UPLOAD_SIZE',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: \fpcm\classes\tools::calcSize($this->dbStats['upload_size'] ?? 0),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('hdd'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                'SYSTEM_STATS_CACHE_SIZE',
                \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: \fpcm\classes\tools::calcSize($this->cache->getSize()),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end'
            )
        ];

        return $rows;
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
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableFiles))->setItem("'upload_count' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableFiles))->setItem("'upload_size' AS descr, SUM(filesize)"),
        ], \PDO::FETCH_KEY_PAIR);

        return is_array($this->dbStats) && count($this->dbStats);
    }

}
