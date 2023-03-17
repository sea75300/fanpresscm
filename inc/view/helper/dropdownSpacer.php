<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Select dropdown menü space
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class dropdownSpacer extends helper {

    /**
     * @ignore
     */
    public function __construct()
    {
        parent::__construct('');
    }

    /**
     * Return item string
     * @return string
     */
    protected function getString(): string
    {        
        return '<li><hr class="dropdown-divider"></li>';
    }

}

?>