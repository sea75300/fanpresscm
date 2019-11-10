<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Page token values
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.3
 */
final class pageTokens {

    /**
     * Constructor
     * @return void
     * @ignore
     */
    final public function __construct()
    {
        if (!$this->isActive()) {
            return;
        }

        $this->init();
    }

    /**
     * Validate page token
     * @param string $name
     * @return bool
     */
    final public function validate($name = '') : bool
    {
        if (!$this->isActive()) {
            return true;
        }
        
        if (!trim($name)) {
            $name = 'token/'.http::get('module');
        }

        $token = http::postOnly('token');
        if ($token === null || hash_equals($this->getValue($name), $token)) {
            return true;
        }

        trigger_error('Submitted page token was inavlid.');
        return false;
    }

    /**
     * Refresh page token
     * @param string $name
     * @return string
     */
    final public function refresh($name = '') : string
    {
        if (!$this->isActive()) {
            return '';
        }
        
        if (!trim($name)) {
            $name = 'token/'.http::get('module');
        }

        $_SESSION['pageTokens'][$name] = hash_hmac(security::defaultHashAlgo, bin2hex(random_bytes(32)), crypt::getRandomString());
        return $_SESSION['pageTokens'][$name];
    }

    /**
     * Delete all page tokens
     * @return bool
     */
    final public function delete() : bool
    {
        unset($_SESSION['pageTokens']);
        return true;
    }

    /**
     * Initialize session storage
     * @return bool
     */
    private function init() : bool
    {
        $_SESSION['pageTokens'] = $_SESSION['pageTokens'] ?? [];
        $_SESSION['pageTokens'] = array_slice($_SESSION['pageTokens'], 0, FPCM_PAGETOKEN_MAX);
        return true;
    }

    /**
     * Returns value
     * @param string $name
     * @return null|string
     */
    private function getValue(string $name)
    {
        if (!$this->isActive()) {
            return null;
        }

        return $_SESSION['pageTokens'][$name] ?? null;
    }

    /**
     * Checks if page tokens are in use
     * @return bool
     */
    private function isActive() : bool
    {
        return !defined('FPCM_MODE_NOPAGETOKEN') || session_status() === PHP_SESSION_ACTIVE;
    }

}
