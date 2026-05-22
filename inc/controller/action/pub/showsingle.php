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
class showsingle extends \fpcm\controller\abstracts\pubController {

    use \fpcm\controller\traits\pub\apiMode;

    /**
     *
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    protected $article;

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     *
     * @var \fpcm\model\pubtemplates\article
     */
    protected $articleTemplate;

    /**
     *
     * @var \fpcm\model\pubtemplates\comment
     */
    protected $commentTemplate;

    /**
     *
     * @var \fpcm\model\pubtemplates\commentform
     */
    protected $commentFormTemplate;

    /**
     *
     * @var \fpcm\model\comments\comment
     */
    protected $newComment;

    /**
     *
     * @var \fpcm\model\abstracts\spamCaptcha
     */
    protected $captcha;

    /**
     *
     * @var int
     */
    protected $articleId = 0;

    /**
     * Article template to use
     * @var bool
     */
    protected $templateString = '';

    /**
     * Article template to use
     * @var bool
     */
    protected $viewVars = [];

    /**
     *
     * Konstruktor
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->requestExit([
            'id',
            'module'
        ]);

        $this->templateString = isset($params['template']) && trim($params['template']) ? $params['template'] : false;
        $this->apiMode = isset($params['apiMode']) ? (bool) $params['apiMode'] : false;

        parent::__construct();

        $this->viewVars = [
            'article' => '',
            'comments' => '',
            'commentform' => '',
        ];

        $this->view->showHeaderFooter($this->apiMode ? \fpcm\view\view::INCLUDE_HEADER_NONE : \fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->commentList = new \fpcm\model\comments\commentList();
        $this->categoryList = new \fpcm\model\categories\categoryList();
        $this->userList = new \fpcm\model\users\userList();
    }

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath(): string
    {
        return 'public/showsingle';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->articleId = $this->request->fromGET('id');
        if (!$this->articleId) {
            $this->view->addErrorMessage('LOAD_FAILED_ARTICLE');
            return true;
        }

        $srcData = explode('-', $this->articleId, 2);
        $this->articleId = (int) $srcData[0];

        $this->article = new \fpcm\model\articles\article($this->articleId);

        if (!$this->article->publicIsVisible()) {
            $this->view->addErrorMessage('LOAD_FAILED_ARTICLE');
            $this->article = false;
            return true;
        }

        $this->cacheName = \fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/' . \fpcm\model\articles\article::CACHE_ARTICLE_SINGLE . $this->articleId;
        $this->articleTemplate = new \fpcm\model\pubtemplates\article($this->templateString ? $this->templateString : $this->config->article_template_active);

        $this->initComments();

        return true;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        parent::process();
        if (!$this->article) {
            $this->view->setViewVars(array_merge($this->viewVars, $this->view->getViewVars()));
            $this->view->render();
            return;
        }

        $parsed = array('articles' => '', 'comments' => '');
        if ($this->cache->isExpired($this->cacheName) || $this->session->exists()) {
            
            $this->assignCommentsData();
            
            $parsed['comments'] = '';
            $parsed['articles'] = $this->assignArticleData();

            $ev = $this->events->trigger('pub\showSingle', $parsed);
            if (!$ev->getSuccessed() || !$ev->getContinue()) {
                trigger_error(sprintf("Event pub\showSingle failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
                return $parsed;
            }

            $parsed = $ev->getData();

            if (!$this->session->exists()) {
                $this->cache->write($this->cacheName, $parsed, $this->config->system_cache_timeout);
            }
        } else {
            $parsed = $this->cache->read($this->cacheName);
        }

        $this->viewVars['article'] = $parsed['articles'];
        if ($this->config->system_comments_enabled && $this->article->getComments() && !$this->ipList->ipIsLocked('nocomments')) {
            $this->viewVars['comments'] = $parsed['comments'];
            $this->viewVars['commentform'] = $this->assignCommentFormData();
        }

        $this->view->addJsLangVars(['PUBLIC_SHARE_LIKE', 'AJAX_RESPONSE_ERROR', 'GLOBAL_PLEASEWAIT']);
        $this->view->setViewVars(array_merge($this->viewVars, $this->view->getViewVars()));
        $this->view->render();
    }

    /**
     * Artikel parsen
     * @return string
     */
    protected function assignArticleData()
    {
        $users = $this->userList->getUsersByIds([
            $this->article->getCreateuser(),
            $this->article->getChangeuser()
        ]);

        $this->articleTemplate->assignByObject(
            $this->article, [
                'author' => isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()] : false,
                'changeUser' => isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()] : false
            ],
            $this->categoryList->assignPublic($this->article),
            $this->commentCount
        );

        $this->articleTemplate->setCommentsEnabled($this->config->system_comments_enabled && $this->article->getComments());
        $parsed = $this->articleTemplate->parse();

        if ($this->session->exists()) {
            $html = [];
            $html[] = '<div class="fpcm-pub-articletoolbar-article fpcm-pub-articletoolbar-article' . $this->articleId . '">';
            $html[] = '<a href="' . $this->article->getEditLink() . '">' . $this->language->translate('HL_ARTICLE_EDIT') . '</a>';
            $html[] = '</div>';

            $parsed = implode(PHP_EOL, $html) . $parsed;
        }

        return $parsed;
    }

    /**
     * Kommentare parsen
     * @return void
     */
    protected function assignCommentsData()
    {
        if (!$this->config->system_comments_enabled || !$this->article->getComments()) {
            return;
        }

        $approved = $this->session->exists() ? null : 1;
        $private = $this->session->exists() ? null : 0;
        $spam = $this->session->exists() ? null : 0;

        $res = $this->commentList->countComments([ $this->articleId ], $private, $approved, $spam, false);
        $this->commentCount = $res[$this->articleId] ?? 0;
        
        $this->view->addJsVars(['commentsCount' => $this->commentCount]);
    }

    /**
     * Kommentar-Formular initialisieren
     * @return string
     */
    protected function assignCommentFormData()
    {
        if (!$this->config->system_comments_enabled || !$this->article->getComments()) {
            return '';
        }

        $privacy = false;
        if ($this->session->exists()) {
            $this->newComment->setName($this->session->getCurrentUser()->getDisplayname());
            $this->newComment->setEmail($this->session->getCurrentUser()->getEmail());
            $this->newComment->setWebsite(\fpcm\classes\dirs::getRootUrl());
            $privacy = true;
        }

        $this->commentFormTemplate->assignByObject($this->article, $this->newComment, $this->captcha, $privacy);
        $parsed = $this->commentFormTemplate->parse();

        return $parsed;
    }

    /**
     * Spam-Captcha initialisieren
     * @return \fpcm\model\captchas
     */
    protected function initSpamCaptcha()
    {
        $this->captcha = \fpcm\components\components::getChatptchaProvider();
        return $this->captcha;
    }

    /**
     * Neuen Kommentar speichern
     * @return void
     */
    protected function initComments()
    {
        if (!$this->config->system_comments_enabled || !$this->article->getComments()) {
            return;
        }

        $this->initSpamCaptcha();

        $this->commentTemplate = new \fpcm\model\pubtemplates\comment($this->config->comments_template_active);
        $this->commentFormTemplate = new \fpcm\model\pubtemplates\commentform();
        $this->newComment = new \fpcm\model\comments\comment();
    }

}
