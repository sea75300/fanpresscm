<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

/**
 * Permission edit controller for single group
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class permissions extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var \fpcm\model\permissions\permissions
     */
    protected $permissionObj;

    /**
     *
     * @var int
     */
    protected $rollId;

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
        $this->rollId = $this->getRequestVar('id', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $this->view->assign('rollId', $this->rollId);

        $roll = new \fpcm\model\users\userRoll($this->rollId);
        $this->view->assign('rollname', $this->language->translate($roll->getRollName()));

        $this->permissionObj = new \fpcm\model\permissions\permissions($this->rollId);

        if ($this->buttonClicked('permissionsSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        $permissionData = $this->getRequestVar('permissions', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if ($this->buttonClicked('permissionsSave') && is_array($permissionData) && $this->checkPageToken) {

            if ($this->rollId == 1) {
                $permissionData['system']['permissions'] = 1;
            }

            $permissionData = array_replace_recursive($this->permissions->getPermissionSet() , $permissionData);
            $this->permissionObj->setPermissionData($permissionData);
            if (!$this->permissionObj->update()) {
                $this->view->addErrorMessage('SAVE_FAILED_PERMISSIONS');
                return true;
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
        $this->view->assign('permissions', $this->permissionObj->getPermissionData());
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setFormAction('users/permissions', [
            'id' => $this->rollId
        ]);

        $this->view->addJsFiles(['permissions.js']);
        $this->view->render();
    }

}

?>
