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
     * Config für Sicherheitsconfig
     * @return bool
     * @since FPCM 3.6
     */
    public static function initSecurityConfig()
    {
        $secConf = baseconfig::getSecurityConfig();
        if (is_array($secConf) && count($secConf)) {
            return true;
        }

        $secConf = var_export([
            'cookieName' => tools::getHash('cookie' . self::getSecureBaseString() . dirs::getRootUrl()),
        ],
        true);

        return file_put_contents(dirs::getDataDirPath(dirs::DATA_CONFIG, 'sec.php'), '<?php' . PHP_EOL . ' $config = ' . $secConf . PHP_EOL . '?>');
    }

    /**
     * Exit script execution on request
     * @param array $vars
     * @return boolean
     * @since FPCM 4.3
     */
    public static function requestExit(array $vars) : bool
    {
        if (!defined('FPCM_REQUEST_EXIT') || !FPCM_REQUEST_EXIT) {
            return true;
        }

        define(
            'FPCM_EXITREQUEST_REGEX',
            '/('.implode('|', ['SELECT', 'CHR', 'UPPER', 'INFORMATION_SCHEMA',
                               '\sAND', '\sOR', 'UNION', 'CONCAT', 'THEN']).')+/'
        );
        
        $result = array_map(function($var) {

            $varData = http::get($var);
            if (is_array($varData)) {
                return 0;
            }

            $res = preg_match_all(FPCM_EXITREQUEST_REGEX, $varData);
            return ($res === false || $res === 0) ? 0 : 1;
        }, $vars);

        if (!is_array($result)) {
            return false;
        }
        
        $count = count(array_keys($result, 1));
        if ($count) {
            sleep($count > 30 ? 30 : $count);
            print date(DATE_W3C).' - Bad request';
            http_response_code(400);
            return false;
        }

        return true;
    }

    /**
     * Erzeugt Basis-String für Hash-Funktionen
     * @return string
     */
    private static function getSecureBaseString()
    {
        try {            
            $data = bin2hex(random_bytes(64));
        } catch (\Exception $exc) {
            $md5base = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : __FILE__;
            $data = uniqid('fpcm', true) . '#' . microtime(true) . '#' . md5($md5base) . '#' . mt_rand();
        }

        return $data;
    }

    /**
     * gibt Inhalt von Session cookie zurück
     * @return string
     * @deprecated since version FPCM 4.3
     */
    public static function createSessionId()
    {
        return tools::getHash(self::getSecureBaseString());
    }

    /**
     * Erzeugt Page-Token zur Absicherung gegen CSRF-Angriffe
     * @param bool $overrideModule
     * @return string
     * @deprecated since version FPCM 4.3
     */
    public static function createPageToken($overrideModule = '')
    {
        return (new \fpcm\classes\pageTokens())->refresh();
    }
}
