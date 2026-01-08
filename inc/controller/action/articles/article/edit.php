<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\article;

/**
 * Article edit controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2026, Stefan Seehafer
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

        $msg = $this->request->fromGET('msg');

        $msgText = match ($msg) {
            self::MESSAGE_RESTORE_REVISION => 'ARTICLEREVRESTORE',
            self::MESSAGE_ARTICLE_ADDED => 'ARTICLE',
            self::MESSAGE_ARTICLE_APPROVE => 'ARTICLE_APPROVAL',
            default => null,
        };

        if ($msgText) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_' . $msgText);
        }

        return true;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        $this->commentCount = array_sum($this->commentList->countComments([$this->article->getId()]));
        $this->revisionCount = $this->article->getRevisionsCount();

        parent::process();

        $this->view->setFormAction(
            controller: $this->article->getEditLink(),
            isLink: true
        );
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

        $this->view->addJsLangVars([
            'EDITOR_STATUS_INEDIT', 'EDITOR_STATUS_NOTINEDIT',
            'EDITOR_ARTICLE_SHORTLINK', 'COMMENTS_EDIT',
            'COMMMENT_LOCKIP', 'EDITOR_ARTICLE_SHORTLINK_COPY',
            'TEMPLATE_ARTICLE_ARTICLEIMAGE'
        ]);

        $this->addButtons();
        $this->assignChangeData();
        $this->assignShares();
        $this->showInEditMessage();
    }

    private function showInEditMessage() : bool
    {
        if (!$this->article->isInEdit()) {
            return false;
        }

        $data = $this->article->getInEdit();

        $username = $this->language->translate('GLOBAL_NOTFOUND');
        if (is_array($data)) {
            $user = new \fpcm\model\users\author($data[1]);
            if ($user->exists()) {
                $username = $user->getDisplayname();
            }
        }

        $this->view->addMessage('EDITOR_STATUS_INEDIT', ['{{username}}' => $username]);
        return true;
    }

    private function assignChangeData()
    {
        $this->view->assign('createInfo', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => \fpcm\classes\tools::userId2Text($this->article->getCreateuser()),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())
        ]));

        $this->view->assign('changeInfo', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => \fpcm\classes\tools::userId2Text($this->article->getChangeuser()),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
        ]));
    }

    private function assignShares()
    {
        $shares = (new \fpcm\model\shares\shares())->getByArticleId($this->article->getId());

        $this->view->assign('shares', $shares);
        $this->view->assign('showShares', $this->config->system_share_count);
    }

    private function addButtons()
    {
        $this->view->addButtons([
            (new \fpcm\view\helper\openButton('articlefe'))
                ->setUrlbyObject($this->article)
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
                ->setIconOnly()
                ->setToolbarToggle(1),
            (new \fpcm\view\helper\button('shortlink'))
                ->setText('EDITOR_ARTICLE_SHORTLINK')
                ->setIcon('external-link-square-alt')
                ->setIconOnly()
                ->setToolbarToggle(1)
                ->setData([
                    'article' => $this->article->getId()
                ])
        ]);

        if ($this->article->getId() && $this->permissions->article->add) {

            $this->view->addButton(
                (new \fpcm\view\helper\copyButton('articleCopy'))
                    ->setToolbarToggle(1)
                    ->setReadonly($this->article->isInEdit())
                    ->setCopyParams($this->article, 'article')
            );

        }

        if ($this->article->getImagepath()) {
            $this->view->addButton((new \fpcm\view\helper\linkButton('articleimg'))
                    ->setUrl($this->article->getImagepath())
                    ->setText('EDITOR_ARTICLEIMAGE_SHOW')
                    ->setIcon('image')
                    ->setIconOnly()
                    ->setToolbarToggle(1));
        }

        if ($this->permissions->article->delete && !$this->request->fromGET('rev')) {
            $this->view->addButton((new \fpcm\view\helper\deleteButton('articleDelete'))
                    ->setToolbarToggle(1)
                    ->setReadonly($this->article->isInEdit())
                    ->setClickConfirm());
        }

        if ($this->permissions->article->revisions) {
            $this->view->addButton((new \fpcm\view\helper\submitButton('articleRevisionRestore'))
                    ->setText('EDITOR_REVISION_RESTORE')
                    ->setIcon('undo')
                    ->setReadonly($this->article->isInEdit())
                    ->setToolbarToggle(
                        toolbarTab: 3,
                        default: true
                    ));
            $this->view->addButton((new \fpcm\view\helper\deleteButton('revisionDelete'))
                    ->setToolbarToggle(
                        toolbarTab: 3,
                        default: true
                    )
                    ->setText('EDITOR_REVISION_DELETE')
                    ->setClickConfirm());
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
                ->setToolbarToggle(1)
                ->setIconOnly()
                ->setReadonly($tmp->isInEdit())
                ->setUrl($tmp->getEditLink())
                ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
        );
    }

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
                        ->setToolbarToggle(
                            toolbarTab: 2,
                            default: true
                        )
                    );
            }

            if ($this->permissions->comment->delete) {
                $this->view->addButton((new \fpcm\view\helper\deleteButton('deleteComment'))
                    ->setText('EDITOR_COMMENTS_DELETE')
                    ->setOnClick('comments.deleteMultipleArticle')
                    ->setToolbarToggle(
                        toolbarTab: 2,
                        default: true
                    )
                );
            }

            $this->initCommentMassEditForm(2);
        }

        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());
    }

    protected function onArticleSaveAfterSuccess(int $id): bool
    {
        $this->article->createRevision();
        $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE');
        return true;
    }

    protected function onArticleDelete(): bool
    {
        if (!$this->permissions->article->delete ||
            $this->article->isInEdit() ||
            !$this->checkPageToken) {
            $this->view->addErrorMessage('DELETE_FAILED_ARTICLE');
            return false;
        }

        if (!$this->article->delete()) {
            $this->view->addErrorMessage('DELETE_FAILED_ARTICLE');
            return false;
        }

        $this->redirect('articles/listall');
        return true;
    }

    protected function onArticleRevisionRestore(): bool
    {
        if (!$this->permissions->article->revisions ||
            $this->article->isInEdit() ||
            !$this->checkPageToken) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }

        $revIds = $this->request->fromPOST('revisionIds', [\fpcm\model\http\request::FILTER_CASTINT]);
        if (!is_array($revIds) || !count($revIds)) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }


        $rid = array_shift($revIds);
        if (!$this->article->restoreRevision($rid)) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }

        $this->redirect('articles/edit', [
            'id' => $this->article->getId(),
            'msg' => self::MESSAGE_RESTORE_REVISION
        ]);
        return true;
    }

    protected function onRevisionDelete(): bool
    {
        if (!$this->permissions->article->revisions ||
            $this->article->isInEdit() ||
            !$this->checkPageToken) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }

        $revIds = $this->request->fromPOST('revisionIds', [\fpcm\model\http\request::FILTER_CASTINT]);
        if (!is_array($revIds) || !count($revIds)) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }

        if (!$this->article->deleteRevisions($revIds)) {
            $this->view->addErrorMessage('DELETE_FAILED_REVISIONS');
            return false;
        }

        $this->view->addNoticeMessage('DELETE_SUCCESS_REVISIONS');
        return true;
    }

}
