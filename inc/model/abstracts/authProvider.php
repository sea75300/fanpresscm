<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * authProvider base class
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class authProvider extends model {

    /**
     * @var int
     */
    const AUTH_2FA_ERROR = -400;

    /**
     * Execute authentication
     * @param array $param
     * @return mixed
     */
    abstract public function authenticate(array $param);

    /**
     * Return template for active auth provider
     * @return string
     */
    abstract public function getLoginTemplate();

    /**
     * Runs additional two 2FA-checks
     * @param mixed $param
     * @return bool
     */
    public function twoFactorAuth($param)
    {
        return true;
    }
}
