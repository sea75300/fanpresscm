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

    protected function getContentData(): array
    {
        $conditions = new \fpcm\model\articles\search();
        $this->assignConditions($conditions);

        $articles = $this->articleList->getArticlesByCondition($conditions);
        $this->users = $this->userList->getUsersForArticles(array_keys($articles));


        if ($this->config->system_twig) {

            $vars = [];

            $notFoundStr = $this->language->translate('GLOBAL_NOTFOUND');

            foreach ($articles as $article) {
                
                $author = $this->users[$article->getCreateuser()] ?? null;

                $changeUser = $this->users[$article->getChangeuser()] ?? null;
                
                $categories = $this->categoryList->assignPublic($article);
                $commentCount = $this->commentCounts[$article->getId()] ?? 0;
                
                /* @var $share sharebuttons */
                $share = \fpcm\classes\loader::getObject('\fpcm\model\pubtemplates\sharebuttons');
                $share->assignData($article->getElementLink(), $article->getTitle(), $article->getId());
                
                
                $vars[] = [
                    'headline' => $article->getTitle(),
                    'text' => $article->getContent(),
                    'textShort' => $article->getContent(),
                    'date' => date($this->config->system_dtmask, $article->getCreatetime()),
                    'statusPinned' => $article->getPinned() ? $this->language->translate('PUBLIC_ARTICLE_PINNED') : '',
                    'shareButtons' => '',
                    'commentCount' => $this->config->system_comments_enabled && $article->getComments() ? (int) $commentCount : 0,
                    'author' => $author ? $author->getDisplayname() : $notFoundStr,
                    'authorEmail' => ($author ? '<a href="mailto:' . $author->getEmail() . '">' . $author->getDisplayname() . '</a>' : ''),
                    'authorAvatar' => $author ? \fpcm\model\users\author::getAuthorImageDataOrPath($author, 0) : '',
                    'authorInfoText' => $author ? nl2br($author->getUsrinfo(), false) : '',
                    'changeDate' => date($this->config->system_dtmask, $article->getChangetime()),
                    'changeUser' => $changeUser ? $changeUser->getDisplayname() : $notFoundStr,
                    'categoryIcons' => implode(PHP_EOL, array_values($categories)),
                    'categoryTexts' => implode(PHP_EOL, array_keys($categories)),
                    'permaLink' => $article->getElementLink(),
                    'commentLink' => $article->getElementLink('#comments'),
                    'articleImage' => $article->getArticleImage(),
                    'sources' => $article->getSources(),
                    'oldarticle' => $article->isOldArticle() ? $this->language->translate('PUBLIC_ARTICLE_OLD') : ''
                ];
            }

            $twig = new \fpcm\model\pubtemplates\twig();
            $twig->fromSystemTemplate('articles.html.twig', [
                'articles' => $vars,
                'debug' => \fpcm\classes\baseconfig::debugModeActive(),
                'is_logged_in' => $this->session->exists(),
                'permissions' => [
                    'can_add' => true,
                    'can_edit' => false
                ]
            ]);
            echo $twig->render();                
                
            return [];
        }



        foreach ($articles as $article) {
            $parsed[] = $this->assignData($article);
        }

        $countConditions = new \fpcm\model\articles\search();
        $this->assignConditions($countConditions);

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
