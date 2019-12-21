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

    /**
     *
     * @var bool
     */
    protected $isTrash = true;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->articleTrash();
    }

    protected function getArticleItems()
    {
        $this->articleItems = $this->articleList->getArticlesDeleted(true);
    }

    protected function getListAction()
    {
        $this->listAction = 'articles/trash';
    }

    /**
     * 
     * @return int
     */
    protected function getArticleCount()
    {
        return 0;
    }

    /**
     * 
     * @return bool
     */
    protected function getConditionItem()
    {
        return false;
    }

    /**
     * 
     * @return bool
     */
    protected function getSearchMode()
    {
        return false;
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink(): string
    {
        return 'articles_trash';
    }

    /**
     * 
     * @return string
     */
    protected function getTabHeadline(): string
    {
        return 'ARTICLES_TRASH';
    }

    public function request()
    {
        $this->articleActions = [$this->language->translate('ARTICLE_LIST_RESTOREARTICLE') => 'restore', $this->language->translate('ARTICLE_LIST_EMPTYTRASH') => 'trash'];

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

        $this->deleteActions = $this->permissions->article->delete;

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $this->view->addJsVars(['artCacheMod' => urlencode($crypt->encrypt(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE))]);
    }

}

?>