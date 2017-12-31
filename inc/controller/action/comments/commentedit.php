<?php
    /**
     * Comment edit controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\comments;
    
    class commentedit extends \fpcm\controller\abstracts\controller {

        use \fpcm\model\comments\permissions;

        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

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

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => array('editall', 'edit'), 'comment' => array('editall', 'edit'));
            
            if ($this->permissions) {
                $this->approve = $this->permissions->check(array('comment' => 'approve'));
                $this->private = $this->permissions->check(array('comment' => 'private'));                
            }
            
            
            $this->view     = new \fpcm\model\view\acp('commentedit', 'comments');            
        }

        public function request() {
            if (is_null($this->getRequestVar('commentid'))) {
                $this->redirect('comments/list');
            }
            
            $this->comment = new \fpcm\model\comments\comment($this->getRequestVar('commentid'));
            
            if (!$this->comment->exists()) {
                $this->view->setNotFound('LOAD_FAILED_COMMENT', 'comments/list');
                return true;
            }

            $this->checkEditPermissions($this->comment);
            if (!$this->comment->getEditPermission()) {
                $this->view = new \fpcm\model\view\error();
                $this->view->setMessage($this->lang->translate('PERMISSIONS_REQUIRED'));
                $this->view->render();
                return false;
            }

            if ($this->buttonClicked('commentSave') && $this->getRequestVar('comment')) {
                $commentData = $this->getRequestVar('comment', array(4,7));
                
                $this->comment->setText($commentData['text']);
                unset($commentData['text']);
                
                foreach ($commentData as &$value) {
                    $value = \fpcm\classes\http::filter($value, array(1,3));
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
        
        public function process() {
            if (!parent::process()) return false;

            $mode = (int) $this->getRequestVar('mode');
            
            if ($mode == 2) {
                $this->view->setShowHeader(0);
                $this->view->setShowFooter(0);
            }
            
            $this->view->setViewJsFiles([
                \fpcm\classes\loader::libGetFileUrl('tinymce4', 'tinymce.min.js'),
                'editor_tinymce.js'
            ]);
            $this->view->addJsVars([
                'fpcmTinyMceLang'               => $this->config->system_lang,
                'fpcmTinyMceDefaultFontsize'    => $this->config->system_editor_fontsize,
                'fpcmTinyMcePlugins'            => 'autolink charmap code image link lists media nonbreaking wordcount fpcm_emoticons autoresize',
                'fpcmTinyMceToolbar'            => 'fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist blockquote | link unlink anchor image media emoticons charmap | undo redo removeformat searchreplace fullscreen code',
                'fpcmNavigationActiveItemId'    => 'itemnav-item-editcomments',
                'fpcmCommentsEdit'              => 1
            ]);
            
            if ($this->comment->getChangeuser() && $this->comment->getChangetime()) {
                $changeUser = new \fpcm\model\users\author($this->comment->getChangeuser());

                $this->view->assign(
                    'changeInfo',
                    $this->lang->translate('COMMMENT_LASTCHANGE', array(
                        '{{username}}' => $changeUser->exists() ? $changeUser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                        '{{time}}'     => date($this->config->system_dtmask, $this->comment->getChangetime())
                )));                
            } else {
                $this->view->assign('changeInfo', $this->lang->translate('GLOBAL_NOCHANGE'));
            }

            $this->view->setViewJsFiles(['comments.js']);
            $this->view->assign('ipWhoisLink', substr($this->comment->getIpaddress(), -1) === '*' ? false : true);
            $this->view->assign('comment', $this->comment);
            $this->view->assign('commentsMode', $mode);
            $this->view->assign('permApprove', $this->approve);
            $this->view->assign('permPrivate', $this->private);
            $this->view->setHelpLink('hl_comments_mng');
            $this->view->render();
        }

    }
?>
