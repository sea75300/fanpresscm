<?php

/**
 * Article list active controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlelistarchive extends articlelistbase {

    /**
     *
     * @var bool
     */
    protected $showDraftStatus   = false;

    /**
     *
     * @var bool
     */
    protected $showArchivedStatus = false;

    protected function getPermissions()
    {
        return ['article' => 'edit', 'article' => 'editall', 'article' => 'archive'];
    }

    protected function getArticleCount()
    {
        $this->articleCount = $this->articleList->getArticlesArchived(false, [], true);
    }

    protected function getArticleItems()
    {
        $this->articleItems = $this->articleList->getArticlesArchived(true, [$this->listShowLimit, $this->listShowStart]);
    }

    protected function getConditionItem()
    {
        return null;
    }

    protected function getListAction()
    {
        $this->listAction = 'articles/listarchive';
    }

    protected function getSearchMode()
    {
        return 1;
    }

    public function request()
    {
        unset($this->articleActions[$this->lang->translate('EDITOR_PINNED')], $this->articleActions[$this->lang->translate('EDITOR_ARCHIVE')]);
        return parent::request();
    }

}

?>
