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

    protected function getViewPath()
    {
        return 'comments/commentedit';
    }

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

    protected function getHelpLink()
    {
        return 'hl_comments_mng';
    }

    protected function getActiveNavigationElement()
    {
        return 'itemnav-item-editcomments';
    }

    public function request()
    {
        if ($this->permissions) {
            $this->approve = $this->permissions->check(array('comment' => 'approve'));
            $this->private = $this->permissions->check(array('comment' => 'private'));
        }

        if (is_null($this->getRequestVar('commentid'))) {
            $this->redirect('comments/list');
        }

        $this->comment = new \fpcm\model\comments\comment($this->getRequestVar('commentid'));

        if (!$this->comment->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_COMMENT', 'comments/list');
            return false;
        }

        $this->checkEditPermissions($this->comment);
        if (!$this->comment->getEditPermission()) {
            $this->view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            return false;
        }

        if ($this->buttonClicked('commentSave') && $this->getRequestVar('comment')) {
            $commentData = $this->getRequestVar('comment', array(4, 7));

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

            if ($this->comment->update()) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_COMMENT');
            } else {
                $this->view->addErrorMessage('SAVE_FAILED_COMMENT');
            }
        }

        return true;
    }

    public function process()
    {
        $mode = $this->getRequestVar('mode', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);

        if ($mode === 2) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        }

        $this->view->addJsFiles([\fpcm\classes\loader::libGetFileUrl('tinymce4/tinymce.min.js'), 'editor_tinymce.js']);

        $this->view->addJsVars([
            'editorConfig' => [
                'language' => $this->config->system_lang,
                'plugins' => 'autolink charmap code image link lists media nonbreaking wordcount fpcm_emoticons autoresize',
                'toolbar' => 'fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist blockquote | link unlink anchor image media emoticons charmap | undo redo removeformat searchreplace fullscreen code',
            ],
            'editorDefaultFontsize' => $this->config->system_editor_fontsize,
            'commentsEdit' => 1
        ]);

        if ($this->comment->getChangeuser() && $this->comment->getChangetime()) {
            $changeUser = new \fpcm\model\users\author($this->comment->getChangeuser());

            $this->view->assign(
                    'changeInfo', $this->lang->translate('COMMMENT_LASTCHANGE', array(
                        '{{username}}' => $changeUser->exists() ? $changeUser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                        '{{time}}' => date($this->config->system_dtmask, $this->comment->getChangetime())
            )));
        } else {
            $this->view->assign('changeInfo', $this->lang->translate('GLOBAL_NOCHANGE'));
        }
        
        if ($mode === 1) {
            $this->view->addButton((new \fpcm\view\helper\saveButton('commentSave')));
        }

        $this->view->setFormAction($this->comment->getEditLink(), ['mode' => $mode], true);
        $this->view->addJsFiles(['comments.js']);
        $this->view->assign('ipWhoisLink', substr($this->comment->getIpaddress(), -1) === '*' ? false : true);
        $this->view->assign('comment', $this->comment);
        $this->view->assign('commentsMode', $mode);
        $this->view->assign('canApprove', $this->approve);
        $this->view->assign('canPrivate', $this->private);
        $this->view->render();
    }

}

?>
