<?php

/**
 * Public article list controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

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
     * Konstruktor
     * @param bool $apiMode API-Modus
     */
    public function __construct($apiMode = false)
    {

        $this->apiMode = $apiMode;

        parent::__construct();

        $this->articleList  = new \fpcm\model\articles\articlelist();
        $this->userList     = new \fpcm\model\users\userList();
        $this->template     = new \fpcm\model\pubtemplates\latestnews();
        $this->view->showHeaderFooter($this->apiMode ? \fpcm\view\view::INCLUDE_HEADER_NONE : \fpcm\view\view::INCLUDE_HEADER_SIMPLE);
    }

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath()
    {
        return 'public/showlatest';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        if (!$this->maintenanceMode()) {
            return false;
        }

        if ($this->ipList->ipIsLocked()) {
            return false;
        }

        $this->category = defined('FPCM_PUB_CATEGORY_LATEST') ? FPCM_PUB_CATEGORY_LATEST : 0;
        $this->limit = defined('FPCM_PUB_LIMIT_LATEST') ? FPCM_PUB_LIMIT_LATEST : $this->config->articles_limit;
        $this->isUtf8 = defined('FPCM_PUB_OUTPUT_UTF8') ? FPCM_PUB_OUTPUT_UTF8 : true;
        $this->cacheName = \fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/articlelatest';

        return true;
    }

    /**
     * Controller ausführen
     * @return boolean
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
        /**
         * @var \fpcm\model\pubtemplates\article
         */
        $tpl = $this->template;

        $replacements = array(
            '{{headline}}' => $article->getTitle(),
            '{{author}}' => isset($this->users[$article->getCreateuser()]) ? $this->users[$article->getCreateuser()]->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
            '{{date}}' => date($this->config->system_dtmask, $article->getCreatetime()),
            '{{permaLink}}:{{/permaLink}}' => $article->getElementLink(),
            '{{commentLink}}:{{/commentLink}}' => $article->getElementLink() . '#comments'
        );

        $tpl->setReplacementTags($replacements);

        return $tpl->parse();
    }

}

?>