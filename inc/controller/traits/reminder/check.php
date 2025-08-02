<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\reminder;

/**
 * Reminder permission check trait
 *
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
trait check
{

    /**
     * Remidner object type
     * @var string
     */
    protected string $type = '';

    /**
     * 
     * @return bool
     */
    public function checkFiles()
    {
        if (!$this->permissions->uploads->visible) {
            return false;
        }

        $this->type = \fpcm\model\files\image::class;
        return true;
    }

}
