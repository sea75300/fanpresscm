<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\users;

/**
 * Author image processing trait
 * 
 * @package fpcm\controller\traits\users\authorImages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait savePermissions {

    /**
     *
     * @var int
     */
    protected $rollId;

    /**
     * Permission object
     * @var \fpcm\model\permissions\permissions
     */
    protected $permissionObj;
    
    /**
     * 
     * @return bool
     */
    protected function savePermissions() : bool
    {        
        if (!$this->permissionObj instanceof \fpcm\model\permissions\permissions) {
            return false;
        }
        
        $permissionData = $this->getRequestVar('permissions', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);
        
        if (!is_array($permissionData)) {
            return true;
        }

        if ($this->rollId == 1) {
            $permissionData['system']['users'] = 1;
            $permissionData['system']['rolls'] = 1;
            $permissionData['system']['permissions'] = 1;
        }

        $this->permissionObj->setPermissionData( array_replace_recursive($this->permissions->getPermissionSet() , $permissionData) );
        return $this->permissionObj->update();
    }
    
    protected function fetchRollPermssions() : bool
    {
        $this->permissionObj = new \fpcm\model\permissions\permissions($this->rollId);
        return true;
    }
    
    protected function assignToView() : bool
    {
        $this->view->addJsFiles(['permissions.js']);

        $this->view->assign('rollId', $this->rollId);
        $this->view->assign('permissions', $this->permissionObj->getPermissionData());
        return true;
    }

}

?>