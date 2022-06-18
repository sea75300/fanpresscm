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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class refresh extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\isAccessibleTrue;
    
    private $ext = false;

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
        $this->runCrons();
        if ($this->ext) {
            exit('{}');
        }

        $this->runSessionCheck();
        $this->runArticleInEdit();
        $this->response->setReturnData($this->returnData)->fetch();
    }

    /**
     * 
     * @return bool
     */
    private function runCrons()
    {
        if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
            fpcmLogCron('Asynchronous cronjob execution was disabled');
            return false;
        }
        
        $cronlist = new \fpcm\model\crons\cronlist();
        $crons = $cronlist->getExecutableCrons();

        if (!count($crons)) {
            return true;
        }

        foreach ($crons as $cron) {
            $cronlist->registerCronAjax($cron);
        }

        $this->returnData['crons'] = true;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function runSessionCheck()
    {
        if (!is_object($this->session)) {
            $this->returnData['sessionCode'] = -1;
            return true;
        }

        if (!$this->session->exists() || $this->ipList->ipIsLocked($this->getIpLockedModul()) ) {
            $this->returnData['sessionCode'] = 0;
            return true;
        }

        $this->returnData['sessionCode'] = 1;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function runArticleInEdit()
    {
        $this->returnData['articleCode'] = 0;
        $this->returnData['articleUser'] = false;

        $articleId = $this->request->fetchAll('articleId', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($this->returnData['sessionCode'] < 1 || !$this->permissions->editArticles() || !$articleId) {
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
        
        $this->returnData['articleCode'] = 1;
        $data = $article->getInEdit();

        if (is_array($data)) {
            $user = new \fpcm\model\users\author($data[1]);

            $this->returnData['username'] = $user->exists() ? $user->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND');
        }

        return true;
    }

}
