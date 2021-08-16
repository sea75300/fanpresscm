<?php

/**
 * Article trash controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5
 */

namespace fpcm\controller\action\articles;

class articlelisttrash extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\articles\listsCommon,
        \fpcm\controller\traits\articles\lists; 

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->articleTrash();
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_article_edit';
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'articles/listouter';
    }

    public function process()
    {
        $this->isTrash = true;
        
        $this->initActionObjects();

        $this->view->addAjaxPageToken('clearTrash');
        $this->view->setFormAction('articles/trash');
        $this->view->addJsFiles(['articles/trash.js']);
        $this->view->assign('includeSearchForm', false);
        $this->view->assign('includeMassEditForm', false);

        $this->view->addButtons([
            (new \fpcm\view\helper\button('restoreFromTrash'))
                ->setIcon('trash-restore')
                ->setText('ARTICLE_LIST_RESTOREARTICLE')
                ->setOnClick('articles_trash.restoreFromTrash'),
            (new \fpcm\view\helper\button('emptyTrash'))
                ->setIcon('trash')
                ->setText('ARTICLE_LIST_EMPTYTRASH')
                ->setOnClick('articles_trash.emptyTrash')
        ]);        
        
        
        $this->items = $this->articleList->getArticlesDeleted(true);
        $this->translateCategories();

        $this->initDataView();
        $this->view->addDataView($this->dataView);
        
        $this->view->addTabs('articles', [
            (new \fpcm\view\helper\tabItem('articles'))->setText('ARTICLES_TRASH')->setFile('articles/listouter.php')
        ]);
    }

}
