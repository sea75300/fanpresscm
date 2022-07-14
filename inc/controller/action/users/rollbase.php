<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

abstract class rollbase extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\theme\nav\users;

    /**
     *
     * @var \fpcm\model\users\userRoll
     */
    protected $userRoll;

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
        $this->userRoll->setCodex($this->request->fromPOST('rollcodex'));
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
        $tabs = [
            (new \fpcm\view\helper\tabItem('roll'))->setText($this->headlineVar)->setFile('users/rolledit.php')           
        ];
        
        if ( $this->permissions->system->permissions && $this->userRoll->getId() ) {
            $tabs[] = (new \fpcm\view\helper\tabItem('permission'))->setText('HL_OPTIONS_PERMISSIONS')->setFile('users/permissions_editor.php');
        }
        
        $this->view->addTabs('roll', $tabs, '', $this->getActiveTab());

        return true;
    }

}

?>
