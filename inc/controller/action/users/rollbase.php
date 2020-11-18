<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

abstract class rollbase extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var \fpcm\model\users\userRoll
     */
    protected $userRoll;

    public function isAccessible(): bool
    {
        return $this->permissions->system->users && $this->permissions->system->rolls;
    }

    protected function getHelpLink()
    {
        return 'HL_OPTIONS_USERS';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-users';
    }

    protected function getViewPath() : string
    {
        return 'users/rolledit';
    }

    public function request()
    {
        $this->view->setFieldAutofocus('rollname');
        $this->view->addButton(new \fpcm\view\helper\saveButton('saveRoll'));
        return true;
    }
    
    protected function getRollObject($id = null)
    {
        $this->userRoll = new \fpcm\model\users\userRoll($id);
        $this->view->assign('userRoll', $this->userRoll);
    }

    protected function save($update = false)
    {
        if (!$this->buttonClicked('saveRoll')) {
            return false;
        }

        if ($this->buttonClicked('permissionsSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        $rollName = $this->request->fromPOST('rollname');
        
        if (!trim($rollName)) {
            $this->view->addErrorMessage('SAVE_FAILED_ROLL');
            return true;
        }
        
        $this->userRoll->setRollName($rollName);
        $func = $update ? 'update' : 'save';
        $msg  = $update ? 'edited' : 'added';

        $result = call_user_func([$this->userRoll, $func]);
        $errMsg = 'SAVE_FAILED_ROLL';

        if ($update && $result && $this->permissions->system->permissions && !$this->savePermissions()) {
            $errMsg = 'SAVE_FAILED_PERMISSIONS';
            $result = false;
        }
        else  if ($result === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            $errMsg = 'SAVE_FAILED_ROLL_EXISTS';
            $result = false;
        }

        if ($result) {
            $this->redirect('users/list', [$msg => 2, 'rg' => 1]);
            return true;
        }
        
        $this->view->addErrorMessage($errMsg);
        return true;
    }
    
    public function process()
    {
        $this->view->assign('tabsHeadline', $this->headlineVar);
        return true;
    }

}

?>
