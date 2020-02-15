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
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->edit;
    }

    /**
     *
     * @var bool
     */
    protected $showArchivedStatus = false;
    
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
        $this->conditionItems->archived = 0;
        $this->conditionItems->deleted = 0;
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