<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Trait to colorize status icons based on their status
 * 
 * @package fpcm\model\traits\
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.0-a1
 */
trait statusIcons {
    
    final protected function getStatusColor(\fpcm\view\helper\icon $icon, bool|int $status) : \fpcm\view\helper\icon
    {

        $class = match ($status) {
            1, true => 'text-info',
            0, false => 'text-secondary',
        };

        return $icon->setClass($class);
        
    }

}
