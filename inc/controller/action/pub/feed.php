<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article RSS feed controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class feed extends \fpcm\controller\abstracts\pubController {

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
     * @var array
     */
    protected $emails = [];

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
     * Konstruktor
     */
    public function __construct()
    {
        $this->requestExit([
            'module'
        ]);

        parent::__construct();

        if ($this->config->articles_rss) {
            $this->articleList = new \fpcm\model\articles\articlelist();
            $this->userList = new \fpcm\model\users\userList();
            $this->template = new \fpcm\model\pubtemplates\latestnews();
        }
    }

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath(): string
    {
        return $this->config->articles_rss ? 'public/feed' : '';
    }

    /**
     * @see \fpcm\controller\abstracts\controller::request()
     * @return bool
     */
    public function request()
    {
        if (!$this->config->articles_rss) {
            exit($this->language->translate('RSSFEED_DISABLED'));
        }

        $this->category = defined('FPCM_PUB_CATEGORY_LATEST') ? FPCM_PUB_CATEGORY_LATEST : 0;
        $this->limit = defined('FPCM_PUB_LIMIT_LATEST') ? FPCM_PUB_LIMIT_LATEST : $this->config->articles_limit;
        $this->cacheName = \fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/articlefeed';

        return true;
    }

    /**
     * Controller ausführen
     * @return bool
     */
    public function process()
    {
        parent::process();

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);

        header('Content-type: text/html; charset=utf-8');
        $content = '';

        if ($this->cache->isExpired($this->cacheName) || $this->session->exists()) {
            $this->users = array_flip($this->userList->getUsersNameList());
            $this->emails = array_flip($this->userList->getUsersEmailList());

            /**
             * Feed-Basis mittel \DOMDocument-Klasse
             */
            $dom = new \DOMDocument('1.0', 'utf-8');

            $rss = $dom->createElement('rss');
            $rss->setAttribute('version', '2.0');
            $channel = $dom->createElement('channel');
            $rss->appendChild($channel);

            $title = $dom->createElement('title', 'FanPress CM RSS Feed');
            $channel->appendChild($title);
            $link = $dom->createElement('link', \fpcm\classes\tools::getFullControllerLink('fpcm/feed'));
            $channel->appendChild($link);
            $date = $dom->createElement('lastBuildDate', date(DATE_RSS, time()));
            $channel->appendChild($date);
            $gnrt = $dom->createElement('generator', 'FanPress CM News System ' . $this->config->system_version);
            $channel->appendChild($gnrt);
            $descr = $dom->createElement('description', 'FanPress CM News System RSS Feed');
            $channel->appendChild($descr);

            $conditions = new \fpcm\model\articles\search();
            $conditions->limit = [$this->limit, 0];
            $conditions->archived = 0;
            $conditions->postponed = 0;

            if ($this->category !== 0) {
                $conditions->category = $this->category;
            }

            $articles = $this->articleList->getArticlesByCondition($conditions);

            foreach ($articles as $article) {

                $item = $dom->createElement('item');

                $guid = $dom->createElement('guid', htmlspecialchars($article->getElementLink()));
                $item->appendChild($guid);

                $atitle = $dom->createElement('title', htmlspecialchars($article->getTitle()));
                $item->appendChild($atitle);

                $alink = $dom->createElement('link', htmlspecialchars($article->getElementLink()));
                $item->appendChild($alink);

                $adescr = $dom->createElement('description');

                $acont = $dom->createCDATASection($article->getContent());
                $adescr->appendChild($acont);
                $item->appendChild($adescr);

                $adate = $dom->createElement('pubDate', date(DATE_RSS, $article->getCreatetime()));
                $item->appendChild($adate);

                if (isset($this->users[$article->getCreateuser()])) {
                    $usremail = $this->emails[$article->getCreateuser()] . ' (' . $this->users[$article->getCreateuser()] . ')';
                    $aauthr = $dom->createElement('author', $usremail);
                    $item->appendChild($aauthr);
                }

                $channel->appendChild($item);
            }

            $dom->appendChild($rss);
            $dom = $this->events->trigger('pub\prepareRssFeed', $dom);

            $content .= $dom->saveXML();

            if (!$this->session->exists()) {
                $this->cache->write($this->cacheName, $content, $this->config->system_cache_timeout);
            }
        } else {
            $content .= $this->cache->read($this->cacheName);
        }

        $this->view->assign('content', $content);
        $this->view->render();
    }

}
