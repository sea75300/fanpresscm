<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

/**
 * Permission edit controller for single group
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.6
 */
class permissions extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\users\savePermissions;

    /**
     * 
     * @var \fpcm\model\users\userRoll
     */
    private $roll;

    public function isAccessible(): bool
    {
        return $this->permissions->system->users && $this->permissions->system->rolls && $this->permissions->system->permissions;
    }

    protected function getViewPath() : string
    {
        return 'users/permissions';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->rollId = $this->request->getID();

        $this->roll = new \fpcm\model\users\userRoll($this->rollId, false);

        $this->fetchRollPermssions();
        
        if ($this->buttonClicked('permissionsSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        if ($this->buttonClicked('permissionsSave') && $this->checkPageToken) {

            if (!$this->savePermissions()) {
                $this->view->addErrorMessage('SAVE_FAILED_PERMISSIONS');
                return true;
            }
            
            if ($this->rollId == 1) {
                $permissionData['system']['permissions'] = 1;
            }

            $this->view->addNoticeMessage('SAVE_SUCCESS_PERMISSIONS');
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {        
        $this->assignToView();
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setFormAction('users/permissions', [
            'id' => $this->rollId
        ]);

        $this->view->addTabs('permissions', [
            (new \fpcm\view\helper\tabItem('permissions-group'))
                ->setText('USERS_EDIT_PERMISSION', ['rollname' => $this->language->translate($this->roll->getRollName())])
                ->setFile($this->getViewPath() . '.php')
        ], 'm-2');

        $this->view->render();
    }

}

?>
