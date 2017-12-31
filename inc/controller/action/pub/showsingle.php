<?php
    /**
     * Public article list controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\pub;
    
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
         * @var \fpcm\model\ips\iplist
         */
        protected $iplist;
        
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
         * Konstruktor
         * @param bool $apiMode API-Modus
         */
        public function __construct($apiMode = false) {
            parent::__construct();
            
            $this->view         = new \fpcm\model\view\pub('showsingle', 'public');
            $this->view->assign('article', '');
            $this->view->assign('comments', '');
            $this->view->assign('commentform', '');
            $this->view->assign('systemMode', $this->config->system_mode);
            
            $this->view->setShowHeader($apiMode ? false : true);
            $this->view->setShowFooter($apiMode ? false : true);
                        
            $this->commentList  = new \fpcm\model\comments\commentList();
            $this->categoryList = new \fpcm\model\categories\categoryList();
            $this->userList     = new \fpcm\model\users\userList();
            $this->iplist       = new \fpcm\model\ips\iplist();
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {
            
            if (!$this->maintenanceMode()) {
                return false;
            }
            
            $this->isUtf8   = defined('FPCM_PUB_OUTPUT_UTF8') ? FPCM_PUB_OUTPUT_UTF8 : true;
            
            $this->crons->registerCron('postponedArticles');
            
            if ($this->iplist->ipIsLocked()) {
                $this->view->addErrorMessage('ERROR_IP_LOCKED');
                $this->view->assign('showToolbars', false);
                $this->view->render();
                return false;
            }             
            
            if (is_null($this->getRequestVar('id'))) {
                $this->view->addErrorMessage('LOAD_FAILED_ARTICLE');
                return true;
            }
            
            $this->articleId = $this->getRequestVar('id');
            $srcData         = explode('-', $this->articleId, 2);
            $this->articleId = (int) $srcData[0];

            $this->article = new \fpcm\model\articles\article($this->articleId);

            if (!$this->article->publicIsVisible()) {
                $this->view->addErrorMessage('LOAD_FAILED_ARTICLE');
                $this->article = false;
                return true;
            }

            $this->cache = new \fpcm\classes\cache(\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE.$this->articleId, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);
            
            $this->articleTemplate = new \fpcm\model\pubtemplates\article($this->config->article_template_active);

            $this->saveComment();

            return true;
        }
        
        /**
         * Controller ausführen
         * @return boolean
         */
        public function process() {
            
            parent::process();
            
            $parsed = [];
            
            if (!$this->article) {
                $this->view->render();
                return false;
            }
            
            $parsed = array('articles' => '', 'comments' => '');
            if ($this->cache->isExpired() || $this->session->exists()) {
                $parsed['articles'] = $this->assignArticleData();
                $parsed['comments'] = $this->assignCommentsData();
                
                $parsed = $this->events->runEvent('publicShowSingle', $parsed);
                
                if (!$this->session->exists()) $this->cache->write($parsed, $this->config->system_cache_timeout);
            } else {
                $parsed = $this->cache->read();
            }
            
            if (!$this->isUtf8) {
                $parsed['articles'] = utf8_decode($parsed['articles']);
                $parsed['comments'] = utf8_decode($parsed['comments']);
            }            
            
            $this->view->assign('article', $parsed['articles']);
            
            if ($this->config->system_comments_enabled && $this->article->getComments() && !$this->iplist->ipIsLocked('nocomments')) {
                $this->view->assign('comments', $parsed['comments']);
                $this->view->assign('commentform', $this->assignCommentFormData());
            } else {
                $this->view->assign('commentform', false);
            }
            
            $this->view->render();
        }        
        
        /**
         * Artikel parsen
         * @return string
         */
        protected function assignArticleData() {
            $categoryTexts = [];
            $categoryIcons = [];

            $categories = $this->categoryList->getCategoriesAll();     
            
            foreach ($this->article->getCategories() as $categoryId) {
                $category = isset($categories[$categoryId]) ? $categories[$categoryId] : false;

                if (!$category) continue;

                $categoryTexts[] = '<span class="fpcm-pub-category-text">'.$category->getName().'</span>';

                if (!$category->getIconPath()) continue;
                $categoryIcons[] = '<img src="'.$category->getIconPath().'" alt="'.$category->getName().'" title="'.$category->getName().'" class="fpcm-pub-category-icon">';
            }

            $shareButtonParser = new \fpcm\model\pubtemplates\sharebuttons($this->article->getArticleLink(), $this->article->getTitle());
            
            $users = $this->userList->getUsersByIds(array($this->article->getCreateuser(), $this->article->getChangeuser()));

            if ($this->session->exists()) {
                $approvedOnly = null;
                $privateNo    = null;
                $spamNo       = null;
                $useCache     = false;
            }
            else {
                $approvedOnly = 1;
                $privateNo    = 0;
                $spamNo       = 0;
                $useCache     = true;
            }

            $commentCounts = $this->commentList->countComments(
                [$this->article->getId()],
                $privateNo,
                $approvedOnly,
                $spamNo,
                $useCache
            );
            
            $commentCount  = $this->config->system_comments_enabled && $this->article->getComments()
                           ? (isset($commentCounts[$this->article->getId()]) ? (int) $commentCounts[$this->article->getId()] : 0)
                           : '';
            
            $this->articleTemplate->setCommentsEnabled($this->config->system_comments_enabled && $this->article->getComments());

            $cuser  = isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()] : false;
            $chuser = isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()] : false;

            $emailAddress   = $cuser
                            ? '<a href="mailto:'.$cuser->getEmail().'">'.$cuser->getDisplayname().'</a>'
                            : '';
            
            $replacements = array(
                '{{headline}}'                      => $this->article->getTitle(),
                '{{text}}'                          => $this->article->getContent(),
                '{{author}}'                        => $cuser ? $cuser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                '{{authorEmail}}'                   => $emailAddress,
                '{{authorAvatar}}'                  => $cuser ? \fpcm\model\users\author::getAuthorImageDataOrPath($cuser, 0) : '',
                '{{authorInfoText}}'                => $cuser ? $cuser->getUsrinfo() : '',
                '{{date}}'                          => date($this->config->system_dtmask, $this->article->getCreatetime()),
                '{{changeDate}}'                    => date($this->config->system_dtmask, $this->article->getChangetime()),
                '{{changeUser}}'                    => $chuser ? $chuser->getDisplayname() : $this->lang->translate('GLOBAL_NOTFOUND'),
                '{{statusPinned}}'                  => $this->article->getPinned() ? $this->lang->translate('PUBLIC_ARTICLE_PINNED') : '',
                '{{shareButtons}}'                  => $shareButtonParser->parse(),
                '{{categoryIcons}}'                 => implode(PHP_EOL, $categoryIcons),
                '{{categoryTexts}}'                 => implode(PHP_EOL, $categoryTexts),
                '{{commentCount}}'                  => $commentCount,
                '{{permaLink}}:{{/permaLink}}'      => $this->article->getArticleLink(),
                '{{commentLink}}:{{/commentLink}}'  => $this->article->getArticleLink().'#comments',
                '<readmore>:</readmore>'            => $this->article->getMd5path(),
                '{{articleImage}}'                  => $this->article->getArticleImage(),
                '{{sources}}'                       => $this->article->getSources()
            );

            $this->articleTemplate->setReplacementTags($replacements);
            
            $parsed = $this->articleTemplate->parse();
            
            if ($this->session->exists()) {
                $html   = [];
                $html[] = '<div class="fpcm-pub-articletoolbar-article fpcm-pub-articletoolbar-article'.$this->articleId.'">';
                $html[] = '<a href="'.$this->article->getEditLink().'">'.$this->lang->translate('HL_ARTICLE_EDIT').'</a>';
                $html[] = '</div>';
                
                $parsed = implode(PHP_EOL, $html).$parsed;
            }
            
            return $parsed;
        }
        
        /**
         * Kommentare parsen
         * @return string
         */
        protected function assignCommentsData() {
            
            if (!$this->config->system_comments_enabled || !$this->article->getComments()) return '';

            $conditions = new \fpcm\model\comments\search();
            $conditions->articleid = $this->articleId;
            $conditions->approved  = $this->session->exists() ? null : 1;
            $conditions->private   = $this->session->exists() ? null : 0;
            $conditions->spam      = $this->session->exists() ? null : 0;
            $comments = $this->commentList->getCommentsBySearchCondition($conditions);

            $parsed = [];
            $i      = 1;
            foreach ($comments as $comment) {
                $tpl = $this->commentTemplate;

                $replacements = array(
                    '{{author}}'                => $comment->getName(),
                    '{{email}}'                 => $comment->getEmail(),
                    '{{website}}'               => $comment->getWebsite(),
                    '{{text}}'                  => $comment->getText(),
                    '{{date}}'                  => date($this->config->system_dtmask, $comment->getCreatetime()),
                    '{{number}}'                => $i,
                    '{{id}}'                    => $comment->getId(),
                    '{{mentionid}}'             => 'id="c'.$i.'"',
                    '{{mention}}:{{/mention}}'  => $i
                );
                
                $tpl->setReplacementTags($replacements);

                $parsed[] = $tpl->parse();
                
                $i++;
            }

            return implode(PHP_EOL, $parsed);            
        }

        /**
         * Kommentar-Formular initialisieren
         * @return string
         */
        protected function assignCommentFormData() {

            if (!$this->config->system_comments_enabled || !$this->article->getComments()) return '';
            
            $id = ($this->session->exists()) ? $this->session->getUserId() : null;
            
            $author = new \fpcm\model\users\author($id);

            if (!$this->buttonClicked('sendComment') && is_null($this->getRequestVar('newcomment')) && $this->session->exists()) {
                $this->newComment->setName($author->getDisplayname());
                $this->newComment->setEmail($author->getEmail());
                $this->newComment->setWebsite(\fpcm\classes\http::getHttpHost());
            }
            
            $replacementTags = array(
                '{{formHeadline}}'                   => $this->lang->translate('COMMENTS_PUBLIC_FORMHEADLINE'),
                '{{submitUrl}}'                      => $this->article->getArticleLink(),
                '{{nameDescription}}'                => $this->lang->translate('COMMMENT_AUTHOR'),
                '{{nameField}}'                      => '<input type="text" class="fpcm-pub-textinput" name="newcomment[name]" value="'.$this->newComment->getName().'">',
                '{{emailDescription}}'               => $this->lang->translate('GLOBAL_EMAIL'),
                '{{emailField}}'                     => '<input type="text" class="fpcm-pub-textinput" name="newcomment[email]" value="'.$this->newComment->getEmail().'">',
                '{{websiteDescription}}'             => $this->lang->translate('COMMMENT_WEBSITE'),
                '{{websiteField}}'                   => '<input type="text" class="fpcm-pub-textinput" name="newcomment[website]" value="'.$this->newComment->getWebsite().'">',
                '{{textfield}}'                      => '<textarea class="fpcm-pub-textarea" id="newcommenttext" name="newcomment[text]">'.$this->newComment->getText().'</textarea>',
                '{{smileysDescription}}'             => $this->lang->translate('HL_OPTIONS_SMILEYS'),
                '{{smileys}}'                        => $this->getSmileyList(),
                '{{tags}}'                           => htmlentities(\fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_FORM),
                '{{spampluginQuestion}}'             => $this->captcha->createPluginText(),
                '{{spampluginField}}'                => $this->captcha->createPluginInput(),
                '{{privateCheckbox}}'                => '<input type="checkbox" class="fpcm-pub-checkboxinput" name="newcomment[private]" value="1">',
                '{{submitButton}}'                   => '<button type="submit" name="btnSendComment">'.$this->lang->translate('GLOBAL_SUBMIT').'</button>',
                '{{resetButton}}'                    => '<button type="reset">'.$this->lang->translate('GLOBAL_RESET').'</button>'
            );            
           
            $this->commentFormTemplate->setReplacementTags($replacementTags);
            $parsed = $this->commentFormTemplate->parse();
            
            if (!$this->isUtf8) {
                $parsed = utf8_decode($parsed);
            }
            
            return $parsed;
        }
        
        /**
         * Smiley-Liste initialisieren
         * @return string
         */
        protected function getSmileyList() {
            $smileyList = new \fpcm\model\files\smileylist();
            $smileys    = $smileyList->getDatabaseList();            

            $html = [];
            $html[] = "<ul class=\"fpcm-pub-smileys\">";
            foreach ($smileys as $key => $smiley)  {
                $html[] = '<li><a class="fpcm-pub-commentsmiley" smileycode="'.$smiley->getSmileyCode().'" href="#"><img src="'.$smiley->getSmileyUrl().'" alt="'.$smiley->getSmileyCode().'()" '.$smiley->getWhstring().'></a></li>';
            }
            $html[] = '</ul>';
            
            return implode(PHP_EOL, $html);
            
        }
        
        /**
         * Spam-Captcha initialisieren
         * @return \fpcm\model\captchas
         */
        protected function initSpamCaptcha() {            
            $this->captcha = $this->events->runEvent('publicReplaceSpamCaptcha');
            
            if (!is_a($this->captcha, '\fpcm\model\abstracts\spamCaptcha')) {
                $this->captcha = new \fpcm\model\captchas\fpcmDefault();
            }
            
            return $this->captcha;
        }

        /**
         * Neuen Kommentar speichern
         * @return boolean
         */
        protected function saveComment() {
            
            if (!$this->config->system_comments_enabled || !$this->article->getComments()) {
                return true;
            }
                
            $this->initSpamCaptcha();

            $this->newComment = new \fpcm\model\comments\comment();

            $this->commentTemplate     = new \fpcm\model\pubtemplates\comment($this->config->comments_template_active);
            $this->commentFormTemplate = new \fpcm\model\pubtemplates\commentform();

            if ($this->buttonClicked('sendComment') && !is_null($this->getRequestVar('newcomment')) && !$this->iplist->ipIsLocked() && !$this->iplist->ipIsLocked('nocomments')) {
                $newCommentData = $this->getRequestVar('newcomment');

                $timer = time();

                if ($timer <= $this->commentList->getLastCommentTimeByIP() + $this->config->comments_flood) {
                    $this->view->addErrorMessage('PUBLIC_FAILED_FLOOD', array('{{seconds}}' => $this->config->comments_flood));
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

                $text  = $this->lang->translate('PUBLIC_COMMENT_EMAIL_TEXT', array(
                    '{{name}}'        => $this->newComment->getName(),
                    '{{email}}'       => $this->newComment->getEmail(),
                    '{{commenttext}}' => strip_tags($this->newComment->getText()),
                    '{{articleurl}}'  => $this->article->getArticleLink(),
                    '{{systemurl}}'   => \fpcm\classes\baseconfig::$rootPath
                ));

                $to    = [];
                if ($this->config->comments_notify != 1) {
                    $to[] = $this->config->system_email;                        
                }
                if ($this->config->comments_notify > 0 && !$this->session->exists()) {
                    $to[] = $this->userList->getEmailByUserId($this->article->getCreateuser());
                }                                        

                if (!count($to) || $this->session->exists()) return true;

                $email = new \fpcm\classes\email(implode(',', array_unique($to)), $this->lang->translate('PUBLIC_COMMENT_EMAIL_SUBJECT'), $text);
                $email->submit();                    
            }
            
        }
        
    }
?>