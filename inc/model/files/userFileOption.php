<?php
/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * File option objekt
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\files
 */
class userFileOption extends fileOption {
 
    /**
     * Return path type
     * @return string
     */
    protected function getType()
    {
        return \fpcm\classes\dirs::DATA_PROFILES;
    }

}
