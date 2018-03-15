<?php

/**
 * Article list active controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlelistactive extends articlelistbase {

    /**
     *
     * @var bool
     */
    protected $showArchivedStatus = false;

    protected function getPermissions()
    {
        return ['article' => 'edit'];
    }
    
    protected function getArticleCount()
    {
        $this->articleCount = $this->articleList->countArticlesByCondition($this->conditionItems);        
    }

    protected function getArticleItems()
    {
        $this->conditionItems->archived = 0;
        $this->conditionItems->limit = [$this->config->articles_acp_limit, $this->listShowStart];
        $this->articleItems = $this->articleList->getArticlesByCondition($this->conditionItems, true);
    }

    protected function getConditionItem()
    {
        $this->conditionItems = new \fpcm\model\articles\search();
        $this->conditionItems->draft = -1;
        $this->conditionItems->drafts = -1;
        $this->conditionItems->active = -1;
        $this->conditionItems->archived = -1;
        $this->conditionItems->approval = -1;
        $this->conditionItems->orderby = ['createtime DESC'];
    }

    protected function getSearchMode()
    {
        return 0;
    }

    protected function getListAction()
    {
        $this->listAction = 'articles/listactive';
    }

}

?>