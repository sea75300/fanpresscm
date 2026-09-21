<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

        $ev = $this->events->trigger('pub\pageinationShowAll', $res);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event pub\pageinationShowAll failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return '';
        }

        $res = $ev->getData();
        return $res ?? '';
    }

    /**
     * Get content data
     * @return array
     */
    protected function getContentData(): array
    {
        $conditions = new \fpcm\model\articles\search();
        $this->assignConditions($conditions);

        $articles = $this->articleList->getArticlesByCondition($conditions);
        $this->users = $this->userList->getUsersForArticles(array_keys($articles));

        $countConditions = new \fpcm\model\articles\search();
        $this->assignConditions($countConditions);
        
        if ($this->parseTwig($articles, $countConditions)) {
            return [];
        }

        return $this->parseLegacy($articles, $countConditions);

    }

    /**
     * Parse Twig template
     * @param array $articles
     * @param \fpcm\model\articles\search $countConditions
     * @return bool
     */
    private function parseTwig(
        array $articles,
        \fpcm\model\articles\search $countConditions
    ) : bool
    {
        if (!$this->config->system_twig) {
            return false;
        }

        $twig = new \fpcm\model\templates\pub\articles\overview();

        foreach ($articles as $article) {
            $twig->assignArticle(
                $article,
                $this->users[$article->getCreateuser()] ?? null,
                $this->users[$article->getChangeuser()] ?? null,
                $this->categoryList->assignPublic($article),
                $this->commentCounts[$article->getId()] ?? 0
            );
        }

        $twig->setPager(
            $this->articleList->countArticlesByCondition($countConditions),
            $this->limit,
            $this->page,
            $this->config->articles_archive_show,
            'fpcm/list'
        );

        echo $twig->render();

        return true;
    }

    /**
     * Use legacy Template paraser
     * @param array $articles
     * @param \fpcm\model\articles\search $countConditions
     * @return array
     */
    private function parseLegacy(
        array $articles,
        \fpcm\model\articles\search $countConditions
    ) : array
    {
        $parsed = [];
        
        foreach ($articles as $article) {
            $parsed[] = $this->assignData($article);
        }

        $parsed[] = $this->createPagination($this->articleList->countArticlesByCondition($countConditions));

        $ev = $this->events->trigger('pub\showAll', $parsed);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event pub\showAll failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $parsed;
        }

        return $ev->getData();
    }

    protected function isArchive(): bool
    {
        return false;
    }

    protected function assignConditions(\fpcm\model\articles\search &$conditions): bool
    {
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

        $doSearch = trim($this->search) && strlen($this->search) >= FPCM_PUB_SEARCH_MINLEN;
        if (!$doSearch) {
            return true;
        }

        $conditions->content =  $this->search;
        return true;
    }

}
