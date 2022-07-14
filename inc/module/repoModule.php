<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module base model
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 */
class repoModule extends module {

    /**
     * Initialize object with database data
     * @param object $result
     * @return bool
     */
    public function createFromRepoArray(array $result) : bool
    {
        $this->id = isset($result->id) ? $result['id'] : false;
        $this->config = new config($this->mkey, $result);

        return true;
    }

    /**
     * Initialize repo module
     * @return bool
     */
    public function init() : bool
    {
        return true;
    }

}
