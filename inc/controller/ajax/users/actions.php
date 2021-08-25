<?php

/**
 * Login controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\users;

class actions extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @var int
     */
    protected $oid;

    /**
     * 
     * @var string
     */
    protected $preFn;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        $this->oid = $this->request->fromPOST('oid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $res = $this->processByParam();
        if ($res === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setReturnData([])->fetch();
        }

        $this->response->setReturnData($res)->fetch();
    }

    /**
     * Benutzer deaktivieren
     * @return void
     */
    protected function processDisableUser()
    {
        if (!$this->permissions->system->users) {
            return new \fpcm\view\message('SAVE_FAILED_USER_DISABLE', \fpcm\view\message::TYPE_ERROR);
        }
        
        if ($this->oid === $this->session->getUserId()) {
            return new \fpcm\view\message('SAVE_FAILED_USER_DISABLE_OWN', \fpcm\view\message::TYPE_ERROR);
        }
        
        if ((new \fpcm\model\users\userList)->countActiveUsers() == 1) {
            return new \fpcm\view\message('SAVE_FAILED_USER_DISABLE_LAST', \fpcm\view\message::TYPE_ERROR);
        }

        if (!(new \fpcm\model\users\author($this->oid))->disable()) {
            return new \fpcm\view\message('SAVE_FAILED_USER_DISABLE', \fpcm\view\message::TYPE_ERROR);
        }

        return new \fpcm\view\message('SAVE_SUCCESS_USER_DISABLE', \fpcm\view\message::TYPE_NOTICE);
    }

    /**
     * Benutzer aktivieren
     * @return void
     */
    protected function processEnableUser()
    {
        if (!$this->permissions->system->users) {
            return new \fpcm\view\message('SAVE_FAILED_USER_ENABLE', \fpcm\view\message::TYPE_ERROR);
        }
        
        if ($this->oid === $this->session->getUserId()) {
            return new \fpcm\view\message('SAVE_SUCCESS_USER_ENABLE', \fpcm\view\message::TYPE_ERROR);
        }

        if (!(new \fpcm\model\users\author($this->oid))->enable()) {
            return new \fpcm\view\message('SAVE_FAILED_USER_ENABLE', \fpcm\view\message::TYPE_ERROR);        
        }

        return new \fpcm\view\message('SAVE_SUCCESS_USER_ENABLE', \fpcm\view\message::TYPE_NOTICE);
    }

    /**
     * Benutzer löschen
     * @return bool
     */
    protected function processDeleteUser()
    {
        if (!$this->permissions->system->users) {
            return new \fpcm\view\message('DELETE_FAILED_USERS', \fpcm\view\message::TYPE_ERROR);
        }
        
        if ($this->oid === $this->session->getUserId()) {
            return new \fpcm\view\message('DELETE_FAILED_USERS_OWN', \fpcm\view\message::TYPE_ERROR);
        }
        
        if ((new \fpcm\model\users\userList)->countActiveUsers() == 1) {
            return new \fpcm\view\message('DELETE_FAILED_USERS_LAST', \fpcm\view\message::TYPE_ERROR);
        }
        
        $moveTo = $this->request->fromPOST('moveTo', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $moveAction = $this->request->fromPOST('moveAction');
        
        if (!$moveAction) {
            
            fpcmLogSystem("(\fpcm\model\users\author({$this->oid}))->delete()");
            
//            if (!(new \fpcm\model\users\author($this->oid))->delete()) {
//                return new \fpcm\view\message('DELETE_FAILED_USERS', \fpcm\view\message::TYPE_ERROR);
//            }

            return new \fpcm\view\message('DELETE_SUCCESS_USERS', \fpcm\view\message::TYPE_NOTICE);
        }
        
        if (!in_array($moveAction, ['move', 'delete']) || ($moveAction === 'move' && !$moveTo)) {
            return new \fpcm\view\message('DELETE_FAILED_USERSARTICLES', \fpcm\view\message::TYPE_ERROR);            
        }
        
        if (!(new \fpcm\model\users\author($this->oid))->delete()) {
            return new \fpcm\view\message('DELETE_FAILED_USERS', \fpcm\view\message::TYPE_ERROR);
        }


        $articleList = new \fpcm\model\articles\articlelist();
        switch ($moveAction) {
            case 'move' :
                fpcmLogSystem("articleList->moveArticlesToUser({$this->oid}, {$moveTo});");
//                $articleList->moveArticlesToUser($this->oid, $moveTo);
                break;
            case 'delete' :
                fpcmLogSystem("articleList->deleteArticlesByUser({$this->oid});");
//                $articleList->deleteArticlesByUser($this->oid);
                break;
        }
        
        return new \fpcm\view\message('DELETE_SUCCESS_USERS', \fpcm\view\message::TYPE_NOTICE);
    }

}

?>
