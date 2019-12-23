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

        $rollName = $this->getRequestVar('rollname');
        
        if (!trim($rollName)) {
            $this->view->addErrorMessage('SAVE_FAILED_ROLL');
            return true;
        }
        
        $this->userRoll->setRollName($rollName);
        $func = $update ? 'update' : 'save';
        $msg  = $update ? 'edited' : 'added';
        if (call_user_func([$this->userRoll, $func])) {
            $this->redirect('users/list', [$msg => 2, 'rg' => 1]);
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_ROLL');
        return true;
    }
    
    public function process()
    {
        $this->view->assign('tabsHeadline', $this->headlineVar);
        return true;
    }

}

?>
