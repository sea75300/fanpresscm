<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users\roll;

abstract class base
extends
    \fpcm\controller\abstracts\controller
implements
    \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\theme\nav\users;

    /**
     *
     * @var \fpcm\model\users\userRoll
     */
    protected $userRoll;

    protected $update = false;

    public function isAccessible(): bool
    {
        return $this->permissions->system->users && $this->permissions->system->rolls;
    }

    protected function getViewPath() : string
    {
        return 'users/rolledit';
    }

    public function request()
    {
        return true;
    }

    protected function getRollObject($id = null)
    {
        $this->userRoll = new \fpcm\model\users\userRoll($id);
        $this->view->assign('userRoll', $this->userRoll);
    }

    public function process()
    {
        $this->initButtons();

        $tabs = [
            (new \fpcm\view\helper\tabItem('roll'))->setText($this->headlineVar)->setFile('users/rolledit.php')
        ];

        if ( $this->permissions->system->permissions && $this->userRoll->getId() ) {
            $tabs[] = (new \fpcm\view\helper\tabItem('permission'))->setText('HL_OPTIONS_PERMISSIONS')->setFile('users/permissions_editor.php');
        }

        $this->view->addTabs('roll', $tabs, '', $this->getActiveTab());

        return true;
    }

    private function initButtons()
    {

        $buttons = [
            (new \fpcm\view\helper\saveButton('saveRoll'))->setPrimary()
        ];

        if ($this->userRoll->getId()) {
            $buttons[] = (new \fpcm\view\helper\copyButton('copyRoll'))->setCopyParams($this->userRoll, 'roll');
        }

        if ($this->userRoll->getId() && !$this->userRoll->isSystemRoll()) {
            $buttons[] = (new \fpcm\view\helper\deleteButton('deleteRoll'))->setClickConfirm();
        }


        $this->view->addButtons($buttons);

    }

    /**
     *
     * @return bool
     */
    protected function onsaveRoll()
    {
        $this->initButtons();

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        $rollName = $this->request->fromPOST('rollname');

        if (!trim($rollName)) {
            $this->view->addErrorMessage('SAVE_FAILED_ROLL');
            return true;
        }

        $this->userRoll->setRollName($rollName);
        $this->userRoll->setCodex($this->request->fromPOST('rollcodex'));
        $func = $this->update ? 'update' : 'save';
        $msg  = $this->update ? 4 : 3;

        $result = call_user_func([$this->userRoll, $func]);
        $errMsg = 'SAVE_FAILED_ROLL';

        if ($this->update && $result && $this->permissions->system->permissions && !$this->savePermissions()) {
            $errMsg = 'SAVE_FAILED_PERMISSIONS';
            $result = false;
        }
        else  if ($result === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            $errMsg = 'SAVE_FAILED_ROLL_EXISTS';
            $result = false;
        }

        if ($result) {
            $this->redirect('users/list', ['msg' => $msg, 'rg' => 1]);
            return true;
        }

        $this->view->addErrorMessage($errMsg);


        return true;
    }

    protected function ondeleteRoll()
    {

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        if (!$this->permissions->system->rolls) {
            $this->view->addErrorMessage('DELETE_FAILED_ROLL');
            return false;
        }

        if ($this->userRoll->isSystemRoll()) {
            $this->view->addErrorMessage('DELETE_FAILED_ROLL');
            return false;
        }

        if ($this->userRoll->getId() === $this->session->getCurrentUser()->getRoll()) {
            $this->view->addErrorMessage('DELETE_FAILED_ROLL_OWN');
            return false;
        }

        $count = (new \fpcm\model\users\userRollList)->getUserRolls();
        if (count($count) == 1) {
            $this->view->addErrorMessage('DELETE_FAILED_ROLL_OWN');
            return false;
        }

        if (!$this->userRoll->delete()) {
            $this->view->addErrorMessage('DELETE_FAILED_ROLL');
            return false;
        }

        $this->redirect('users/list', ['rg' => 1]);
        return true;
    }

}
