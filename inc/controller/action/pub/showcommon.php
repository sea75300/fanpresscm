<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
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
     * aktuelle Seite
     * @var int
     */
    protected $page = 0;

    /**
     * Benutzer-Array
     * @var array
     */
    protected $users = [];

    /**
     * Benutzer-Array
     * @var array
     */
    protected $usersEmails = [];

    /**
     * Kategorien-Array
     * @var array
     */
    protected $categories = [];

    /**
     * Kommentare pro Artikel
     * @var array
     */
    protected $commentCounts = [];

    /**
     * Aktueller Listen-Offset
     * @var int
     */
    protected $offset = 0;

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
     * Template to use
     * @var bool
     */
    protected $templateString = false;

    /**
     * 
     * Konstruktor
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->apiMode = isset($params['apiMode']) ? (bool) $params['apiMode'] : false;
        $this->category = isset($params['category']) ? $params['category'] : 0;
        $this->isUtf8 = isset($params['isUtf8']) ? (bool) $params['isUtf8'] : true;
        $this->templateString = isset($params['template']) && trim($params['template']) ? $params['template'] : false;

        parent::__construct();
        $this->limit = isset($params['count']) ? (int) $params['count'] : $this->config->articles_limit;
        $this->view->showHeaderFooter($this->apiMode ? \fpcm\view\view::INCLUDE_HEADER_NONE : \fpcm\view\view::INCLUDE_HEADER_SIMPLE);
    }

    /**
     * 
     * @return boolean
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
     * @return boolean
     */
    public function request()
    {
        $this->crons->registerCron('postponedArticles');

        $this->page = $this->getRequestVar('page', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if ($this->page === null) {
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
     * @return boolean
     */
    public function process()
    {
        parent::process();
        if ($this->cache->isExpired($this->cacheName) || $this->session->exists()) {
            $this->categories = $this->categoryList->getCategoriesAll();
            $this->commentCounts = ($this->config->system_comments_enabled) ? $this->commentList->countComments([], 0, 1) : [];
        }
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
            'author' => isset($this->users[$article->getCreateuser()]) ? $this->users[$article->getCreateuser()] : false,
            'changeUser' => isset($this->users[$article->getChangeuser()]) ? $this->users[$article->getChangeuser()] : false
                ], $this->categoryList->assignPublic($article), isset($this->commentCounts[$article->getId()]) ? $this->commentCounts[$article->getId()] : 0
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

        return '<ul class="fpcm-pub-pagination">' . PHP_EOL . implode(PHP_EOL, $pages) . PHP_EOL . '</ul>';
    }

    /**
     * 
     * @return string
     */
    protected function getCacheNameString() : string
    {
        return '';
    }

}

?>