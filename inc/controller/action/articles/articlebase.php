<?php
    /**
     * Article controller base
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlebase extends \fpcm\controller\abstracts\controller {

        use \fpcm\controller\traits\articles\editor;

        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\articles\article
         */
        protected $article;
        
        /**
         *
         * @var \fpcm\model\system\fileLib
         */
        protected $fileLib;

        /**
         *
         * @var array
         */
        protected $jsVars = [];

        /**
         *
         * @var string
         */
        protected $editorFile;
        
        /**
         *
         * @var \fpcm\model\categories\categoryList
         */
        protected $categoryList;
        
        /**
         *
         * @var \fpcm\model\abstracts\articleEditor
         */
        protected $editorPlugin;

        public function __construct() {
            parent::__construct();
            $this->categoryList = new \fpcm\model\categories\categoryList();
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->editorPlugin = $this->getEditorPlugin();            
            $this->view->setViewJsFiles($this->editorPlugin->getJsFiles());
            $this->view->setViewCssFiles($this->editorPlugin->getCssFiles());
            
            $viewVars = $this->editorPlugin->getViewVars();
            foreach ($viewVars as $key => $value) {
                $this->view->assign($key, $value);
            }

            $changeAuthor = $this->permissions->check(['article' => 'authors']);
            $this->view->assign('changeAuthor', $changeAuthor);
            if ($changeAuthor) {
                $userlist = new \fpcm\model\users\userList();
                $changeuserList = array($this->lang->translate('EDITOR_CHANGEAUTHOR') => '') + $userlist->getUsersNameList();
                $this->view->assign('changeuserList', $changeuserList);
            }

            $this->view->assign('editorFile', $this->editorPlugin->getEditorTemplate());
            $this->view->assign('article', $this->article);
            $this->view->assign('categories', $this->categoryList->getCategoriesCurrentUser());
            $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
            $this->view->assign('showArchiveStatus', true);
            $this->view->assign('showDraftStatus', true);
            $this->view->assign('isRevision', false);
            $this->view->assign('timesMode', false);
            $this->view->assign('userfields', $this->getUserFields());
            $this->view->setHelpLink('articles_editor');

            $twitter = new \fpcm\model\system\twitter();
            $twitterOk = $twitter->checkRequirements();
            
            $twitterReplacements = '';
            if ($twitterOk) {                
                $tweetTpl = new \fpcm\model\pubtemplates\tweet();
                $tags = $tweetTpl->getReplacementTranslations('TEMPLATE_ARTICLE_');

                $twitterReplacements = [];
                foreach ($tags as $tag => $descr) {
                    $twitterReplacements[] = $descr.': '.$tag;
                }

                
                $twitterReplacements = implode(' &bull; '.PHP_EOL.' ', $twitterReplacements);
            }

            $this->view->assign('twitterReplacements', $twitterReplacements);
            $this->view->assign('showTwitter', $twitterOk);
            
            $this->jsVars  = $this->editorPlugin->getJsVars();
            $this->jsVars += array(
                'fpcmFileManagerUrl'        => \fpcm\classes\baseconfig::$rootPath.'index.php?module=files/list&mode=',
                'fpcmFileManagerUrlMode'    => 2
            );
            
            $jsLangVars = array('fileManagerHeadline' => $this->lang->translate('HL_FILES_MNG'));
            $this->view->addJsLangVars(array_merge($jsLangVars, $this->editorPlugin->getJsLangVars()));
            
            $this->view->addJsVars($this->jsVars);
            
            return true;
        }
        
        /**
         * Liefert benutzerdefinierte Felder zurück, welche durch Module in Editor eingefügt werden können;
         * * möglich sind textarea, select, checkbox, radio, textinput
         * * nicht unterstütze Typen werden zu textinput
         * @return array
         */
        protected function getUserFields() {
            $fields = $this->events->runEvent('editorAddUserFields');
            
            if (!is_array($fields) || !count($fields)) return [];
            
            return $fields;
        }
    }
?>