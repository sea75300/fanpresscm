<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

/**
 * Article edit controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class articleedit extends articlebase {

    use \fpcm\controller\traits\comments\lists,
        \fpcm\model\articles\permissions;

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    protected $revisionArticle = null;

    /**
     *
     * @var int
     */
    protected $revisionId = 0;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->edit || $this->permissions->article->editall;
    }

    /**
     * 
     * @return string
     */
    protected function getActiveNavigationElement()
    {
        return 'itemnav-id-editnews';
    }

    /**
     * @see \fpcm\controller\abstracts\controller::request()
     * @return bool
     */
    public function request()
    {
        if (is_null($this->getRequestVar('articleid'))) {
            $this->redirect('articles/list');
        }

        if (!parent::request()) {
            return false;
        }

        if (!$this->article->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_ARTICLE', 'articles/listall');
            return false;
        }

        $this->initPermissions();
        $this->checkEditPermissions($this->article);
        if (!$this->article->getEditPermission()) {
            $this->view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            return false;
        }
        
        $this->userList     = new \fpcm\model\users\userList();
        $this->commentList  = new \fpcm\model\comments\commentList();

        if ($this->buttonClicked('doAction') && !$this->checkPageToken) {
            $data = $this->getRequestVar('article', [
                \fpcm\classes\http::FILTER_STRIPSLASHES,
                \fpcm\classes\http::FILTER_TRIM
            ]);

            $this->assignArticleFormData($data, time());
        }

        $this->handleRevisionActions();
        $this->handleDeleteAction();
        
        $res = false;
        if (!$this->showRevision && $this->checkPageToken && !$this->article->isInEdit()) {
            $res = $this->saveArticle();
                
            if ($this->emptyTitleContent) {
                return true;
            }

            if (!$res) {
                $this->view->addErrorMessage('SAVE_FAILED_ARTICLE');
                return false;
            }
        }
        
        $added = $this->getRequestVar('added', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if ($res > 0 || $added === 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE');
            return true;
        }

        if ($added == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE_APPROVAL');
            return true;
        }

        $this->handleCommentActions();

        if (!$this->revisionId) {
            $this->article->enableTweetCreation($this->config->twitter_events['update']);
        }

        return true;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::process()
     * @return mixed
     */
    public function process()
    {
        parent::process();

        $this->view->setFormAction('articles/edit', ['articleid' => $this->article->getId()]);
        $this->view->assign('editorMode', 1);
        $this->view->assign('showRevisions', true);
        $this->view->assign('postponedTimer', $this->article->getCreatetime());
        $this->view->assign('commentCount', array_sum($this->commentList->countComments([$this->article->getId()])));
        $this->view->assign('commentsMode', 2);
        $this->view->assign('revisionCount', $this->article->getRevisionsCount());
        
        $this->view->addDataView(new \fpcm\components\dataView\dataView('commentlist', false));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('revisionslist', false));

        $this->view->addJsVars([
            'canConnect' => \fpcm\classes\baseconfig::canConnect() ? 1 : 0,
            'articleId' => $this->article->getId(),
            'checkTimeout' => FPCM_ARTICLE_LOCKED_INTERVAL * 1000,
            'checkLastState' => -1,
            'lkIp' => $this->permissions->system->ipaddr ? 1 : 0
        ]);

        $this->view->addJsLangVars(['EDITOR_STATUS_INEDIT', 'EDITOR_STATUS_NOTINEDIT', 'EDITOR_ARTICLE_SHORTLINK', 'COMMENTS_EDIT', 'COMMMENT_LOCKIP']);

        if ($this->article->isInEdit()) {

            $data = $this->article->getInEdit();

            $username = $this->language->translate('GLOBAL_NOTFOUND');
            if (is_array($data)) {
                $user = new \fpcm\model\users\author($data[1]);
                if ($user->exists())
                    $username = $user->getDisplayname();
            }

            $this->view->addMessage('EDITOR_STATUS_INEDIT', ['{{username}}' => $username]);
        }

        if ($this->showRevision) {
            $this->view->assign('revisionArticle', $this->revisionArticle);
            $this->view->assign('editorFile', \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/revisiondiff.php'));
            $this->view->assign('isRevision', true);
            $this->view->assign('showRevisions', false);
            $this->view->assign('showComments', false);
            $this->view->assign('showShares', false);
            $this->view->setFormAction($this->article->getEditLink(), ['rev' => $this->revisionId], true);

            $this->view->addButton((new \fpcm\view\helper\linkButton('backToArticel'))
                    ->setUrl($this->article->getEditLink())
                    ->setText('EDITOR_BACKTOCURRENT')
                    ->setIcon('chevron-circle-left'), 2);
        } else {

            $users = $this->userList->getUsersByIds([
                $this->article->getCreateuser(),
                $this->article->getChangeuser()
            ]);
            
            $createUser = isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()] : null;
            $changeUser = isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()] : null;
            
            $this->view->assign('createInfo', $this->language->translate('EDITOR_AUTHOREDIT', [
                '{{username}}' => $createUser ? $createUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
                '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())           
            ]));

            $this->view->assign('changeInfo', $this->language->translate('EDITOR_LASTEDIT', [
                '{{username}}' => $changeUser ? $changeUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
                '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
            ]));
            
            $this->view->addButtons([
                (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($this->article)->setTarget('_blank')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1'),
                (new \fpcm\view\helper\button('shortlink'))
                    ->setText('EDITOR_ARTICLE_SHORTLINK')
                    ->setIcon('external-link-square-alt')
                    ->setIconOnly(true)
                    ->setClass('fpcm-ui-maintoolbarbuttons-tab1')
                    ->setData([
                        'article' => $this->article->getId()
                    ]),
            ]);

            if ($this->article->getImagepath()) {
                $this->view->addButton((new \fpcm\view\helper\linkButton('articleimg'))->setUrl($this->article->getImagepath())->setText('EDITOR_ARTICLEIMAGE_SHOW')->setIcon('image')->setIconOnly(true)->setClass('fpcm-editor-articleimage fpcm-ui-maintoolbarbuttons-tab1'));
            }

            $shares = (new \fpcm\model\shares\shares())->getByArticleId($this->article->getId());

            $this->view->assign('shares', $shares);
            $this->view->assign('showShares', $this->config->system_share_count);
        }
        
        if ($this->permissions->article->revisions) {
             $this->view->addButton((new \fpcm\view\helper\submitButton('articleRevisionRestore'))->setText('EDITOR_REVISION_RESTORE')->setIcon('undo')->setReadonly($this->article->isInEdit())->setClass('fpcm-ui-maintoolbarbuttons-tab3 '.($this->showRevision ? '' : 'fpcm-ui-hidden')));
            if (!$this->showRevision) {
                $this->view->addButton((new \fpcm\view\helper\deleteButton('revisionDelete'))->setClass('fpcm-ui-maintoolbarbuttons-tab3 fpcm-ui-hidden fpcm-ui-button-confirm')->setText('EDITOR_REVISION_DELETE'));
                
            }
        }

        $this->view->render();
    }

    /**
     * Initialisiert Berechtigungen
     */
    protected function initPermissions()
    {
        $editComments = $this->permissions->editComments();
        $this->view->assign('showComments', $editComments);

        if ($editComments) {
            
            $this->initCommentPermissions();
            
            $this->view->addJsFiles(['comments/module.js']);
            if ($this->permissions->editCommentsMass()) {
                $this->view->addButton((new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden'));
            }

            if ($this->permissions->comment->delete) {
                $this->view->addButton((new \fpcm\view\helper\deleteButton('deleteComment'))->setClass('fpcm-ui-button-confirm fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden fpcm-ui-button-confirm')->setText('EDITOR_COMMENTS_DELETE'));
            }

            $this->initCommentMassEditForm(2);
        }

        if ($this->permissions->article->delete && !$this->getRequestVar('rev')) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('articleDelete'))->setClass('fpcm-ui-maintoolbarbuttons-tab1 fpcm-ui-button-confirm')->setReadonly($this->article->isInEdit()));
        }

        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());
    }

    /**
     * Kommentar-Aktionen ausführen
     * @return bool
     */
    protected function handleCommentActions()
    {
        if (!$this->checkPageToken || !$this->buttonClicked('deleteComment')) {
            return false;
        }

        $this->processCommentActions($this->commentList);
    }

    /**
     * 
     * @return bool
     */
    private function handleDeleteAction()
    {
        if (!$this->buttonClicked('articleDelete') || $this->showRevision || !$this->checkPageToken) {
            return true;
        }

        if ($this->article->isInEdit()) {
            return false;
        }

        if ($this->permissions->article->delete && $this->article->delete()) {
            $this->redirect('articles/listall');
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_ARTICLE');
        return false;
    }

    /**
     * 
     * @return bool
     */
    private function handleRevisionActions()
    {
        if ($this->getRequestVar('revrestore')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLEREVRESTORE');
        }
        
        if (!$this->permissions->article->revisions) {
            return false;
        }

        $revisionIdsArray = $this->getRequestVar('revisionIds', [\fpcm\classes\http::FILTER_CASTINT]);

        if ($this->buttonClicked('revisionDelete') && is_array($revisionIdsArray) && !$this->showRevision && $this->checkPageToken) {
            if ($this->article->deleteRevisions($revisionIdsArray)) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_REVISIONS');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_REVISIONS');
            }
            
            return true;
        }

        $this->revisionId = !is_null($this->getRequestVar('rev')) ? (int) $this->getRequestVar('rev') : (is_array($revisionIdsArray) ? array_shift($revisionIdsArray) : false);

        if ($this->buttonClicked('articleRevisionRestore') && $this->revisionId && $this->checkPageToken) {

            if ($this->article->restoreRevision($this->revisionId)) {
                $this->redirect('articles/edit&articleid=' . $this->article->getId() . '&revrestore=1');
            } else {
                $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            }

            return true;
        }

        if (!$this->revisionId) {
            return false;
        }

        include_once \fpcm\classes\loader::libGetFilePath('PHP-FineDiff/finediff.php');

        $this->revisionArticle = clone $this->article;

        if (!$this->revisionId) {
            $this->revisionId = $this->getRequestVar('rev', [\fpcm\classes\http::FILTER_CASTINT]);
        }

        $this->showRevision = ($this->revisionArticle->getRevision($this->revisionId) ? true : false);
        
        $users = $this->userList->getUsersByIds([
            $this->article->getCreateuser(),
            $this->article->getChangeuser(),
            $this->revisionArticle->getCreateuser(),
            $this->revisionArticle->getChangeuser(),
        ]);
        
        $this->view->assign('createInfoOrg', $this->language->translate('EDITOR_AUTHOREDIT', [
            '{{username}}' => isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())           
        ]));

        $this->view->assign('changeInfoOrg', $this->language->translate('EDITOR_LASTEDIT', [
            '{{username}}' => isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
        ]));
        
        $this->view->assign('createInfoRev', $this->language->translate('EDITOR_AUTHOREDIT', [
            '{{username}}' => isset($users[$this->revisionArticle->getCreateuser()]) ? $users[$this->revisionArticle->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revisionArticle->getCreatetime())           
        ]));

        $this->view->assign('changeInfoRev', $this->language->translate('EDITOR_LASTEDIT', [
            '{{username}}' => isset($users[$this->revisionArticle->getChangeuser()]) ? $users[$this->revisionArticle->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revisionArticle->getChangetime())
        ]));
        

        $from   = $this->revisionArticle->getContent();
        $opcode = \FineDiff::getDiffOpcodes($from, $this->article->getContent(), \FineDiff::$characterGranularity);
        $this->view->assign('textDiff', \FineDiff::renderDiffToHTMLFromOpcodes($from, $opcode));
        $this->view->addJsVars(['isRevision' => true]);

        return true;
    }

}

?>
