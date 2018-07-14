<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\comments;

/**
 * Comment list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class commentlist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\comments\lists;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

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
     * @see \fpcm\controller\abstracts\controller::request()
     * @return boolean
     */
    public function request()
    {
        if (!$this->buttonClicked('deleteComment')) {
            return true;
        }

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        return $this->processCommentActions($this->list);
    }

    /**
     * @see \fpcm\controller\abstracts\controller::process()
     * @return mixed
     */
    public function process()
    {
        $this->view->assign('commentsMode', 1);
        
        $this->initCommentPermissions();
        $this->initSearchForm();
        $this->initCommentMassEditForm(1);

        $this->view->addJsFiles(['comments.js']);
        $this->view->setFormAction('comments/list');

        if ($this->permissionsArray['canEditComments'] && $this->permissionsArray['canMassEdit']) {
            $this->view->addButton((new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true));
        }

        $this->view->addButton((new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true));

        if ($this->permissionsArray['canDelete']) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('deleteComment'))->setClass('fpcm-ui-button-confirm'));
        }

        $this->initDataView();
        $this->view->addDataView($this->dataView);
        $this->view->addPager(new \fpcm\view\helper\pager('comments/list', $this->page, $this->commentCount, $this->config->articles_acp_limit, $this->maxItemCount));
    }

    
    /**
     * Initialisiert Suchformular-Daten
     * @param array $users
     */
    private function initSearchForm()
    {
        $this->view->assign('searchTypes', [
            'COMMENTS_SEARCH_TYPE_ALL' => 0,
            'COMMENTS_SEARCH_TYPE_TEXT' => 1
        ]);

        $this->view->assign('searchApproval', array(
            'COMMMENT_APPROVE' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));

        $this->view->assign('searchSpam', array(
            'COMMMENT_SPAM' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));

        $this->view->assign('searchPrivate', array(
            'COMMMENT_PRIVATE' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ));
        $this->view->assign('searchCombination', array(
            'ARTICLE_SEARCH_LOGICAND' => 0,
            'ARTICLE_SEARCH_LOGICOR' => 1
        ));

        $this->view->addJsLangVars(['SEARCH_WAITMSG', 'ARTICLES_SEARCH', 'ARTICLE_SEARCH_START']);
        $this->view->addJsVars([
            'commentsLastSearch' => 0,
            'massEditSaveFailed' => 'SAVE_FAILED_COMMENTS'
        ]);
    }

}

?>