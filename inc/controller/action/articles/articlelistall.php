<?php

/**
 * Article list all controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlelistall extends articlelistbase {

    protected function getPermissions()
    {
        return ['article' => 'edit', 'article' => 'editall'];
    }

    protected function getListAction()
    {
        $this->listAction = 'articles/listall';
    }

    protected function getArticleCount()
    {
        $this->articleCount = $this->articleList->countArticlesByCondition($this->conditionItems);
    }

    protected function getArticleItems()
    {
        $this->conditionItems->limit = [$this->listShowLimit, $this->listShowStart];
        $this->articleItems = $this->articleList->getArticlesByCondition($this->conditionItems, true);
    }

    protected function getSearchMode()
    {
        return -1;
    }

    protected function getConditionItem()
    {
        $this->conditionItems = new \fpcm\model\articles\search();
        $this->conditionItems->draft = -1;
        $this->conditionItems->drafts = -1;
        $this->conditionItems->approval = -1;
    }

}

?>