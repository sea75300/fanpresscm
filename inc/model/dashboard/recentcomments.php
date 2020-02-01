<?php

/**
 * Recent comments Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent comments dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class recentcomments extends \fpcm\model\abstracts\dashcontainer {

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
     * Returns container name
     * @return string
     */
    public function getName()
    {
        return 'recentcomments';
    }

    /**
     * Returns container permissions
     * @return array
     */
    public function getPermissions()
    {
        return [
            'article' => ['edit', 'editall'],
            'comment' => ['edit', 'editall']
        ];
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
        return 'RECENT_COMMENTS';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 4;
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
     * Content rendern
     */
    private function renderContent()
    {
        $commenList = new \fpcm\model\comments\commentList();

        $search = new \fpcm\model\comments\search();
        $search->searchtype = 0;
        $search->deleted = 0;
        $search->limit = array(10, 0);
        $search->orderby = array('createtime DESC');
        $comments = $commenList->getCommentsBySearchCondition($search);

        $userlist = new \fpcm\model\users\userList();
        $users = array_flip($userlist->getUsersNameList());

        $content = [];
        $content[] = '<div>';
        foreach ($comments as $comment) {

            $createInfo = $this->language->translate('COMMMENT_LASTCHANGE', array(
                '{{username}}' => isset($users[$comment->getChangeuser()]) ? $users[$comment->getChangeuser()] : $this->language->translate('GLOBAL_NOTFOUND'),
                '{{time}}' => date($this->config->system_dtmask, $comment->getChangetime())
            ));

            if (!$comment->getChangeuser() && !$comment->getChangetime()) {
                $createInfo = '';
            }

            $content[] = '<div class="row fpcm-ui-font-small fpcm-ui-padding-md-tb">';
            $content[] = '  <div class="col-12 col-md-auto px-3 fpcm-ui-center">';
            $content[] = (string) (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($comment)->setTarget('_blank');
            $content[] = (string) (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($comment, '&mode=1')->setReadonly($comment->getEditPermission() ? false : true);
            $content[] = '  </div>';

            $content[] = '  <div class="col align-self-center">';
            $content[] = '  <div class="fpcm-ui-ellipsis">';
            $content[] = '  <strong>' . (new \fpcm\view\helper\escape(strip_tags($comment->getName()))) . '</strong> @ ' . (new \fpcm\view\helper\dateText($comment->getCreatetime())) . '<br>';
            $content[] = '  <span>' . $createInfo . '</span>';
            $content[] = '  </div></div>';
            $content[] = '  <div class="col-auto fpcm-ui-metabox px-4 align-self-center">';

            $content[] = $comment->getStatusIconSpam();
            $content[] = $comment->getStatusIconApproved();
            $content[] = $comment->getStatusIconPrivate();

            $content[] = '  </div>';
            $content[] = '</div>';
        }

        $content[] = '</div>';

        $this->content = implode(PHP_EOL, $content);

        $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
    }

}
