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
class showsingle extends \fpcm\controller\abstracts\pubController {

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
     * UTF8-Encoding aktiv
     * @var bool
     */
    protected $isUtf8 = true;

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
        $this->templateString = isset($params['template']) && trim($params['template']) ? $params['template'] : false;
        $this->apiMode = isset($params['apiMode']) ? (bool) $params['apiMode'] : false;
        $this->isUtf8 = isset($params['isUtf8']) ? (bool) $params['isUtf8'] : true;

        parent::__construct();

        $this->viewVars = [
            'article' => '',
            'comments' => '',
            'commentform' => '',
            'systemMode' => $this->config->system_mode
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
     * @return boolean
     */
    public function request()
    {
        $this->crons->registerCron('postponedArticles');

        $this->articleId = $this->getRequestVar('id');
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

        $this->saveComment();

        return true;
    }

    /**
     * Controller ausführen
     * @return boolean
     */
    public function process()
    {
        parent::process();
        if (!$this->article) {
            $this->view->setViewVars(array_merge($this->viewVars, $this->view->getViewVars()));
            $this->view->render();
            return false;
        }

        $parsed = array('articles' => '', 'comments' => '');
        if ($this->cache->isExpired($this->cacheName) || $this->session->exists()) {
            $parsed['articles'] = $this->assignArticleData();
            $parsed['comments'] = $this->assignCommentsData();

            $parsed = $this->events->trigger('pub\showSingle', $parsed);

            if (!$this->session->exists()) {
                $this->cache->write($this->cacheName, $parsed, $this->config->system_cache_timeout);
            }
        } else {
            $parsed = $this->cache->read($this->cacheName);
        }

        if (!$this->isUtf8) {
            $parsed['articles'] = utf8_decode($parsed['articles']);
            $parsed['comments'] = utf8_decode($parsed['comments']);
        }

        $this->viewVars['article'] = $parsed['articles'];
        if ($this->config->system_comments_enabled && $this->article->getComments() && !$this->ipList->ipIsLocked('nocomments')) {
            $this->viewVars['comments'] = $parsed['comments'];
            $this->viewVars['commentform'] = $this->assignCommentFormData();
        }

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

        if ($this->session->exists()) {
            $approvedOnly = null;
            $privateNo = null;
            $spamNo = null;
            $useCache = false;
        } else {
            $approvedOnly = 1;
            $privateNo = 0;
            $spamNo = 0;
            $useCache = true;
        }

        $this->articleTemplate->assignByObject(
            $this->article, [
                'author' => isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()] : false,
                'changeUser' => isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()] : false
            ], $this->categoryList->assignPublic($this->article), $this->commentList->countComments([$this->article->getId()], $privateNo, $approvedOnly, $spamNo, $useCache)[$this->article->getId()]
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
     * @return string
     */
    protected function assignCommentsData()
    {
        if (!$this->config->system_comments_enabled || !$this->article->getComments())
            return '';

        $conditions = new \fpcm\model\comments\search();
        $conditions->articleid = $this->articleId;
        $conditions->approved = $this->session->exists() ? null : 1;
        $conditions->private = $this->session->exists() ? null : 0;
        $conditions->spam = $this->session->exists() ? null : 0;
        $comments = $this->commentList->getCommentsBySearchCondition($conditions);

        $parsed = [];
        $i = 1;
        foreach ($comments as $comment) {

            $this->commentTemplate->assignByObject($comment, $i);
            $parsed[] = $this->commentTemplate->parse();

            $i++;
        }

        return implode(PHP_EOL, $parsed);
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

        $data = $this->getRequestVar('newcomment');
        if (!$this->buttonClicked('sendComment') && !$data && $this->session->exists()) {
            $this->newComment->setName($this->session->getCurrentUser()->getDisplayname());
            $this->newComment->setEmail($this->session->getCurrentUser()->getEmail());
            $this->newComment->setWebsite(\fpcm\classes\http::getHttpHost());
        }

        $this->commentFormTemplate->assignByObject($this->article, $this->newComment, $this->captcha);
        $parsed = $this->commentFormTemplate->parse();

        if (!$this->isUtf8) {
            $parsed = utf8_decode($parsed);
        }

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
     * @return boolean
     */
    protected function saveComment()
    {
        if (!$this->config->system_comments_enabled || !$this->article->getComments()) {
            return true;
        }

        $this->initSpamCaptcha();

        $this->newComment = new \fpcm\model\comments\comment();

        $this->commentTemplate = new \fpcm\model\pubtemplates\comment($this->config->comments_template_active);
        $this->commentFormTemplate = new \fpcm\model\pubtemplates\commentform();

        $newCommentData = $this->getRequestVar('newcomment');
        if ($this->buttonClicked('sendComment') && $newCommentData !== null && !$this->ipList->ipIsLocked() && !$this->ipList->ipIsLocked('nocomments')) {

            $hasPrivacy = (isset($newCommentData['privacy']) && $this->config->comments_privacy_optin) || !$this->config->comments_privacy_optin ? true : false;
            if ($this->buttonClicked('sendComment') && !$hasPrivacy) {
                $this->view->addErrorMessage('PUBLIC_PRIVACY');
                return true;
            }

            $timer = time();

            if ($timer <= $this->commentList->getLastCommentTimeByIP() + $this->config->comments_flood) {
                $this->view->addErrorMessage('PUBLIC_FAILED_FLOOD', [
                    '{{seconds}}' => $this->config->comments_flood
                ]);
                return true;
            }

            if (!$this->captcha->checkAnswer()) {
                $this->view->addErrorMessage('PUBLIC_FAILED_CAPTCHA');
                return true;
            }

            if (!$newCommentData['name']) {
                $this->view->addErrorMessage('PUBLIC_FAILED_NAME');
                return true;
            }

            $newCommentData['email'] = filter_var($newCommentData['email'], FILTER_VALIDATE_EMAIL);
            if ($this->config->comments_email_optional && !$newCommentData['email']) {
                $this->view->addErrorMessage('PUBLIC_FAILED_EMAIL');
                return true;
            }

            $newCommentData['website'] = filter_var($newCommentData['website'], FILTER_VALIDATE_URL);
            $newCommentData['website'] = $newCommentData['website'] ? $newCommentData['website'] : '';

            $this->newComment->setName($newCommentData['name']);
            $this->newComment->setEmail($newCommentData['email']);
            $this->newComment->setWebsite($newCommentData['website']);
            $this->newComment->setText(nl2br(strip_tags($newCommentData['text'], \fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_CHECK)));
            $this->newComment->setPrivate(isset($newCommentData['private']));
            $this->newComment->setIpaddress(\fpcm\classes\http::getIp());
            $this->newComment->setApproved($this->config->comments_confirm ? false : true);
            $this->newComment->setArticleid($this->articleId);
            $this->newComment->setCreatetime($timer);
            $this->newComment->setSpammer((!$this->session->exists() && $this->captcha->checkExtras() ? true : false));
            $this->newComment->prepareDataSave();

            if (!$this->newComment->save()) {
                $this->view->addErrorMessage('SAVE_FAILED_COMMENT');
                return true;
            }

            $this->view->addNoticeMessage('SAVE_SUCCESS_COMMENT');

            $text = $this->language->translate('PUBLIC_COMMENT_EMAIL_TEXT', array(
                '{{name}}' => $this->newComment->getName(),
                '{{email}}' => $this->newComment->getEmail(),
                '{{commenttext}}' => strip_tags($this->newComment->getText()),
                '{{articleurl}}' => $this->article->getElementLink(),
                '{{systemurl}}' => \fpcm\classes\dirs::getRootUrl()
            ));

            $to = [];
            if ($this->config->comments_notify != 1) {
                $to[] = $this->config->system_email;
            }
            if ($this->config->comments_notify > 0 && !$this->session->exists()) {
                $to[] = $this->userList->getEmailByUserId($this->article->getCreateuser());
            }

            if (!count($to) || $this->session->exists())
                return true;

            $email = new \fpcm\classes\email(implode(',', array_unique($to)), $this->language->translate('PUBLIC_COMMENT_EMAIL_SUBJECT'), $text);
            $email->submit();
        }
    }

}

?>