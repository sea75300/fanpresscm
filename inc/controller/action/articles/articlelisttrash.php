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
        $this->articleActions = [$this->lang->translate('ARTICLE_LIST_RESTOREARTICLE') => 'restore', $this->lang->translate('ARTICLE_LIST_EMPTYTRASH') => 'trash'];

        $res = parent::request();

        if ($this->deleteActions) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('trash'))->setText('ARTICLE_LIST_EMPTYTRASH')->setClass('fpcm-ui-hidden fpcm-ui-button-confirm'));
        }

        $this->view->setFormAction('articles/trash');
        return $res;
    }

    protected function initArticleActions()
    {
        if (!$this->permissions) {
            return false;
        }

        $this->canEdit = $this->permissions->check(['article' => ['edit', 'editall', 'approve', 'archive']]);

        $this->view->assign('canEdit', $this->canEdit);
        $this->deleteActions = $this->permissions->check(['article' => 'delete']);

        $tweet = new \fpcm\model\system\twitter();

        if ($tweet->checkRequirements() && $tweet->checkConnection()) {
            $this->articleActions['ARTICLE_LIST_NEWTWEET'] = 'newtweet';
        }

        if ($this->deleteActions) {
            $this->articleActions['GLOBAL_DELETE'] = 'delete';
        }

        $this->articleActions['ARTICLES_CACHE_CLEAR'] = 'articlecache';

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $this->view->addJsVars(['artCacheMod' => urlencode($crypt->encrypt(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE))]);
    }

}

?>