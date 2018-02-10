<?php
    /**
     * Recent comments Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2018, Stefan Seehafer
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
         * ggf. nötige Container-Berechtigungen
         * @var array
         */
        protected $checkPermissions = array('article' => array('edit', 'editall'), 'comment' => array('edit', 'editall'));
        
        /**
         * Permissions-Objekt
         * @var \fpcm\model\system\permissions
         */
        protected $permissions   = null;

        /**
         * aktueller Benutzer
         * @var int
         */
        protected $currentUser  = 0;
        
        /**
         * Breite des Containers
         * @see \fpcm\model\abstracts\dashcontainer
         * @var bool
         */
        protected $width        = true;

        /**
         * Benutzer ist Admin
         * @see \fpcm\model\abstracts\dashcontainer
         * @var int
         */
        protected $isAdmin      = false;

        /**
         * Konstruktor
         */
        public function __construct() {
                                   
            $this->name      = 'recentcomments';

            $session            = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
            $this->currentUser  = $session->getUserId();
            $this->isAdmin      = $session->getCurrentUser()->isAdmin();

            $this->getCacheName('_'.$this->currentUser);
            
            parent::__construct();

            $this->permissions  = new \fpcm\model\system\permissions($session->currentUser->getRoll());
            
            if ($this->cache->isExpired($this->cacheName)) {
                $this->renderContent();                
            } else {
                $this->content = $this->cache->read($this->cacheName);
            }

            $this->headline  = $this->language->translate('RECENT_COMMENTS');
            $this->position  = 4;
        }
        
        /**
         * Content rendern
         */
        private function renderContent() {

            $ownPermissions = $this->permissions->check(array('article' => 'edit'), array('comment' => 'edit'));
            $allPermissions = $this->permissions->check(array('article' => 'editall'), array('comment' => 'editall'));
            
            $commenList  = new \fpcm\model\comments\commentList();
            
            $search             = new \fpcm\model\comments\search();
            $search->searchtype = 0;
            $search->limit      = array(10,0);
            $search->orderby    = array('createtime DESC');
            $comments           = $commenList->getCommentsBySearchCondition($search);

            $userlist   = new \fpcm\model\users\userList();            
            $users      = array_flip($userlist->getUsersNameList());
            
            $content    = [];
            $content[]  = '<table class="fpcm-ui-table fpcm-ui-articles fpcm-ui-large-td">';
            foreach ($comments as $comment) {
                
                $createInfo = $this->language->translate('COMMMENT_LASTCHANGE', array(
                    '{{username}}' => isset($users[$comment->getChangeuser()]) ? $users[$comment->getChangeuser()] : $this->language->translate('GLOBAL_NOTFOUND'),
                    '{{time}}'     => date($this->config->system_dtmask, $comment->getChangetime())
                ));

                if (!$comment->getChangeuser() && !$comment->getChangetime()) {
                    $createInfo = '';
                }
                
                $content[] = '<tr class="fpcm-small-text">';
                $content[] = '  <td class="fpcm-ui-articlelist-open">';
                $content[] = (string) (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($comment)->setTarget('_blank');
                $content[] = (string) (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($comment, '&mode=1')->setReadonly($comment->getEditPermission() ? false : true);
                $content[] = '  </td>';
                
                $content[] = '  <td>';
                $content[] = '  <strong>'.(new \fpcm\view\helper\escape(strip_tags($comment->getName()))).'</strong> @ '.(new \fpcm\view\helper\dateText($comment->getCreatetime())).'<br>';
                $content[] = '  <span>'.$createInfo.'</span>';
                $content[] = '  </td>';
                $content[] = '  <td class="fpcm-ui-dashboard-recentarticles-meta">';

                if ($comment->getSpammer()) {
                    $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="'.$this->language->translate('COMMMENT_SPAM').'"><span class="fa fa-square fa-stack-2x"></span><span class="fa fa-flag fa-stack-1x fa-inverse"></span></span>';
                }
                if ($comment->getApproved()) {
                    $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="'.$this->language->translate('COMMMENT_APPROVE').'"><span class="fa fa-square fa-stack-2x"></span><span class="fa fa-check-circle-o fa-rotate-90 fa-stack-1x fa-inverse"></span></span>';
                }                
                if ($comment->getPrivate()) {
                    $content[] = '      <span class="fa-stack fa-fw fpcm-ui-status-1" title="'.$this->language->translate('COMMMENT_PRIVATE').'"><span class="fa fa-square fa-stack-2x"></span><span class="fa fa-eye-slash fa-stack-1x fa-inverse"></span></span>';
                }               

                $content[] = '  </td>';
                $content[] = '</tr>';
            }
            
            $content[]  = '</table>';
            
            $this->content = implode(PHP_EOL, $content);
            
            $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
        }
        
    }