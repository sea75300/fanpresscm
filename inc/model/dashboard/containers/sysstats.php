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
                    '%s <span class="text-success-emphasis">(%s)</span>',
                    $this->dbStats['articles_all'],
                    $this->dbStats['articles_active']
                ),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('archive'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_ARTICLES_ARCHIVE',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_archived'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('pen-ruler'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_ARTICLES_DRAFT',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_draft'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
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
                class: $uac . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_unapproved'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $uac,
                size: 3
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
                class: $ppc . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['articles_postponed'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $ppc,
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('comments'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_COMMENTS_ALL',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_all'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
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
                class: $uacc,
                size: 3
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
                class: $prcc . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_private'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $prcc,
                size: 3
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
                class: $scc . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['comments_spam'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $scc,
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('users'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_USERS',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: sprintf(
                    '%s <span class="text-success-emphasis">(%s)</span>',
                    $this->dbStats['users_all'],
                    $this->dbStats['users_active']
                ),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('globe'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto',
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_IP_LOCKS',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['ip_locks'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
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
                class: $dicc . ' text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $dic,
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: $dicc,
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('bell'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_CACHE_REMINDERS',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['reminders'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: 'text-truncate',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('folder-open'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_UPLOAD_COUNT',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: $this->dbStats['upload_count'],
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('weight'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_UPLOAD_SIZE',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: \fpcm\classes\tools::calcSize($this->dbStats['upload_size'] ?? 0),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: 'text-truncate',
                size: 3
            )
        ];

        $rows[] = [
            'icon' => new \fpcm\model\dashboard\components\dataviewItem(
                value: new \fpcm\view\helper\icon('hdd'),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                size: 'auto'
            ),
            'label' => new \fpcm\model\dashboard\components\dataviewItem(
                value: 'SYSTEM_STATS_CACHE_SIZE',
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                class: 'text-truncate'
            ),
            'value' => new \fpcm\model\dashboard\components\dataviewItem(
                value: \fpcm\classes\tools::calcSize($this->cache->getSize()),
                type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                align: 'end',
                class: 'text-truncate',
                size: 3
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
     * Get width
     * @return int
     */
    public function getWidth() {
        return 6;
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
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableIpAdresses))->setItem("'ip_locks' AS descr, {$countStr}"),
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableReminders))->setItem("'reminders' AS descr, {$countStr}"),

        ], \PDO::FETCH_KEY_PAIR);

        return is_array($this->dbStats) && count($this->dbStats);
    }

}
