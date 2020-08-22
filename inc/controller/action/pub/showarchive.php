<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article archive list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class showarchive extends showcommon {

    /**
     * 
     * @return string
     */
    protected function getCacheNameString() : string
    {
        return 'articlearchive';
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
        $res = str_replace('</ul>', '<li><a href="?module=fpcm/list" class="fpcm-pub-pagination-page">' . $this->language->translate('ARTICLES_PUBLIC_ACTIVE') . '</a></li>' . PHP_EOL . '</ul>' . PHP_EOL, $res);
        $res = $this->events->trigger('pub\pageinationShowArchive', $res);

        return $res;
    }

    protected function getContentData(): array
    {
        $conditions = new \fpcm\model\articles\search();
        $conditions->limit = [$this->limit, $this->offset];
        $conditions->archived = 1;
        $conditions->postponed = \fpcm\model\articles\article::POSTPONED_INACTIVE;

        if ($this->config->articles_archive_datelimit) {
            $conditions->datefrom = $this->config->articles_archive_datelimit;
        }

        if ($this->category !== 0) {
            $conditions->category = $this->category;
        }

        $articles = $this->articleList->getArticlesByCondition($conditions);
        $this->users = $this->userList->getUsersForArticles(array_keys($articles));

        foreach ($articles as $article) {
            $parsed[] = $this->assignData($article);
        }

        $countConditions = new \fpcm\model\articles\search();
        $countConditions->archived = true;
        if ($this->category !== 0) {
            $countConditions->category = $this->category;
        }

        if ($this->config->articles_archive_datelimit) {
            $countConditions->datefrom = $this->config->articles_archive_datelimit;
        }

        $parsed[] = $this->createPagination($this->articleList->countArticlesByCondition($countConditions), 'fpcm/archive');

        return $this->events->trigger('pub\showArchive', $parsed);
    }

    protected function isArchive(): bool
    {
        return true;
    }

}
