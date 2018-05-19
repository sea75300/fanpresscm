<?php

/**
 * Comment edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\comments;

class commentedit extends \fpcm\controller\abstracts\controller {

    use \fpcm\model\comments\permissions;

    /**
     *
     * @var \fpcm\model\comments\comment
     */
    protected $comment;

    /**
     *
     * @var bool
     */
    protected $approve = false;

    /**
     *
     * @var bool
     */
    protected $private = false;

    /**
     *
     * @var array
     */
    protected $ownArticleIds = [];

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return 'comments/commentedit';
    }

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return [
            'article' => [
                'editall',
                'edit'
            ],
            'comment' => [
                'editall',
                'edit'
            ]
        ];
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
    protected function getActiveNavigationElement()
    {
        return 'itemnav-item-editcomments';
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        if ($this->permissions) {
            $this->approve = $this->permissions->check(array('comment' => 'approve'));
            $this->private = $this->permissions->check(array('comment' => 'private'));
        }

        $id = $this->getRequestVar('commentid', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);
        if (!$id) {
            $this->redirect('comments/list');
        }

        $this->comment = new \fpcm\model\comments\comment($id);

        if (!$this->comment->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_COMMENT', 'comments/list');
            return false;
        }

        $this->checkEditPermissions($this->comment);
        if (!$this->comment->getEditPermission()) {
            $this->view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            return false;
        }

        $commentData = $this->getRequestVar('comment', [
            \fpcm\classes\http::FILTER_STRIPSLASHES,
            \fpcm\classes\http::FILTER_TRIM
        ]);

        if ($this->buttonClicked('commentSave') && $commentData !== null) {

            $this->comment->setText($commentData['text']);
            unset($commentData['text']);

            foreach ($commentData as &$value) {
                $value = \fpcm\classes\http::filter($value, array(1, 3));
            }

            $this->comment->setName($commentData['name']);
            $this->comment->setEmail($commentData['email']);
            $this->comment->setWebsite($commentData['website']);

            if ($this->approve) {
                $this->comment->setApproved(isset($commentData['approved']) ? true : false);
                $this->comment->setSpammer(isset($commentData['spam']) ? true : false);
            }

            if ($this->private) {
                $this->comment->setPrivate(isset($commentData['private']) ? true : false);
            }

            $this->comment->setChangetime(time());
            $this->comment->setChangeuser($this->session->getUserId());

            if (!$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }
            
            if ($this->comment->update()) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_COMMENT');
                return true;
            }

            $this->view->addErrorMessage('SAVE_FAILED_COMMENT');
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function process()
    {
        $mode = $this->getRequestVar('mode', [\fpcm\classes\http::FILTER_CASTINT]);

        if ($mode === 2) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        }
        
        $editorPlugin = \fpcm\components\components::getArticleEditor();
        if (!$editorPlugin) {
            $this->view = new \fpcm\view\error('Error loading article editor component '.$this->config->system_editor);
            $this->view->render();
        }

        $this->view->addCssFiles($editorPlugin->getCssFiles());

        $viewVars = $editorPlugin->getViewVars();
        
        if (isset($viewVars['editorButtons']) && count($viewVars['editorButtons'])) {
            $viewVars['editorButtons']['frame']->setReturned(true);
            unset($viewVars['editorButtons']['frame']);
            $viewVars['editorButtons']['readmore']->setReturned(true);
            unset($viewVars['editorButtons']['readmore']);
            $viewVars['editorButtons']['drafts']->setReturned(true);
            unset($viewVars['editorButtons']['drafts']);
            $viewVars['editorButtons']['restore']->setReturned(true);
            unset($viewVars['editorButtons']['restore']);
        }
        
        foreach ($viewVars as $key => $value) {
            $this->view->assign($key, $value);
        }
        
        $this->view->addJsFiles(array_merge(['comments.js', 'comments_editor.js', 'editor_videolinks.js'], $editorPlugin->getJsFiles()));
        $this->view->addJsLangVars($editorPlugin->getJsLangVars());
        
        $jsVars = $editorPlugin->getJsVars();
        
        if (isset($jsVars['editorConfig']['plugins']) && isset($jsVars['editorConfig']['toolbar'])) {

            $jsVars['editorConfig']['plugins'] = str_replace([
                'autosave',
                'template',
                'fpcm_readmore',
            ], '', $jsVars['editorConfig']['plugins']);
            
            $jsVars['editorConfig']['toolbar'] = str_replace([
                'restoredraft',
                'template',
                'fpcm_readmore',
            ], '', $jsVars['editorConfig']['toolbar']);
            
            
            $jsVars['editorConfig']['custom_elements'] = '';
        }

        $this->view->addJsVars($jsVars);

        if ($this->comment->getChangeuser() && $this->comment->getChangetime()) {
            $changeUser = new \fpcm\model\users\author($this->comment->getChangeuser());

            $this->view->assign(
                    'changeInfo', $this->language->translate('COMMMENT_LASTCHANGE', array(
                        '{{username}}' => $changeUser->exists() ? $changeUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
                        '{{time}}' => date($this->config->system_dtmask, $this->comment->getChangetime())
            )));
        } else {
            $this->view->assign('changeInfo', $this->language->translate('GLOBAL_NOCHANGE'));
        }
        
        if ($mode === 1) {
            
            $buttons     = [];
            $buttons[]   = (new \fpcm\view\helper\saveButton('commentSave'));

            $article     = new \fpcm\model\articles\article($this->comment->getArticleid());
            $articleList = new \fpcm\model\articles\articlelist();
            $articleList->checkEditPermissions($article);

            if ($article->exists() && $article->getEditPermission()) {
                $buttons[] = (new \fpcm\view\helper\editButton('editArticle'))->setUrlbyObject($article)->setText('COMMENTS_EDITARTICLE');
            }
            
            $buttons[] = (new \fpcm\view\helper\openButton('commentfe'))->setUrlbyObject($this->comment)->setTarget('_blank');
            
            $this->view->addButtons($buttons);
        }

        $this->view->setFormAction($this->comment->getEditLink(), ['mode' => $mode], true);
        $this->view->assign('ipWhoisLink', substr($this->comment->getIpaddress(), -1) === '*' ? false : true);
        $this->view->assign('comment', $this->comment);
        $this->view->assign('commentsMode', $mode);
        $this->view->assign('canApprove', $this->approve);
        $this->view->assign('canPrivate', $this->private);
        $this->view->render();
        
        return true;
    }

}

?>
