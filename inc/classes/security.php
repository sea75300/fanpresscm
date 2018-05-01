<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Security layer class
 * 
 * @package fpcm\classes\security
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class security {

    /**
     * Passwort Check RegEx
     */
    const regexPasswordCkeck = "/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";

    /**
     * Standard-Hash-Algorithmus
     * @since FPCM 3.4
     */
    const defaultHashAlgo = "sha256";

    /**
     * Session Cookie Name
     * @since FPCM 3.6
     */
    private static $cookieName;

    /**
     * Token Cookie Name
     * @since FPCM 3.6
     */
    private static $cookieTokenName;

    /**
     * Cookie-Name zurückgeben
     * @return string
     */
    public static function getSessionCookieName()
    {
        if (self::$cookieName) {
            return self::$cookieName;
        }

        $conf = baseconfig::getSecurityConfig();
        if (!is_array($conf) || !isset($conf['cookieName'])) {
            return false;
        }

        self::$cookieName = 'fpcmsid' . $conf['cookieName'];
        return self::$cookieName;
    }

    /**
     * Cookie-Name zurückgeben
     * @return string
     */
    public static function getTokenCookieName()
    {
        if (self::$cookieTokenName) {
            return self::$cookieTokenName;
        }

        $conf = baseconfig::getSecurityConfig();
        if (!is_array($conf) || !isset($conf['cookieName'])) {
            return false;
        }

        self::$cookieTokenName = 'token' . $conf['cookieName'];
        return self::$cookieTokenName;
    }

    /**
     * Page-Token-Feld-Name zurückgeben
     * @param string $name
     * @return string
     */
    public static function getPageTokenFieldName($name = '')
    {
        $conf = baseconfig::getSecurityConfig();
        return tools::getHash(trim($name) ? trim($name) : $conf['pageTokenBase']).self::getTokenCookieValue();
    }

    /**
     * gibt Inhalt von Session cookie zurück
     * @return string
     */
    public static function getTokenCookieValue()
    {
        return http::cookieOnly(self::getTokenCookieName(), [
            http::FILTER_STRIPTAGS,
            http::FILTER_STRIPSLASHES,
            http::FILTER_TRIM,
            http::FILTER_DECRYPT
        ]);
    }

    /**
     * gibt Inhalt von Session cookie zurück
     * @return string
     */
    public static function getSessionCookieValue()
    {
        return http::cookieOnly(self::getSessionCookieName(), [
            http::FILTER_STRIPTAGS,
            http::FILTER_STRIPSLASHES,
            http::FILTER_TRIM,
            http::FILTER_DECRYPT
        ]);
    }

    /**
     * gibt Inhalt von Session cookie zurück
     * @return string
     */
    public static function createSessionId()
    {
        return tools::getHash(self::getSecureBaseString());
    }

    /**
     * Passwort-Hash erzeugen
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function createPasswordHash($password, $salt)
    {
        return crypt($password, $salt);
    }

    /**
     * Passwort-Hash erzeugen
     * @param string $password
     * @return string
     * @since FPCM 4
     */
    public static function createUserPasswordHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Passwort-Salt erzeugen
     * @param string $additional
     * @return string
     */
    public static function createSalt($additional = '')
    {
        return '$5$' . substr(tools::getHash(self::getSecureBaseString()), 0, 16) . '$';
    }

    /**
     * Erzeugt Page-Token zur Absicherung gegen CSRF-Angriffe
     * @param bool $overrideModule
     * @return string
     */
    public static function createPageToken($overrideModule = '')
    {
        $str = tools::getHash(uniqid(true, __FUNCTION__) . mt_rand() . microtime(true));

        $fopt = new \fpcm\model\files\fileOption(self::getPageTokenFieldName($overrideModule));
        $fopt->write(\fpcm\classes\loader::getObject('\fpcm\classes\crypt')->encrypt([
            'str' => $str,
            'exp'  => time() + FPCM_PAGETOKENCACHE_TIMEOUT
        ]));

        return $str;
    }

    /**
     * Config für Sicherheitsconfig
     * @return boolean
     * @since FPCM 3.6
     */
    public static function initSecurityConfig()
    {
        $secConf = baseconfig::getSecurityConfig();
        if (is_array($secConf) && count($secConf)) {
            return true;
        }

        $secConf = [
            'cookieName' => tools::getHash('cookie' . uniqid('fpcm', true) . dirs::getRootUrl()),
            'pageTokenBase' => tools::getHash('pgToken' . dirs::getRootUrl() . '$')
        ];

        return file_put_contents(dirs::getDataDirPath(dirs::DATA_CONFIG, 'sec.php'), '<?php' . PHP_EOL . ' $config = ' . var_export($secConf, true) . PHP_EOL . '?>');
    }

    /**
     * Erzeugt Basis-String für Hash-Funktionen
     * @return string
     */
    private static function getSecureBaseString()
    {
        $md5base = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : __FILE__;
        return uniqid('fpcm', true) . '#' . microtime(true) . '#' . md5($md5base) . '#' . mt_rand();
    }

}