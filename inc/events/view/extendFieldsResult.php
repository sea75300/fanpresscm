<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * 
 * Result object for extendToolbar
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.1.0-a1
 */
final class extendFieldsResult {

    /**
     * Button list
     * @var array
     */
    public $fields = [];

    /**
     * Area name
     * @var string
     */
    public $area = '';

}
