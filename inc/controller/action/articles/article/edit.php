<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\article;

/**
 * Article edit controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class edit extends base {

    use \fpcm\controller\traits\comments\lists,
        \fpcm\model\articles\permissions,
        \fpcm\model\traits\shareLinks;

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
        return 'editnews';
    }

    /**
     * @see \fpcm\controller\abstracts\controller::request()
     * @return bool
     */
    public function request()
    {
        $this->showRevisions = $this->permissions->article->revisions;

        if (!parent::request()) {

            $data = $this->request->fromPOST('article', [
                \fpcm\model\http\request::FILTER_STRIPSLASHES,
                \fpcm\model\http\request::FILTER_TRIM
            ]);

            $this->assignArticleFormData($data, time());
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

        $this->handleRevisionActions();
        $this->handleDeleteAction();

        $res = false;

        $added = $this->request->fromGET('added', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($res > 0 || $added === 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE');
            return true;
        }

        if ($added == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE_APPROVAL');
            return true;
        }

        return true;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::process()
     * @return mixed
     */
    public function process()
    {
        $this->article->enableTweetCreation($this->config->twitter_events['update']);

        $this->commentCount = array_sum($this->commentList->countComments([$this->article->getId()]));
        $this->revisionCount = $this->article->getRevisionsCount();

        parent::process();

        $this->view->setFormAction($this->article->getEditLink(), [], true);
        $this->view->assign('editorMode', 1);
        $this->view->assign('postponedTimer', $this->article->getCreatetime());
        $this->view->assign('pinnedTimer', $this->article->getPinnedUntil());
        $this->view->assign('commentsMode', 2);
        $this->view->assign('showArchiveStatus', true);

        $this->view->addDataView(new \fpcm\components\dataView\dataView('commentlist', false));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('revisionslist', false));

        $this->view->addJsVars([
            'canConnect' => \fpcm\classes\baseconfig::canConnect() ? 1 : 0,
            'articleId' => $this->article->getId(),
            'checkTimeout' => FPCM_ARTICLE_LOCKED_INTERVAL * 1000,
            'checkLastState' => (int) $this->article->isInEdit(),
            'lkIp' => $this->permissions->comment->lockip ? 1 : 0
        ]);

        $this->view->addJsLangVars(['EDITOR_STATUS_INEDIT', 'EDITOR_STATUS_NOTINEDIT', 'EDITOR_ARTICLE_SHORTLINK', 'COMMENTS_EDIT', 'COMMMENT_LOCKIP', 'EDITOR_ARTICLE_SHORTLINK_COPY']);

        if ($this->article->isInEdit()) {

            $data = $this->article->getInEdit();

            $username = $this->language->translate('GLOBAL_NOTFOUND');
            if (is_array($data)) {
                $user = new \fpcm\model\users\author($data[1]);
                if ($user->exists()) {
                    $username = $user->getDisplayname();
                }
            }

            $this->view->addMessage('EDITOR_STATUS_INEDIT', ['{{username}}' => $username]);
        }

        $users = $this->userList->getUsersByIds([
            $this->article->getCreateuser(),
            $this->article->getChangeuser()
        ]);

        $createUser = isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()] : null;
        $changeUser = isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()] : null;

        $this->view->assign('createInfo', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => $createUser ? $createUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())
        ]));

        $this->view->assign('changeInfo', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => $changeUser ? $changeUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
        ]));

        $this->addButtons();

        $shares = (new \fpcm\model\shares\shares())->getByArticleId($this->article->getId());

        $this->view->assign('shares', $shares);
        $this->view->assign('showShares', $this->config->system_share_count);
    }

    /**
     * Initialisiert Berechtigungen
     */
    protected function initPermissions()
    {
        $editComments = $this->permissions->editComments();
        $this->view->assign('showComments', $editComments);

        if ($editComments) {

            $this->view->addJsFiles(['comments/module.js', 'articles/deleteCallback.js']);
            if ($this->permissions->editCommentsMass()) {
                $this->view->addButton((new \fpcm\view\helper\button('massEdit', 'massEdit'))
                        ->setText('GLOBAL_EDIT')
                        ->setIcon('edit')
                        ->setClass( $this->getToolbarButtonToggleClass(2) ));
            }

            if ($this->permissions->comment->delete) {
                $this->view->addButton((new \fpcm\view\helper\deleteButton('deleteComment'))
                    ->setClass( $this->getToolbarButtonToggleClass(2) )
                    ->setText('EDITOR_COMMENTS_DELETE')
                    ->setOnClick('comments.deleteMultipleArticle')
                );
            }

            $this->initCommentMassEditForm(2);
        }

        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());
    }

    /**
     *
     * @return bool
     */
    private function handleDeleteAction()
    {
        if (!$this->buttonClicked('articleDelete') || !$this->checkPageToken) {
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
        if ($this->request->fromGET('revrestore')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLEREVRESTORE');
        }

        if (!$this->permissions->article->revisions) {
            return false;
        }

        $revisionIdsArray = $this->request->fromPOST('revisionIds', [\fpcm\model\http\request::FILTER_CASTINT]);
        if (!is_array($revisionIdsArray) || !count($revisionIdsArray)) {
            return false;
        }

        if (!$this->checkPageToken) {
            return false;
        }

        if ($this->buttonClicked('revisionDelete')) {
            if ($this->article->deleteRevisions($revisionIdsArray)) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_REVISIONS');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_REVISIONS');
            }

            return true;
        }

        if (!$this->buttonClicked('articleRevisionRestore')) {
            return true;
        }

        $rid = array_shift($revisionIdsArray);
        if ($this->article->restoreRevision($rid)) {
            $this->redirect('articles/edit&id=' . $this->article->getId() . '&revrestore=1');
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
        return true;
    }

    protected function onArticleSaveAfterSuccess(int $id): bool
    {
        $this->article->createRevision();
        $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE');

        return true;
    }

    private function addButtons()
    {
        $this->view->addButtons([
            (new \fpcm\view\helper\openButton('articlefe'))
                ->setUrlbyObject($this->article)
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
                ->setIconOnly()
                ->setClass( $this->getToolbarButtonToggleClass(1, ',', true) ),
            (new \fpcm\view\helper\button('shortlink'))
                ->setText('EDITOR_ARTICLE_SHORTLINK')
                ->setIcon('external-link-square-alt')
                ->setIconOnly()
                ->setClass( $this->getToolbarButtonToggleClass(1, ',', true) )
                ->setData([
                    'article' => $this->article->getId()
                ])
        ]);

        if ($this->article->getId() && $this->permissions->article->add) {

            $this->view->addButton(
                (new \fpcm\view\helper\button('articleCopy'))
                    ->setClass( $this->getToolbarButtonToggleClass(1, '', true) )
                    ->setReadonly($this->article->isInEdit())
                    ->setIcon('copy')->setIconOnly()
                    ->setText('GLOBAL_COPY')
                    ->setOnClick('system.createCopy', "article:{$this->article->getId()}")
            );

        }

        if ($this->article->getImagepath()) {
            $this->view->addButton((new \fpcm\view\helper\linkButton('articleimg'))
                    ->setUrl($this->article->getImagepath())
                    ->setText('EDITOR_ARTICLEIMAGE_SHOW')
                    ->setIcon('image')
                    ->setIconOnly()
                    ->setClass($this->getToolbarButtonToggleClass(1, 'fpcm ui-link-fancybox', true) ));
        }

        if ($this->permissions->article->delete && !$this->request->fromGET('rev')) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('articleDelete'))
                    ->setClass( $this->getToolbarButtonToggleClass(1, 'fpcm ui-button-confirm', true))
                    ->setReadonly($this->article->isInEdit()));
        }

        if ($this->permissions->article->revisions) {
            $this->view->addButton((new \fpcm\view\helper\submitButton('articleRevisionRestore'))
                    ->setText('EDITOR_REVISION_RESTORE')
                    ->setIcon('undo')
                    ->setReadonly($this->article->isInEdit())
                    ->setClass( $this->getToolbarButtonToggleClass(3) ));
            $this->view->addButton((new \fpcm\view\helper\deleteButton('revisionDelete'))
                    ->setClass($this->getToolbarButtonToggleClass(3, 'fpcm ui-button-confirm') )
                    ->setText('EDITOR_REVISION_DELETE'));
        }

        $this->addRelationButton();
        $this->addShareButtons();

        return true;
    }

    private function addShareButtons()
    {

        $shares = \fpcm\model\shares\shares::getAllRegisteredShares();


        $options = [];
        foreach ($shares as $share) {

            $url = $this->getLink($share, $this->article->getTitle(), rawurlencode($this->article->getElementLink()));
            if (!$url) {
                continue;
            }

            $icon = \fpcm\model\pubtemplates\sharebuttons::getShareItemClass($share);
            $options[] = (new \fpcm\view\helper\dropdownItem('share'.$share))
                    ->setIcon($icon['icon'], $icon['prefix'])
                    ->setSize('lg')
                    ->setUrl($url)
                    ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
                    ->setValue(md5($share))
                    ->setText(ucfirst($share));
        }

        $this->view->addToolbarRight(
            (new \fpcm\view\helper\dropdown('shareArticle'))
                ->setText('EDITOR_SHARE')
                ->setIcon('share')
                ->setIconOnly()
                ->setOptions($options)
                ->setSelected(false)
        );

    }

    private function addRelationButton()
    {
        if (!$this->article->getRelatesTo()) {
            return;
        }

        $tmp = new \fpcm\model\articles\article($this->article->getRelatesTo());
        if (!$tmp->exists()) {
            return;
        }

        $this->checkEditPermissions($tmp);

        if (!$tmp->getEditPermission()) {
            return;
        }

        $this->view->addButton(
            (new \fpcm\view\helper\linkButton('open-relation'))
                ->setText('COMMENTS_EDITARTICLE')
                ->setIcon('arrow-down-up-across-line')
                ->setClass( $this->getToolbarButtonToggleClass(1, '', true) )
                ->setIconOnly()
                ->setReadonly($tmp->isInEdit())
                ->setUrl($tmp->getEditLink())
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
        );
    }

}
