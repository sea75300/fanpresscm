<?php
    /**
     * Recent comments Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
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

            $this->cacheName = 'recentcomments';
            
            parent::__construct();
            
            $session            = \fpcm\classes\baseconfig::$fpcmSession;
            $this->currentUser  = $session->getUserId();
            $this->isAdmin      = $session->getCurrentUser()->isAdmin();
            $this->permissions  = new \fpcm\model\system\permissions($session->currentUser->getRoll());
            $this->cache        = new \fpcm\classes\cache($this->cacheName.'_'.$this->currentUser, self::CACHE_M0DULE_DASHBOARD);
            
            if ($this->cache->isExpired()) {
                $this->renderContent();                
            } else {
                $this->content = $this->cache->read();
            }
                                   
            $this->headline = $this->language->translate('RECENT_COMMENTS');
            $this->name     = 'recentcomments';
            $this->position = 4;
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
                $content[] = '  <a class="fpcm-ui-button fpcm-ui-button-blank fpcm-openlink-btn" href="'.$comment->getArticleLink().'" target="_blank" title="'.$this->language->translate('GLOBAL_FRONTEND_OPEN').'">'.$this->language->translate('GLOBAL_FRONTEND_OPEN').'</a>';
                if ($comment->getEditPermission()) {
                    $content[] = '  <a class="fpcm-ui-button fpcm-ui-button-blank fpcm-ui-button-edit fpcm-loader" href="'.$comment->getEditLink().'&amp;mode=1" title="'.$this->language->translate('GLOBAL_EDIT').'">'.$this->language->translate('GLOBAL_EDIT').'</a>';
                } else {
                    $content[] = '  <span class="fpcm-ui-button fpcm-ui-button-blank fpcm-ui-button-edit fpcm-ui-readonly" title="'.$this->language->translate('GLOBAL_EDIT').'">'.$this->language->translate('GLOBAL_EDIT').'</span>';
                }
                $content[] = '  </td>';
                
                $content[] = '  <td>';
                $content[] = '  <strong>'.\fpcm\model\view\helper::escapeVal(strip_tags($comment->getName())).'</strong> @ '.date($this->config->system_dtmask, $comment->getCreatetime()).'<br>';
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
            
            $this->cache->write($this->content, $this->config->system_cache_timeout);
        }
        
    }