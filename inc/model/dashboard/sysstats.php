<?php

/**
 * System stats Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * System stats dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
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
        $this->runCheck();
        return PHP_EOL.'<div class="row">'.implode('</div>'.PHP_EOL.'<div class="row">'.PHP_EOL, $this->tableContent).'</div>'.PHP_EOL;
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
     * Check ausführen
     */
    protected function runCheck()
    {

        if ($this->cache->isExpired($this->cacheName)) {
            $this->getArticleStats();
            $this->getCommentStats();
            $this->getUserStats();
            $this->getFileStats();

            $this->cache->write($this->cacheName, $this->tableContent, $this->config->system_cache_timeout);
        } else {
            $this->tableContent = $this->cache->read($this->cacheName);
        }

        $this->getCacheStats();
    }

    /**
     * Artikel-Statistiken berechnen
     */
    protected function getArticleStats()
    {
        $articleList = new \fpcm\model\articles\articlelist();

        $sObj = new \fpcm\model\articles\search();

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('book')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ALL'),
            $articleList->countArticlesByCondition($sObj)
        );

        $sObj = new \fpcm\model\articles\search();
        $sObj->approval = -1;
        $sObj->draft = 0;
        $sObj->archived = 0;
        $sObj->deleted = 0;

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('newspaper', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ACTIVE'),
            $articleList->countArticlesByCondition($sObj)
        );

        $sObj = new \fpcm\model\articles\search();
        $sObj->archived = 1;
        $sObj->approval = -1;

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('archive')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_ARCHIVE'),
            $articleList->countArticlesByCondition($sObj)
        );

        $sObj = new \fpcm\model\articles\search();
        $sObj->draft = 1;
        $sObj->approval = -1;

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('file-alt', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_DRAFT'),
            $articleList->countArticlesByCondition($sObj)
        );

        $sObj = new \fpcm\model\articles\search();
        $sObj->deleted = 1;
        $this->deletedCount += $articleList->countArticlesByCondition($sObj);

        $sObj = new \fpcm\model\articles\search();
        $sObj->approval = 1;
        $count = $articleList->countArticlesByCondition($sObj);

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('thumbs-up', 'far')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_APPROVAL'),
            $count,
            ($count > 0 ? 'fpcm-ui-important-text' : '')
        );

        $sObj = new \fpcm\model\articles\search();
        $sObj->postponed = 1;
        $sObj->draft = 0;
        $sObj->approval = 0;
        $count = $articleList->countArticlesByCondition($sObj);

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('calendar-plus')).' '.$this->language->translate('SYSTEM_STATS_ARTICLES_POSTPONED'),
            $count,
            ($count > 0 ? 'fpcm-ui-important-text' : '')
        );
    }

    /**
     * Kommentar-Statistiken berechnen
     */
    protected function getCommentStats()
    {
        $commentList = new \fpcm\model\comments\commentList();
        $sObj = new \fpcm\model\comments\search();

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('comments')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_ALL'),
            $commentList->countCommentsByCondition($sObj)
        );

        $sObj = new \fpcm\model\comments\search();
        $sObj->unapproved = true;
        $count = $commentList->countCommentsByCondition($sObj);

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('check-circle', 'far')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_UNAPPR'),
            $count,
            ($count > 0 ? 'fpcm-ui-important-text' : '')
        );          

        $sObj = new \fpcm\model\comments\search();
        $sObj->private = true;
        $count = $commentList->countCommentsByCondition($sObj);

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('eye-slash')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_PRIVATE'),
            $count,
            ($count > 0 ? 'fpcm-ui-important-text' : '')
        );          

        $sObj = new \fpcm\model\comments\search();
        $sObj->spam = true;
        $count = $commentList->countCommentsByCondition($sObj);

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('flag')).' '.$this->language->translate('SYSTEM_STATS_COMMENTS_SPAM'),
            $count,
            ($count > 0 ? 'fpcm-ui-important-text' : '')
        );        
        

        $sObj = new \fpcm\model\comments\search();
        $sObj->deleted = true;
        $this->deletedCount += $commentList->countCommentsByCondition($sObj);
    }

    /**
     * Benutzer-Statistiken berechnen
     */
    protected function getUserStats()
    {
        $userCountAll = $this->dbcon->count(\fpcm\classes\database::tableAuthors);
        $userCountAct = $this->dbcon->count(\fpcm\classes\database::tableAuthors, '*', 'disabled = 0');

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('users')).' '.$this->language->translate('SYSTEM_STATS_USERS'),
            "{$userCountAll} ({$userCountAct})"
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('file-alt', 'far')).' '.$this->language->translate('SYSTEM_STATS_CATEGORIES'),
            $this->dbcon->count(\fpcm\classes\database::tableCategories)
        );
    }

    /**
     * Datei-Statistiken berechnen
     */
    protected function getFileStats()
    {
        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('copy', 'far')).' '.$this->language->translate('SYSTEM_STATS_UPLOAD_COUNT'),
            $this->dbcon->count(\fpcm\classes\database::tableFiles, '*')
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('calculator')).' '.$this->language->translate('SYSTEM_STATS_UPLOAD_SIZE'),
            \fpcm\classes\tools::calcSize((new \fpcm\model\files\imagelist)->getUploadFolderSize())
        );

    }

    /**
     * Cache-Statistiken berechnen
     */
    protected function getCacheStats()
    {
        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('hdd')).' '.$this->language->translate('SYSTEM_STATS_CACHE_SIZE'),
            \fpcm\classes\tools::calcSize($this->cache->getSize())
        );

        $this->tableContent[] = $this->get2ColRow(
            (new \fpcm\view\helper\icon('flag')).' '.$this->language->translate('SYSTEM_STATS_TRASHCOUNT'),
            $this->deletedCount
        );

    }

}
