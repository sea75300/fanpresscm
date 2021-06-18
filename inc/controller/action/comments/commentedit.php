<?php

/**
 * Comment edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\comments;

class commentedit extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\model\comments\permissions;

    /**
     *
     * @var \fpcm\model\comments\comment
     */
    protected $comment;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var array
     */
    protected $ownArticleIds = false;

    /**
     *
     * @var int
     */
    protected $mode = 1;

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
    protected function getViewPath() : string
    {
        return 'comments/commentedit';
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
     * @return bool
     */
    public function request()
    {
        $this->mode = $this->request->getIntMode();

        $id = $this->request->getID();
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

        $this->save();
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        if ($this->mode === 2) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
            $this->view->setBodyClass('fpcm-ui-hide-toolbar');
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
            $viewVars['editorButtons']['pagebreak']->setReturned(true);
            unset($viewVars['editorButtons']['pagebreak']);
            $viewVars['editorButtons']['drafts']->setReturned(true);
            unset($viewVars['editorButtons']['drafts']);
            $viewVars['editorButtons']['restore']->setReturned(true);
            unset($viewVars['editorButtons']['restore']);
        }
        
        foreach ($viewVars as $key => $value) {
            $this->view->assign($key, $value);
        }
        
        
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

        $jsVars += array(
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
            'filemanagerMode' => 2
        );

        $this->view->addJsVars($jsVars);
        $this->view->addJsFiles(array_merge(['comments/module.js', 'comments/editor.js', 'editor/editor_videolinks.js'], $editorPlugin->getJsFiles()));
        $this->view->addJsLangVars(array_merge(['HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS', 'GLOBAL_DELETE'], $editorPlugin->getJsLangVars()));
        

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

        $hiddenClass = $this->mode === 2 ? 'fpcm-ui-hidden' : '';
        
        $buttons     = [];
        $buttons[]   = (new \fpcm\view\helper\saveButton('commentSave'))->setClass($hiddenClass)->setPrimary();
        
        if ($this->mode === 1) {
            $article     = new \fpcm\model\articles\article($this->comment->getArticleid());
            $articleList = new \fpcm\model\articles\articlelist();
            $articleList->checkEditPermissions($article);

            if ($article->exists()) {

                if ($article->getEditPermission()) {
                    $buttons[] = (new \fpcm\view\helper\editButton('editArticle'))->setUrlbyObject($article)->setText('COMMENTS_EDITARTICLE');
                }

                $buttons[] = (new \fpcm\view\helper\openButton('commentfe'))->setUrlbyObject($this->comment)->setTarget('_blank');           
            }
            else {
                $this->view->addErrorMessage('LOAD_FAILED_COMMENT_ARTICLE');
            }
        }

        $buttons[] = (new \fpcm\view\helper\linkButton('whoisIp'))->setUrl("http://www.whois.com/whois/{$this->comment->getIpaddress()}")->setTarget('_blank')->setText('Whois')->setIcon('home')->setClass($hiddenClass)->setRel('noreferrer,noopener,external');

        if ($this->permissions->comment->lockip) {
            $buttons[] = (new \fpcm\view\helper\button('lockIp'))->setText('COMMMENT_LOCKIP')->setIcon('globe')->setClass($hiddenClass)->setData([
                'commentid' => $this->comment->getId()
            ]);
        }


        $this->view->addButtons($buttons);

        $this->view->setFormAction($this->comment->getEditLink(), ['mode' => $this->mode], true);
        $this->view->assign('comment', $this->comment);
        $this->view->assign('commentsMode', $this->mode);
        $this->view->render();
        
        return true;
    }
    
    private function save() : bool
    {
        if (!$this->buttonClicked('commentSave')) {
            return false;
        }
        
        
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }
        
        $commentData = $this->request->fromPOST('comment', [
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM
        ]);

        if (!is_array($commentData) || !count($commentData)) {
            $this->view->addErrorMessage('SAVE_FAILED_COMMENT');
            return false;
        }
        
        $commentData['email'] = filter_var($commentData['email'], FILTER_VALIDATE_EMAIL);
        if ($this->config->comments_email_optional && !$commentData['email']) {
            $this->view->addErrorMessage('PUBLIC_FAILED_EMAIL');
            return true;
        }

        $this->comment->setText($commentData['text']);
        unset($commentData['text']);

        foreach ($commentData as &$value) {
            $value = $this->request->filter($value, [\fpcm\model\http\request::FILTER_STRIPTAGS, \fpcm\model\http\request::FILTER_HTMLENTITIES]);
        }

        $this->comment->setName($commentData['name']);
        $this->comment->setEmail($commentData['email']);
        $this->comment->setWebsite(filter_var($commentData['website'], FILTER_SANITIZE_URL));

        if ($this->permissions->comment->approve) {
            $this->comment->setApproved(isset($commentData['approved']) ? true : false);
            $this->comment->setSpammer(isset($commentData['spam']) ? true : false);
        }

        if ($this->permissions->comment->private) {
            $this->comment->setPrivate(isset($commentData['private']) ? true : false);
        }

        $this->comment->setChangetime(time());
        $this->comment->setChangeuser($this->session->getUserId());

        $this->view->addJsVars([ 'reloadList' => $this->mode === 2 ? true : false ]);

        if ($this->comment->update()) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_COMMENT');
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_COMMENT');
    }

}

?>
