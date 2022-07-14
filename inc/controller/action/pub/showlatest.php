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
class showlatest extends \fpcm\controller\abstracts\pubController {

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\pubtemplates\latestnews
     */
    protected $template;

    /**
     *
     * @var array
     */
    protected $users = [];

    /**
     *
     * @var int
     */
    protected $category = 0;

    /**
     *
     * @var int
     */
    protected $limit = 0;

    /**
     * UTF8-Encoding aktiv
     * @var bool
     */
    protected $isUtf8 = true;

    /**
     * 
     * Konstruktor
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->apiMode = isset($params['apiMode']) ? $params['apiMode'] : false;
        $this->category = isset($params['category']) ? $params['category'] : 0;
        $this->isUtf8 = isset($params['isUtf8']) ? $params['isUtf8'] : true;

        parent::__construct();

        $this->limit = isset($params['count']) ? $params['count'] : $this->config->articles_limit;

        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->userList = new \fpcm\model\users\userList();
        $this->template = new \fpcm\model\pubtemplates\latestnews();
        $this->view->showHeaderFooter($this->apiMode ? \fpcm\view\view::INCLUDE_HEADER_NONE : \fpcm\view\view::INCLUDE_HEADER_SIMPLE);
    }

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'public/showlatest';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->maintenanceMode()) {
            return false;
        }

        if ($this->ipList->ipIsLocked()) {
            return false;
        }

        $this->cacheName = \fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/articlelatest';
        return true;
    }

    /**
     * Controller ausführen
     * @return bool
     */
    public function process()
    {
        parent::process();

        $parsed = [];

        if ($this->cache->isExpired($this->cacheName) || $this->session->exists()) {
            $this->users = array_flip($this->userList->getUsersNameList());

            $conditions = new \fpcm\model\articles\search();
            $conditions->limit = [$this->limit, 0];
            $conditions->archived = 0;
            $conditions->postponed = 0;

            if ($this->category !== 0) {
                $conditions->category = $this->category;
            }

            $articles = $this->articleList->getArticlesByCondition($conditions);
            $this->users = $this->userList->getUsersForArticles(array_keys($articles));

            foreach ($articles as $article) {
                $parsed[] = $this->assignData($article);
            }

            if (!$this->session->exists()) {
                $this->cache->write($this->cacheName, $parsed, $this->config->system_cache_timeout);
            }
        } else {
            $parsed = $this->cache->read($this->cacheName);
        }

        $content = implode(PHP_EOL, $parsed);
        if (!$this->isUtf8) {
            $content = utf8_decode($content);
        }

        $this->view->assign('content', $content);
        $this->view->assign('systemMode', $this->config->system_mode);
        $this->view->render();
    }

    /**
     * 
     * @param \fpcm\model\articles\article $article
     * @return string
     */
    protected function assignData(\fpcm\model\articles\article $article)
    {
        $this->template->assignByObject($article, isset($this->users[$article->getCreateuser()]) ? $this->users[$article->getCreateuser()] : false);
        return $this->template->parse();
    }

}
