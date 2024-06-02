<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\comments\lists;

/**
 * Comment list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class all extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\comments\lists,
        \fpcm\controller\traits\common\searchParams;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->config->system_comments_enabled && $this->permissions->editComments();
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_comments_mng';
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'comments/commentlist';
    }

    /**
     * @see \fpcm\controller\abstracts\controller::process()
     * @return mixed
     */
    public function process()
    {
        $this->view->addAjaxPageToken('comments/delete');
        $this->view->assign('commentsMode', 1);
        $this->view->addJsLangVars(['DELETE_SUCCESS_COMMENTS', 'DELETE_FAILED_COMMENTS']);

        $this->initSearchForm();
        $this->initCommentMassEditForm(1);

        $this->view->addJsFiles(['comments/module.js', 'comments/deleteCallback.js']);
        $this->view->setFormAction('comments/list');

        $searchPrimary = true;
        if ($this->permissions->editCommentsMass()) {
            $searchPrimary = false;
            $this->view->addButton((new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setPrimary());
        }

        $this->view->addButton((new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly()->setPrimary($searchPrimary));

        if ($this->permissions->comment->delete) {
            $this->view->addButton( (new \fpcm\view\helper\button('deleteComment'))
                ->setText('GLOBAL_DELETE')
                ->setIcon('trash')
                ->setIconOnly()
                ->setOnClick('comments.deleteMultipleArticle')
            );            
        }

        $this->initDataView();
        $this->view->addDataView($this->dataView);
        $this->view->addPager(new \fpcm\view\helper\pager('comments/list', $this->page, $this->commentCount, $this->config->articles_acp_limit, $this->maxItemCount));
        
        $this->view->addTabs('comments', [
            (new \fpcm\view\helper\tabItem('tabs-comments-list'))
                ->setText('COMMMENT_HEADLINE')
                ->setFile($this->getViewPath() . '.php')
        ]);
        
    }
    
    /**
     * Initialisiert Suchformular-Daten
     * @param array $users
     */
    private function initSearchForm()
    {
        $this->assignSearchFromVars();

        $this->view->assign('searchTypes', [
            'COMMENTS_SEARCH_TYPE_ALL' => \fpcm\model\comments\search::TYPE_ALL,
            'COMMENTS_SEARCH_TYPE_ALLOR' => \fpcm\model\comments\search::TYPE_ALLOR,
            'COMMENTS_SEARCH_TYPE_TEXT' => \fpcm\model\comments\search::TYPE_TEXT,
            'COMMENTS_SEARCH_TYPE_NAMEMAILWEB' => \fpcm\model\comments\search::TYPE_NAMEMAILWEB,
            'COMMENTS_SEARCH_TYPE_NAMEMAILWEB_OR' => \fpcm\model\comments\search::TYPE_NAMEMAILWEB_OR
        ]);

        $this->view->assign('searchApproval', array(
            'GLOBAL_SELECT' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));

        $this->view->assign('searchSpam', array(
            'GLOBAL_SELECT' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));

        $this->view->assign('searchPrivate', array(
            'GLOBAL_SELECT' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));

        $this->view->addJsVars([
            'commentsLastSearch' => 0,
            'massEditSaveFailed' => 'SAVE_FAILED_COMMENTS'
        ]);
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setUrl('#tabs-'.$this->getDataViewName().'-list')
                ->setText('COMMMENT_HEADLINE')
                ->setFile($this->getViewPath() . '.php')
        ];
    }

}
