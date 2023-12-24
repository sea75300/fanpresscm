<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent articles dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class recentarticles extends \fpcm\model\abstracts\dashcontainer implements \fpcm\model\interfaces\isAccessible {

    /**
     * Permissions-Objekt
     * @var \fpcm\model\permissions\permissions
     */
    protected $permissions = null;

    /**
     * aktueller Benutzer
     * @var int
     */
    protected $currentUser = 0;

    /**
     * Benutzer ist Admin
     * @see \fpcm\model\abstracts\dashcontainer
     * @var int
     */
    protected $isAdmin = false;

    /**
     * Container is accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles();
    }

    /**
     * Returns container name
     * @return string
     */
    public function getName()
    {
        return 'recentarticles';
    }

    /**
     * Returns container content
     * @return string
     */
    public function getContent()
    {
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $this->currentUser = $session->getUserId();
        $this->isAdmin = $session->getCurrentUser()->isAdmin();

        $this->getCacheName('_' . $this->currentUser);

        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }

        $articleList = new \fpcm\model\articles\articlelist();
        $userlist = new \fpcm\model\users\userList();

        $conditions = new \fpcm\model\articles\search();
        $conditions->limit = [10, 0];
        $conditions->orderby = ['createtime DESC'];

        $articles = $articleList->getArticlesByCondition($conditions);
        
        if (!count($articles)) {
            $str = $this->language->translate('GLOBAL_NOTFOUND2');
            $this->cache->write($this->cacheName, $str);
            return $str;
        }

        $users = array_flip($userlist->getUsersNameList());

        $content = [];
        $content[] = '<div>';
        
        $createStr = $this->language->translate('GLOBAL_AUTHOR_EDITOR');
        
        /* @var $article \fpcm\model\articles\article */
        foreach ($articles as $article) {

            $createInfo = $this->language->translate('GLOBAL_USER_ON_TIME', array(
                '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()] : $this->language->translate('GLOBAL_NOTFOUND'),
                '{{time}}' => date($this->config->system_dtmask, $article->getCreatetime())
            ));

            $content[] = '<div class="row fpcm-ui-font-small py-1">';
            $content[] = '  <div class="col-12 col-md-auto px-3 text-center">';
            $content[] = (string) (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($article)->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW);
            $content[] = (string) (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($article)->setReadonly($article->getEditPermission() ? false : true);
            $content[] = '  </div>';

            $content[] = '  <div class="col align-self-center text-truncate">';
            $content[] = '  <strong>' . (new \fpcm\view\helper\escape(strip_tags(rtrim($article->getTitle(), '.!?')))) . '</strong><br>';
            $content[] = '  <span>' . $createStr . ': ' . $createInfo . '</span>';
            $content[] = '  </div>';
            $content[] = '  <div class="col-auto fpcm-ui-metabox px-4 align-self-center">';
            $content[] = $article->getStatusIconPinned();
            $content[] = $article->getStatusIconDraft();
            $content[] = $article->getStatusIconPostponed();
            $content[] = $article->getStatusIconApproval();
            $content[] = $article->getStatusIconComments();
            $content[] = '  </div>';
            $content[] = '</div>';
        }

        $content[] = '</div>';

        $str = implode(PHP_EOL, $content);
        $this->cache->write($this->cacheName, $str, $this->config->system_cache_timeout);
        return $str;
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'RECENT_ARTICLES';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 2;
    }

    /**
     * Returns container width
     * @return int
     */
    public function getWidth()
    {
        return 8;
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('toActiveArticles'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/listactive'))
                ->setIcon('newspaper', 'far')
                ->setText('HL_ARTICLE_EDIT');
    }

}
