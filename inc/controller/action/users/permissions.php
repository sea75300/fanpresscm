<?php

/**
 * FanPress CM 3.x
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
class permissions extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\model\system\permissions
     */
    protected $permissionObj;

    protected function getPermissions()
    {
        return ['system' => 'permissions'];
    }

    public function getViewPath()
    {
        return 'users/permissions';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $rollId = $this->getRequestVar('id', [
            \fpcm\classes\http::FPCM_REQFILTER_CASTINT
        ]);

        $this->view->assign('rollId', $rollId);

        $roll = new \fpcm\model\users\userRoll($rollId);
        $this->view->assign('rollname', $this->lang->translate($roll->getRollName()));

        $this->permissionObj = new \fpcm\model\system\permissions($rollId);

        $checkPageToken = $this->checkPageToken();
        if ($this->buttonClicked('permissionsSave') && !$checkPageToken) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        if ($this->buttonClicked('permissionsSave') && !is_null($this->getRequestVar('permissions')) && $checkPageToken) {

            $permissionData = $this->getRequestVar('permissions', [
                \fpcm\classes\http::FPCM_REQFILTER_CASTINT
            ]);

            if ($rollId == 1) {
                $permissionData['system']['permissions'] = 1;
            }

            $permissionData = array_replace_recursive($this->permissions->getPermissionSet(), $permissionData);
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

        $this->view->addJsFiles(['permissions.js']);
        $this->view->render();
    }

}

?>
