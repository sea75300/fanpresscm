<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * 
 * Result object for extendToolbar
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class extendToolbarResult {

    /**
     * Button list
     * @var array
     */
    public $buttons = [];

    /**
     * Area name
     * @var string
     */
    public $area = '';

}
