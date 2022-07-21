<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\system;

/**
 * Dashboard permissions trait
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
trait dashPermissions
{
    /**
     * Check container permissions
     * @param \fpcm\model\abstracts\dashcontainer $obj
     * @return bool
     */
    private function checkPermissions(\fpcm\model\abstracts\dashcontainer $obj) : bool
    {
        if ($obj instanceof \fpcm\model\interfaces\isAccessible) {
            return $obj->isAccessible();
        }
        
        $perm = $obj->getPermissions();
        if (!count($perm)) {
            return true;
        }

        foreach ($perm as $mod => $vals) {
            
            if (!is_array($vals)) {
                $vals = [$vals];
            }

            foreach ($vals as $val) {
                
                $res = $this->permissions->{$mod}->{$val};
                if ($val) {
                    break;
                }
                
            }
            
            if (!$res) {
                return false;
            }
            
        }
        
        return true;
 
    }

}
