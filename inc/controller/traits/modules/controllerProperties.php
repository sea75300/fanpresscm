<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\modules;

/**
 * Dynamic controller properties trait
 * 
 * @package fpcm\controller\traits\modules\controllerProperties
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait controllerProperties {

    private $controllerProperties = [];

    /**
     * Magic get to prevent dynamic property message from modules
     * @param string $name
     * @return mixed
     * @since 5.2.0-rc4
     */
    public function __get(string $name): mixed
    {
        return $this->controllerProperties[$name] ?? null;
    }

    /**
     * Magic set to prevent dynamic property message from modules
     * @param string $name
     * @param mixed $value
     * @return void
     * @since 5.2.0-rc4
     */
    public function __set(string $name, mixed $value): void
    {
        $this->controllerProperties[$name] = $value;
    }

}
