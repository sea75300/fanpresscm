<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * Cookie setter onject
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 4.5
 */
final class cookie {

    /**
     * cookie value
     * @var string
     */
    private $name = '';

    /**
     * setcookie flags
     * @var array
     */
    private $flags = [
        'expires' => 3600,
        'path' => '/',
        'domain' => '',
        'samesite' => 'Lax',
        'secure' => false,
        'httponly' => true
    ];

    /**
     * Use legacy mode to set cookie
     * @var bool
     */
    private $legacySet = false;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->legacySet = version_compare(PHP_VERSION, '7.3', '<') ? true : false;
        $this->flags['expires'] += time();
        $this->flags['domain'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
    }

    /**
     * Set cookie value
     * @param string $value
     * @return bool
     */
    public function set(string $value) : bool
    {
        if ($this->legacySet) {
            return setcookie(
                $this->name,
                $value,
                $this->flags['expires'],
                $this->flags['path'],
                $this->flags['domain'],
                $this->flags['secure'],
                $this->flags['httponly']
            );
        }

        return setcookie($this->name, $value, $this->flags);
    }

    /**
     * Set expiration time
     * @param int $expires
     * @return $this
     */
    public function setExpires(int $expires) : cookie
    {
        $this->flags['expires'] = $expires;
        return $this;
    }

    /**
     * Set cookie path
     * @param string $path
     * @return $this
     */
    public function setPath(string $path) : cookie
    {
        $this->flags['path'] = $path;
        return $this;
    }

    /**
     * Set cookie domain
     * @param string $domain
     * @return $this
     */
    public function setDomain(string $domain) : cookie
    {
        $this->flags['domain'] = $domain;
        return $this;
    }

    /**
     * Set Same-site policy
     * @param string $samesite
     * @return $this
     */
    public function setSamesite(string $samesite) : cookie
    {
        $this->flags['samesite'] = $samesite;
        return $this;
    }

    /**
     * Set cookie is only available via HTTPS
     * @param bool $secure
     * @return $this
     */
    public function setSecure(bool $secure) : cookie
    {
        $this->flags['secure'] = $secure;
        return $this;
    }

    /**
     * Set cookie is not available via Javascript
     * @param bool $httponly
     * @return $this
     */
    public function setHttponly(bool $httponly) : cookie
    {
        $this->flags['httponly'] = $httponly;
        return $this;
    }


}
