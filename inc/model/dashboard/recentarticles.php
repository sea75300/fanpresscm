<?php

/**
 * Recent articles Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent articles dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class recentarticles extends \fpcm\model\abstracts\dashcontainer {

    /**
     * ggf. nötige Container-Berechtigungen
     * @var array
     */
    protected $checkPermissions = array('article' => array('edit', 'editall'));

    /**
     * Permissions-Objekt
     * @var \fpcm\model\system\permissions
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
     * 
     * @return string
     */
    public function getName()
    {
        return 'recentarticles';
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $this->currentUser = $session->getUserId();
        $this->isAdmin = $session->getCurrentUser()->isAdmin();

        $this->getCacheName('_' . $this->currentUser);

        $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\system\permissions');

        if ($this->cache->isExpired($this->cacheName)) {
            $this->renderContent();
        }

        return $this->cache->read($this->cacheName);
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
     * 
     * @return int
     */
    public function getPosition()
    {
        return 2;
    }

    /**
     * 
     * @return int
     */
    public function getWidth()
    {
        return 8;
    }

    /**
     * Content rendern
     */
    private function renderContent()
    {

        $ownPermissions = $this->permissions->check(array('article' => 'edit'));
        $allPermissions = $this->permissions->check(array('article' => 'editall'));

        $articleList = new \fpcm\model\articles\articlelist();
        $userlist = new \fpcm\model\users\userList();

        $conditions = new \fpcm\model\articles\search();
        $conditions->draft = -1;
        $conditions->approval = -1;
        $conditions->limit = [10, 0];
        $conditions->orderby = ['createtime DESC'];

        $articles = $articleList->getArticlesByCondition($conditions);

        $users = array_flip($userlist->getUsersNameList());

        $content = [];
        $content[] = '<div>';
        foreach ($articles as $article) {

            $createInfo = $this->language->translate('EDITOR_AUTHOREDIT', array(
                '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()] : $this->language->translate('GLOBAL_NOTFOUND'),
                '{{time}}' => date($this->config->system_dtmask, $article->getCreatetime())
            ));

            $content[] = '<div class="row fpcm-ui-font-small fpcm-ui-padding-md-tb">';
            $content[] = '  <div class="col-sm-12 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">';
            $content[] = (string) (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($article)->setTarget('_blank');
            $content[] = (string) (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($article)->setReadonly($article->getEditPermission() ? false : true);
            $content[] = '  </div>';

            $content[] = '  <div class="col-sm-12 col-md-8">';
            $content[] = '  <div class="fpcm-ui-ellipsis">';
            $content[] = '  <strong>' . (new \fpcm\view\helper\escape(strip_tags(rtrim($article->getTitle(), '.!?')))) . '</strong><br>';
            $content[] = '  <span>' . $createInfo . '</span>';
            $content[] = '  </div></div>';
            $content[] = '  <div class="col-auto fpcm-ui-metabox fpcm-ui-padding-none-lr">';

            if ($article->getPinned()) {
                $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="' . $this->language->translate('EDITOR_STATUS_PINNED') . '"><span class="fa fa-square fa-stack-2x"></span><span class="fa fa-thumbtack fa-rotate-90 fa-stack-1x fa-inverse"></span></span>';
            }
            if ($article->getDraft()) {
                $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="' . $this->language->translate('EDITOR_STATUS_DRAFT') . '"><span class="fa fa-square fa-stack-2x"></span><span class="far fa-file-alt fa-stack-1x fa-inverse"></span></span>';
            }
            if ($article->getPostponed()) {
                $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="' . $this->language->translate('EDITOR_STATUS_POSTPONETO') . '"><span class="fa fa-square fa-stack-2x"></span><span class="fa fa-calendar-plus fa-stack-1x fa-inverse"></span></span>';
            }
            if ($article->getApproval()) {
                $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="' . $this->language->translate('EDITOR_STATUS_APPROVAL') . '"><span class="fa fa-square fa-stack-2x"></span><span class="far fa-thumbs-up fa-stack-1x fa-inverse"></span></span>';
            }

            $content[] = '  </div>';
            $content[] = '</div>';
        }

        $content[] = '</div>';

        $this->content = implode(PHP_EOL, $content);

        $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
    }

}
