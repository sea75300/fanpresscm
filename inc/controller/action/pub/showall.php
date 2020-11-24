<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class showall extends showcommon {


    /**
     * 
     * @return string
     */
    protected function getCacheNameString() : string
    {
        return 'articlelist';
    }

    /**
     * Seitennavigation erzeugen
     * @param int $count
     * @param string $action
     * @return string
     */
    protected function createPagination($count, $action = 'fpcm/list')
    {
        $res = parent::createPagination($count, $action);
        if ($this->config->articles_archive_show) {
            $res = str_replace('</ul>', '<li><a href="?module=fpcm/archive" class="fpcm-pub-pagination-archive">' . $this->language->translate('ARTICLES_PUBLIC_ARCHIVE') . '</a></li>' . PHP_EOL . '</ul>' . PHP_EOL, $res);
        }

        $res = $this->events->trigger('pub\pageinationShowAll', $res);

        return $res ? $res : '';
    }
    
    protected function getContentData(): array
    {
        $conditions = new \fpcm\model\articles\search();
        $conditions->limit = [$this->limit, $this->offset];
        $conditions->draft = 0;
        $conditions->approval = 0;
        $conditions->deleted = 0;
        $conditions->postponed = \fpcm\model\articles\article::POSTPONED_SEARCH_FE;
        $conditions->archived = 0;
        $conditions->orderby = ['pinned DESC, ' . $this->config->articles_sort . ' ' . $this->config->articles_sort_order];

        if ($this->category !== 0) {
            $conditions->category = $this->category;
        }

        if (trim($this->search)) {
            $conditions->title = $this->search;
            $conditions->content =  $this->search;
        }

        $articles = $this->articleList->getArticlesByCondition($conditions);
        $this->users = $this->userList->getUsersForArticles(array_keys($articles));

        foreach ($articles as $article) {
            $parsed[] = $this->assignData($article);
        }

        $countConditions = new \fpcm\model\articles\search();
        $countConditions->draft = 0;
        $countConditions->approval = 0;
        $countConditions->deleted = 0;
        $countConditions->postponed = 0;
        $countConditions->archived = 0;
        if ($this->category !== 0) {
            $countConditions->category = $this->category;
        }

        if (trim($this->search)) {
            $countConditions->title = $this->search;
            $countConditions->content =  $this->search;
        }

        $parsed[] = $this->createPagination($this->articleList->countArticlesByCondition($countConditions));
        return $this->events->trigger('pub\showAll', $parsed);
    }

    protected function isArchive(): bool
    {
        return false;
    }

}
