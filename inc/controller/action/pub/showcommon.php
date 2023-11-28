<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class showcommon extends \fpcm\controller\abstracts\pubController {

    use \fpcm\controller\traits\pub\apiMode;
    
    /**
     * Articles list instance
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     * Comment list instance
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     * Category list instance
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     * User list instance
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     * Template instance
     * @var \fpcm\model\pubtemplates\article
     */
    protected $template;

    /**
     * Current page
     * @var int
     */
    protected $page = 0;

    /**
     * user list
     * @var array
     */
    protected $users = [];

    /**
     * Comment count by articles
     * @var array
     */
    protected $commentCounts = [];

    /**
     * Current offset by page
     * @var int
     */
    protected $offset = 0;

    /**
     * Search term
     * @var string
     * @since 4.5-b5
     */
    protected $search = '';

    /**
     * Limit to category
     * @var int
     */
    protected $category = 0;

    /**
     * Limit of articles per page
     * @var int
     */
    protected $limit = 0;

    /**
     * Use UTF-8 encoding
     * @var bool
     */
    protected $isUtf8 = true;

    /**
     * Template to use
     * @var bool
     */
    protected $templateString = false;

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'public/showall';
    }

    /**
     * 
     * Konstruktor
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->requestExit([
            'module'
        ]);

        if (isset($params['isUtf8'])) {
            trigger_error('isUtf8 is deprecated and will be removed in FanPress CM 5.2.', E_USER_DEPRECATED);
        }

        $this->apiMode = (bool) ($params['apiMode'] ?? false);
        $this->category = $params['category'] ?? 0;
        $this->search = empty($params['search']) ? '' : addslashes(strip_tags(htmlspecialchars($params['search'])));
        $this->isUtf8 = (bool) ($params['isUtf8'] ?? true);
        $this->templateString = isset($params['template']) && trim($params['template']) ? $params['template'] : false;

        parent::__construct();
        $this->limit = (int) ($params['count'] ?? $this->config->articles_limit);
        $this->view->showHeaderFooter($this->apiMode ? \fpcm\view\view::INCLUDE_HEADER_NONE : \fpcm\view\view::INCLUDE_HEADER_SIMPLE);
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->commentList = new \fpcm\model\comments\commentList();
        $this->categoryList = new \fpcm\model\categories\categoryList();
        $this->template = new \fpcm\model\pubtemplates\article($this->templateString ? $this->templateString : $this->config->articles_template_active);
        $this->userList = \fpcm\classes\loader::getObject('\fpcm\model\users\userList');
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->page = $this->request->getPage();
        if (!$this->page) {
            $this->page = 1;
        }

        $this->cacheName = \fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/'.$this->getCacheNameString() . $this->page;

        if ($this->page < 2) {
            $this->offset = 0;
            return true;
        }

        $this->offset = ($this->page-1) * $this->limit;
        return true;
    }

    /**
     * Controller ausführen
     * @return bool
     */
    public function process()
    {
        parent::process();
        $this->view->addJsLangVars(['PUBLIC_SHARE_LIKE', 'AJAX_RESPONSE_ERROR']);
        $content = implode(PHP_EOL, $this->parseArticles());

        $this->view->assign('content', $this->isUtf8 ? $content : utf8_decode($content));
        $this->view->assign('isArchive', $this->isArchive());
        $this->view->assign('archievDate', $this->config->articles_archive_datelimit);
        $this->view->render();
    }

    /**
     * Final article parsing
     * @return array
     * @since 4.5-b5
     */
    private function parseArticles() : array
    {
        if ($this->session instanceof \fpcm\model\system\session && $this->session->exists() || trim($this->search)) {
            $this->initCommentCounts();
            return $this->getContentData();
        }
        
        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }
        
        $this->initCommentCounts();
        $parsed = $this->getContentData();
        $this->cache->write($this->cacheName, $parsed, $this->config->system_cache_timeout);
        return $parsed;
    }

    /**
     * Init comment counts
     * @return bool
     * @since 4.5-b5
     */
    private function initCommentCounts() : bool
    {
        if (!$this->config->system_comments_enabled) {
            $this->commentCounts = [];
            return false;
        }

        $this->commentCounts = $this->commentList->countComments([], 0, 1);
        return true;
    }

    /**
     * 
     * @param \fpcm\model\articles\article $article
     * @return string
     */
    protected function assignData(\fpcm\model\articles\article $article)
    {
        $this->template->setCommentsEnabled($this->config->system_comments_enabled && $article->getComments());
        $this->template->assignByObject(
                $article, [
            'author' => $this->users[$article->getCreateuser()] ?? false,
            'changeUser' => $this->users[$article->getChangeuser()] ?? false
                ], $this->categoryList->assignPublic($article), $this->commentCounts[$article->getId()] ?? 0
        );

        $parsed = $this->template->parse();

        if ($this->session->exists()) {
            $html = [];
            $html[] = '<div class="fpcm-pub-articletoolbar-article fpcm-pub-articletoolbar-article' . $article->getId() . '">';
            $html[] = '<a href="' . $article->getEditLink() . '">' . $this->language->translate('HL_ARTICLE_EDIT') . '</a>';
            $html[] = '</div>';

            $parsed = implode(PHP_EOL, $html) . $parsed;
        }

        return $parsed;
    }

    /**
     * Seitennavigation erzeugen
     * @param int $count
     * @param string $action
     * @return string
     */
    protected function createPagination($count, $action = 'fpcm/list')
    {
        $pageCount = ceil($count / $this->limit);
        if (!$pageCount) {
            return '<ul></ul>';
        }

        $pages = array_fill(1, $pageCount, '');

        if (count($pages) < 2) {
            return '<ul></ul>';
        }

        foreach ($pages as $key => &$value) {

            $class = 'fpcm-pub-pagination-page';
            if ($key == $this->page || ($key == 1 && !$this->page))
                $class .= ' fpcm-pub-pagination-page-active';

            $page = $this->apiMode && $key < 2 ? $this->config->system_url : '?module=' . $action;

            $page .= $key >= 2 ? '&amp;page=' . $key : '';

            $value .= '<li><a href="' . $page . '" class="' . $class . '">' . $key . '</a></li>';
        }

        if ($this->page > 1) {
            $prevPage = $this->page <= 2 ? '' : '&amp;page=' . ($this->page - 1);
            array_unshift($pages, '<li><a href="?module=' . $action . $prevPage . '" class="fpcm-pub-pagination-next">&larr;</a></li>');
        }

        if ($this->page < $pageCount) {
            $nextPage = $this->page < 2 ? 2 : $this->page + 1;
            array_push($pages, '<li><a href="?module=' . $action . '&amp;page=' . $nextPage . '" class="fpcm-pub-pagination-prev">&rarr;</a></li>');
        }
        
        if ($this->page > $pageCount) {
            $this->view->addErrorMessage('LOAD_FAILED_ARTICLE_LIST');
        }

        return '<ul class="fpcm-pub-pagination">' . PHP_EOL . implode(PHP_EOL, $pages) . PHP_EOL . '</ul>';
    }

    /**
     * 
     * @return string
     */
    abstract protected function getCacheNameString() : string;

    abstract protected function isArchive() : bool;

    abstract protected function getContentData() : array;

    abstract protected function assignConditions(\fpcm\model\articles\search &$conditions) : bool;

}
