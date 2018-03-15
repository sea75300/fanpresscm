<?php

/**
 * System stats Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
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

    /**
     * Container table content
     * @var array
     */
    protected $tableContent = [];

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'sysstats';
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        $this->getCacheName();
        $this->runCheck();
        return PHP_EOL.'<div class="row fpcm-ui-font-small fpcm-ui-padding-md-tb">'.implode('</div>'.PHP_EOL.'<div class="row fpcm-ui-padding-md-tb fpcm-ui-font-small">'.PHP_EOL, $this->tableContent).'</div>'.PHP_EOL;
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'SYSTEM_stats';
    }

    /**
     * 
     * @return int
     */
    public function getPosition()
    {
        return 6;
    }

    /**
     * 
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_MEDIUM;
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
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-book fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_ALL') . ':</strong></div><div class="col-3 fpcm-ui-padding-none-lr fpcm-ui-center">' . $articleList->countArticlesByCondition(new \fpcm\model\articles\search()) . '</div>';

        $sObj = new \fpcm\model\articles\search();
        $sObj->active = 1;
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-newspaper-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_ACTIVE') . ':</strong></div><div class="col-3 fpcm-ui-padding-none-lr fpcm-ui-center">' . $articleList->countArticlesByCondition($sObj) . '</div>';

        $sObj = new \fpcm\model\articles\search();
        $sObj->archived = 1;
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-archive fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_ARCHIVE') . ':</strong></div><div class="col-3 fpcm-ui-padding-none-lr fpcm-ui-center">' . $articleList->countArticlesByCondition($sObj) . '</div>';

        $sObj = new \fpcm\model\articles\search();
        $sObj->drafts = 1;
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-file-text-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_DRAFT') . ':</strong></div><div class="col-3 fpcm-ui-padding-none-lr fpcm-ui-center">' . $articleList->countArticlesByCondition($sObj) . '</div>';

        $sObj = new \fpcm\model\articles\search();
        $sObj->deleted = 1;
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-trash-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_TRASH') . ':</strong></div><div class="col-3 fpcm-ui-padding-none-lr fpcm-ui-center">' . $articleList->countArticlesByCondition($sObj) . '</div>';

        $sObj = new \fpcm\model\articles\search();
        $sObj->approval = 1;
        $count = $articleList->countArticlesByCondition($sObj);
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr ' . ($count > 0 ? 'fpcm-ui-important-text' : '') . '"><span class="fa fa-thumbs-o-up fa-fw"></span><strong>' . $this->language->translate('SYSTEM_STATS_ARTICLES_APPROVAL') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $count . '</div>';
    }

    /**
     * Kommentar-Statistiken berechnen
     */
    protected function getCommentStats()
    {
        $commentList = new \fpcm\model\comments\commentList();

        $sObj = new \fpcm\model\comments\search();
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-comments fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_COMMENTS_ALL') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $commentList->countCommentsByCondition($sObj) . '</div>';

        $sObj = new \fpcm\model\comments\search();
        $sObj->unapproved = true;
        $count = $commentList->countCommentsByCondition($sObj);
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr ' . ($count > 0 ? 'fpcm-ui-important-text' : '') . '"><span class="fa fa-check-circle-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_COMMENTS_UNAPPR') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $count . '</div>';

        $sObj = new \fpcm\model\comments\search();
        $sObj->private = true;
        $count = $commentList->countCommentsByCondition($sObj);
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr ' . ($count > 0 ? 'fpcm-ui-important-text' : '') . '"><span class="fa fa-eye-slash fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_COMMENTS_PRIVATE') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $count . '</div>';

        $sObj = new \fpcm\model\comments\search();
        $sObj->spam = true;
        $count = $commentList->countCommentsByCondition($sObj);
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr ' . ($count > 0 ? 'fpcm-ui-important-text' : '') . '"><span class="fa fa-flag fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_COMMENTS_SPAM') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $count . '</div>';
    }

    /**
     * Benutzer-Statistiken berechnen
     */
    protected function getUserStats()
    {
        $userCountAll = $this->dbcon->count(\fpcm\classes\database::tableAuthors);
        $userCountAct = $this->dbcon->count(\fpcm\classes\database::tableAuthors, '*', 'disabled = 0');
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-users fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_USERS') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $userCountAll . ' (' . $userCountAct . ')</div>';

        $categoryCount = $this->dbcon->count(\fpcm\classes\database::tableCategories);
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-file-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_CATEGORIES') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $categoryCount . '</div>';
    }

    /**
     * Datei-Statistiken berechnen
     */
    protected function getFileStats()
    {

        $fileCount = $this->dbcon->count(\fpcm\classes\database::tableFiles, '*');
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-files-o fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_UPLOAD_COUNT') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $fileCount . '</div>';

        $imgList = new \fpcm\model\files\imagelist();
        $folderSize = \fpcm\classes\tools::calcSize($imgList->getUploadFolderSize());
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-calculator fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_UPLOAD_SIZE') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $folderSize . '</div>';
    }

    /**
     * Cache-Statistiken berechnen
     */
    protected function getCacheStats()
    {
        $folderSize = \fpcm\classes\tools::calcSize($this->cache->getSize());
        $this->tableContent[] = '<div class="col-9 fpcm-ui-padding-none-lr"><span class="fa fa-recycle fa-fw"></span> <strong>' . $this->language->translate('SYSTEM_STATS_CACHE_SIZE') . ':</strong></div><div class="col-3 fpcm-ui-center">' . $folderSize . '</div>';
    }

}
