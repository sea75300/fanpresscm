<?php
    /**
     * Public article list controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\pub;
    
    class showcommon extends \fpcm\controller\abstracts\pubController {

        /**
         * Artikel-Listen-Objekt
         * @var \fpcm\model\articles\articlelist
         */
        protected $articleList;
        
        /**
         * Kommentarlisten-Objekt
         * @var \fpcm\model\comments\commentList
         */
        protected $commentList;
        
        /**
         *
         * @var \fpcm\model\categories\categoryList
         */
        protected $categoryList;
        
        /**
         * Benutzerlisten-Objekt
         * @var \fpcm\model\users\userList
         */
        protected $userList;

        /**
         * Template-Objekt
         * @var \fpcm\model\pubtemplates\article
         */
        protected $template;
        
        /**
         * IP-Sperren-List-Objekt
         * @var \fpcm\model\ips\iplist
         */
        protected $iplist;

        /**
         * aktuelle Seite
         * @var int
         */
        protected $page = 0;

        /**
         * Benutzer-Array
         * @var array
         */
        protected $users         = [];

        /**
         * Benutzer-Array
         * @var array
         */
        protected $usersEmails   = [];

        /**
         * Kategorien-Array
         * @var array
         */
        protected $categories    = [];

        /**
         * Kommentare pro Artikel
         * @var array
         */
        protected $commentCounts = [];
        
        /**
         * Aktueller Listen-Offset
         * @var int
         */
        protected $listShowLimit = 0;
        
        /**
         * APi-Modus
         * @var bool
         */
        protected $apiMode = false;
        
        /**
         * Kategorie-Einschränung
         * @var int
         */
        protected $category = 0;
        
        /**
         * Limit Artikel pro Seite
         * @var int
         */
        protected $limit = 0;
        
        /**
         * UTF8-Encoding aktiv
         * @var bool
         */
        protected $isUtf8 = true;

        /**
         * Konstruktor
         */
        public function __construct() {

            parent::__construct();
            
            $this->articleList  = new \fpcm\model\articles\articlelist();
            $this->commentList  = new \fpcm\model\comments\commentList();
            $this->categoryList = new \fpcm\model\categories\categoryList();
            $this->userList     = new \fpcm\model\users\userList();
            $this->template     = new \fpcm\model\pubtemplates\article($this->config->articles_template_active);
            $this->iplist       = new \fpcm\model\ips\iplist();
        }

        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {
            
            if ($this->iplist->ipIsLocked()) {
                $this->view->addErrorMessage('ERROR_IP_LOCKED');
                $this->view->assign('systemMode', $this->config->system_mode);
                $this->view->assign('content', '');
                $this->view->assign('showToolbars', false);
                $this->view->render();
                return false;
            }            
            
            $this->crons->registerCron('postponedArticles');
            
            $this->page     = !is_null($this->getRequestVar('page')) ? (int) $this->getRequestVar('page') : 0;
            $this->category = defined('FPCM_PUB_CATEGORY_LISTALL') ? FPCM_PUB_CATEGORY_LISTALL : 0;
            $this->isUtf8   = defined('FPCM_PUB_OUTPUT_UTF8') ? FPCM_PUB_OUTPUT_UTF8 : true;
            
            if ($this->page > 1) $this->listShowLimit = ($this->page - 1) * $this->limit;
            
            return true;
        }
        
        /**
         * Controller ausführen
         * @return boolean
         */
        public function process() {
            parent::process();
            
            if ($this->cache->isExpired() || $this->session->exists()) { 
                $this->categories    = $this->categoryList->getCategoriesAll();
                $this->commentCounts = ($this->config->system_comments_enabled) ? $this->commentList->countComments([], 0, 1) : [];
            }
        }
        
        /**
         * 
         * @param \fpcm\model\articles\article $article
         * @return string
         */
        protected function assignData(\fpcm\model\articles\article $article) {
            /**
             * @var \fpcm\model\pubtemplates\article
             */
            $tpl = $this->template;

            $categoryTexts = [];
            $categoryIcons = [];

            foreach ($article->getCategories() as $categoryId) {

                /**
                 * @var \fpcm\model\categories\category
                 */
                $category = isset($this->categories[$categoryId]) ? $this->categories[$categoryId] : false;

                if (!$category) continue;

                $categoryTexts[] = '<span class="fpcm-pub-category-text">'.$category->getName().'</span>';

                if (!$category->getIconPath()) continue;
                $categoryIcons[] = $category->getCategoryImage();
            }

            $shareButtonParser = new \fpcm\model\pubtemplates\sharebuttons($article->getArticleLink(), $article->getTitle());
            
            $commentCount = $this->config->system_comments_enabled && $article->getComments() ? (isset($this->commentCounts[$article->getId()]) ? (int) $this->commentCounts[$article->getId()] : 0) : '';
            
            $tpl->setCommentsEnabled($this->config->system_comments_enabled && $article->getComments());
            
            $cuser  = isset($this->users[$article->getCreateuser()]) ? $this->users[$article->getCreateuser()] : false;
            $chuser = isset($this->users[$article->getChangeuser()]) ? $this->users[$article->getChangeuser()] : false;

            $emailAddress   = $cuser
                            ? '<a href="mailto:'.$cuser->getEmail().'">'.$cuser->getDisplayname().'</a>'
                            : '';

            $replacements = array(
                '{{headline}}'                      => $article->getTitle(),
                '{{text}}'                          => $article->getContent(),
                '{{author}}'                        => $cuser ? $cuser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                '{{authorEmail}}'                   => $emailAddress,
                '{{authorAvatar}}'                  => $cuser ? \fpcm\model\users\author::getAuthorImageDataOrPath($cuser, 0) : '',
                '{{authorInfoText}}'                => $cuser ? $cuser->getUsrinfo() : '',
                '{{date}}'                          => date($this->config->system_dtmask, $article->getCreatetime()),
                '{{changeDate}}'                    => date($this->config->system_dtmask, $article->getChangetime()),
                '{{changeUser}}'                    => $chuser ? $chuser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                '{{statusPinned}}'                  => $article->getPinned() ? $this->lang->translate('PUBLIC_ARTICLE_PINNED') : '',
                '{{shareButtons}}'                  => $shareButtonParser->parse(),
                '{{categoryIcons}}'                 => implode(PHP_EOL, $categoryIcons),
                '{{categoryTexts}}'                 => implode(PHP_EOL, $categoryTexts),
                '{{commentCount}}'                  => $commentCount,
                '{{permaLink}}:{{/permaLink}}'      => $article->getArticleLink(),
                '{{commentLink}}:{{/commentLink}}'  => $article->getArticleLink().'#comments',
                '<readmore>:</readmore>'            => $article->getMd5path(),
                '{{articleImage}}'                  => $article->getArticleImage(),
                '{{sources}}'                       => $article->getSources()
            );

            $tpl->setReplacementTags($replacements);
            
            $parsed = $tpl->parse();
            
            if ($this->session->exists()) {
                $html   = [];
                $html[] = '<div class="fpcm-pub-articletoolbar-article fpcm-pub-articletoolbar-article'.$article->getId().'">';
                $html[] = '<a href="'.$article->getEditLink().'">'.$this->lang->translate('HL_ARTICLE_EDIT').'</a>';
                $html[] = '</div>';
                
                $parsed = implode(PHP_EOL, $html).$parsed;
            }            
            
            return $parsed;
        }
        
        /**
         * Seitennavigation erzeugen
         * @param int $count
         * @param string $action
         * @return string
         */
        protected function createPagination($count, $action = 'fpcm/list') {

            $pageCount = ceil($count / $this->limit);
            
            if (!$pageCount) return '<ul></ul>';
            
            $pages = array_fill(1, $pageCount, '');

            if (count($pages) < 2) return '<ul></ul>';
            
            foreach ($pages as $key => &$value) {
                
                $class = 'fpcm-pub-pagination-page';
                if ($key == $this->page || ($key == 1 && !$this->page)) $class .= ' fpcm-pub-pagination-page-active';

                $page = $this->apiMode && $key < 2 ? $this->config->system_url : '?module='.$action;

                $page .= $key >= 2 ? '&amp;page='.$key : '';
                
                $value .= '<li><a href="'.$page.'" class="'.$class.'">'.$key.'</a></li>';
            }
            
            if ($this->page > 1) {
                $prevPage = $this->page <= 2 ? '' : '&amp;page='.($this->page - 1);
                array_unshift($pages, '<li><a href="?module='.$action.$prevPage.'" class="fpcm-pub-pagination-next">&larr;</a></li>');
            }
            
            if ($this->page < $pageCount) {
                $nextPage = $this->page < 2 ? 2 : $this->page + 1;
                array_push($pages, '<li><a href="?module='.$action.'&amp;page='.$nextPage.'" class="fpcm-pub-pagination-prev">&rarr;</a></li>');
            }
            
            return '<ul class="fpcm-pub-pagination">'.PHP_EOL.implode(PHP_EOL, $pages).PHP_EOL.'</ul>';

        }

    }
?>