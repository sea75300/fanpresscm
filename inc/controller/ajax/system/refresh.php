<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX controller for refresh actions (async crons, session check, article inedit mode)
 * 
 * @package fpcm\controller\ajax\system\refresh
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class refresh extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\isAccessibleTrue;
    
    private $ext = false;

    /**
     * 
     * @var \fpcm\model\http\responseDataRefresh
     */
    private $returnDataObj;

    /**
     * @see \fpcm\controller\abstracts\controller::hasAccess()
     * @return bool
     */
    public function hasAccess()
    {
        if (\fpcm\classes\baseconfig::installerEnabled() || !\fpcm\classes\baseconfig::dbConfigExists()) {
            return false;
        }
        
        $this->ext = $this->request->fetchAll('t') !== null;
        if (!$this->checkReferer($this->ext)) {
            return false;
        }

        return true;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->returnDataObj = new \fpcm\model\http\responseDataRefresh();
        
        $this->runCrons();
        if ($this->ext) {
            exit('{}');
        }

        $this->runSessionCheck();
        $this->runArticleInEdit();
        $this->getNotifications();
        $this->response->setReturnData($this->returnDataObj)->fetch();
    }

    /**
     * 
     * @return bool
     */
    private function runCrons()
    {
        if (defined('FPCM_DISABLE_AJAX_CRONJOBS_REFRESH') && FPCM_DISABLE_AJAX_CRONJOBS_REFRESH) {
            fpcmLogCron('Asynchronous cronjob execution was disabled');
            return true;
        }

        if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
            fpcmLogCron('Asynchronous cronjob execution was disabled');
            return false;
        }
        
        $cronlist = new \fpcm\model\crons\cronlist();
        $crons = $cronlist->getExecutableCrons();

        if (!count($crons)) {
            return true;
        }

        array_map([$cronlist, 'registerCronAjax'] , $crons);
        $this->returnDataObj->crons = true;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function runSessionCheck()
    {
        if (!is_object($this->session)) {
            $this->returnDataObj->sessionCode = -1;
            return true;
        }

        if (!$this->session->exists() || $this->ipList->ipIsLocked($this->getIpLockedModul()) ) {
            $this->returnDataObj->sessionCode = 0;
            return true;
        }

        $this->returnDataObj->sessionCode = 1;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function runArticleInEdit()
    {
        $this->returnDataObj->articleCode = 0;

        $articleId = $this->request->fetchAll('articleId', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($this->returnDataObj->sessionCode < 1 || !$this->permissions->editArticles() || !$articleId) {
            return true;
        }
        
        $article = new \fpcm\model\articles\article($articleId);

        if (!$article->exists()) {
            return false;
        }

        if (!$article->isInEdit()) {
            $article->setInEdit();
            return true;
        }

        $this->returnDataObj->articleCode = 1;
        $data = $article->getInEdit();

        if (is_array($data)) {
            $user = new \fpcm\model\users\author($data[1]);

            $this->returnDataObj->username = $user->exists() ? $user->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND');
        }

        return true;
    }

    private function getNotifications()
    {
        $notifications = new \fpcm\model\theme\notifications();
        $notifications->prependSystemNotifications();
        
        /* @var $result \fpcm\module\eventResult */
        $result = $this->events->trigger('ajaxRefresh', $notifications);
        
        if (!$result->getSuccessed() || !$result->getContinue()) {
            $this->returnDataObj->notificationCount = $notifications->count();
            $this->returnDataObj->notifications = (string) $notifications;
            return false;
        }

        /* @var $notifications \fpcm\model\theme\notifications */
        $notifications = $result->getData();
        
        $this->returnDataObj->notificationCount = $notifications->count();
        $this->returnDataObj->notifications = (string) $notifications;
        
    }

}
