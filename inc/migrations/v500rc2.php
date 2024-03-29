<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0.0-rc2
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-rc2
 * @see migration
 */
class v500rc2 extends migration {

    protected function updatePermissionsAfter(): bool {
        
        $obj = new \fpcm\model\permissions\permissions(1);

        $data = $obj->getPermissionData();            
        if ($data['system']['csvimport'] === 1) {
            return true;
        }
        
        $data['system']['csvimport'] = 1;

        $obj->setPermissionData($data);
        return $obj->update();
    }
    
    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.0.0-rc2';
    }

}