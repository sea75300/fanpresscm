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
        return ['article' => ['edit', 'editall'], 'article' => 'archive'];
    }

    protected function getArticleCount()
    {
        $this->articleCount = $this->articleList->countArticlesByCondition($this->conditionItems);
    }

    protected function getArticleItems()
    {
        $this->conditionItems->limit = [$this->config->articles_acp_limit, $this->listShowStart];
        $this->articleItems = $this->articleList->getArticlesByCondition($this->conditionItems, true);
    }

    protected function getConditionItem()
    {
        $this->conditionItems = new \fpcm\model\articles\search();
        $this->conditionItems->archived = 1;
        $this->conditionItems->deleted = 0;
        $this->conditionItems->orderby = ['createtime DESC'];
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
        unset($this->articleActions[$this->language->translate('EDITOR_PINNED')], $this->articleActions[$this->language->translate('EDITOR_ARCHIVE')]);
        return parent::request();
    }

}

?>
