<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * System config item object
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4.3-rc2
 */
final class configItem {

    /**
     * Config option name
     * @var string
     */
    protected $config_name = '';

    /**
     * Config option value
     * @var mixed
     */
    protected $config_value = '';

    /**
     * Module key for module-defined config options
     * @var string
     */
    protected $modulekey = '';

    /**
     * Constructor
     * @param string $name
     * @param type $value
     * @param string $moduleKey
     */
    function __construct(string $name, $value = '', string $moduleKey = '')
    {
        $this->config_name = $name;
        $this->config_value = is_array($value) || is_object($value) ? json_encode($value) : $value;
        $this->modulekey = $moduleKey;
    }

    /**
     * Returns data array
     * @return array
     */
    final public function getData() : array
    {
        return get_object_vars($this);
    }

}
