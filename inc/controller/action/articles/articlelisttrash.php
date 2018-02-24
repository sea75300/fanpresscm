<?php

/**
 * Article trash controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */

namespace fpcm\controller\action\articles;

class articlelisttrash extends articlelistbase {

    protected function getPermissions()
    {
        return ['article' => 'edit', 'article' => 'editall'];
    }

    protected function getArticleCount()
    {
        return 0;
    }

    protected function getArticleItems()
    {
        $this->articleItems = $this->articleList->getArticlesDeleted(true);
    }

    protected function getConditionItem()
    {
        return false;
    }

    protected function getListAction()
    {
        $this->listAction = 'articles/trash';
    }

    protected function getSearchMode()
    {
        return false;
    }

    public function request()
    {
        $res = parent::request();

        $this->articleActions = [$this->lang->translate('ARTICLE_LIST_RESTOREARTICLE') => 'restore', $this->lang->translate('ARTICLE_LIST_EMPTYTRASH') => 'trash'];
        if ($this->deleteActions) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('trash'))->setText('ARTICLE_LIST_EMPTYTRASH')->setClass('fpcm-ui-hidden fpcm-ui-button-confirm'));
        }

        $this->view->setFormAction('articles/trash');
        return $res;
    }

}

?>