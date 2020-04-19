<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FanPress CM HTTP request class
 * Handler für $_GET, $_POST, $_COOKIE, $_FILES, $_SESSION
 * 
 * @package fpcm\classes\http
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @deprecated FPCM 4.4, use new request/ response handler instead
 * @see \fpcm\model\http\request
 * @see \fpcm\model\http\response 
 */
final class http {

    /**
     * HTTP Filter strip_tags
     * @since FPCM 3.5.2
     */
    const FILTER_STRIPTAGS = 1;

    /**
     * HTTP Filter htmlspecialchars
     * @since FPCM 3.5.2
     */
    const FILTER_HTMLSPECIALCHARS = 2;

    /**
     * HTTP Filter htmlentities
     * @since FPCM 3.5.2
     */
    const FILTER_HTMLENTITIES = 3;

    /**
     * HTTP Filter stripslashes
     * @since FPCM 3.5.2
     */
    const FILTER_STRIPSLASHES = 4;

    /**
     * HTTP Filter htmlspecialchars_decode
     * @since FPCM 3.5.2
     */
    const FILTER_HTMLSPECIALCHARS_DECODE = 5;

    /**
     * HTTP Filter html_entity_decode
     * @since FPCM 3.5.2
     */
    const FILTER_HTMLENTITY_DECODE = 6;

    /**
     * HTTP Filter trim
     * @since FPCM 3.5.2
     */
    const FILTER_TRIM = 7;

    /**
     * HTTP Filter json_decode
     * @since FPCM 3.5.2
     */
    const FILTER_JSON_DECODE = 8;

    /**
     * HTTP Filter intval
     * @since FPCM 3.5.2
     */
    const FILTER_CASTINT = 9;

    /**
     * HTTP Filter crypt::decrypt
     * @since FPCM 3.5.2
     */
    const FILTER_DECRYPT = 10;

    /**
     * HTTP Filter urldecode
     * @since FPCM 3.5.2
     */
    const FILTER_URLDECODE = 11;

    /**
     * HTTP Filter base64_decode
     * @since FPCM 4
     */
    const FILTER_BASE64DECODE = 12;

    /**
     * HTTP Filter ucfirst
     * @since FPCM 4
     */
    const FILTER_FIRSTUPPER = 13;

    /**
     * Regex filter
     * @since FPCM 4.3
     */
    const FILTER_REGEX = 14;

    /**
     * Regex replace filter
     * @since FPCM 4.3
     */
    const FILTER_REGEX_REPLACE = 15;

    /**
     * HTTP-Reuqest aus $_REQUEST und $_COOKIE
     * @var array
     */
    private static $request;

    /**
     * HTTP initialisieren
     */
    public static function init()
    {
        self::$request = array_merge($_REQUEST, $_COOKIE);
    }

    /**
     * Daten aus $_REQUEST, $_POST, $_GET, $_COOKIE auslesen
     * Ersetz für direkten Zugriff auf $_REQUEST, $_POST, $_GET, $_COOKIE!!!
     * @param string $varname Variablenname
     * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
     * @return mixed null wenn Variable nicht gesetzt
     */
    public static function get($varname = null, array $filter = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        if ($varname === null) {
            return self::$request;
        }

        return (isset(self::$request[$varname])) ? self::filter(self::$request[$varname], $filter) : null;
    }

    /**
     * Daten aus $_POST
     * Ersetz für direkten Zugriff auf $_POST
     * @param string $varname Variablenname
     * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
     * @return mixed null wenn Variable nicht gesetzt
     */
    public static function postOnly($varname = null, array $filter = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        if ($varname === null) {
            return $_POST;
        }

        return (isset($_POST[$varname])) ? self::filter($_POST[$varname], $filter) : null;
    }

    /**
     * Daten aus $_GET
     * Ersetz für direkten Zugriff auf $_GET
     * @param string $varname Variablenname
     * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
     * @return mixed null wenn Variable nicht gesetzt
     */
    public static function getOnly($varname = null, array $filter = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        if ($varname === null) {
            return $_GET;
        }

        return (isset($_GET[$varname])) ? self::filter($_GET[$varname], $filter) : null;
    }

    /**
     * Daten aus $_COOKIE
     * Ersetz für direkten Zugriff auf $_COOKIE
     * @param string $varname Variablenname
     * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
     * @return mixed null wenn Variable nicht gesetzt
     */
    public static function cookieOnly($varname = null, array $filter = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        return (isset($_COOKIE[$varname])) ? self::filter($_COOKIE[$varname], $filter) : null;
    }

    /**
     * Gibt IP-Adresse des aktuellen Nutzers zurück
     * @return string
     */
    public static function getIp()
    {
        $return = explode(', ',  $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1' );
        return $return[0];
    }

    /**
     * Gibt HTTP-Host des aktuellen Nutzers zurück
     * @return string
     */
    public static function getHttpHost()
    {
        return $_SERVER['HTTP_HOST'] ?? 'localhost';
    }

    /**
     * Gibt Inhalt von Dateiupload via PHP zurück
     * @return array
     */
    public static function getFiles()
    {
        return (isset($_FILES['files']) && count($_FILES['files'])) ? $_FILES['files'] : null;
    }

    /**
     * Ließt Daten aus $_SESSION
     * @param string $varName
     * @return mixed false, wenn Variable nicht gesetzt
     */
    public static function getSessionVar($varName)
    {
        return isset($_SESSION[$varName]) ? $_SESSION[$varName] : false;
    }

    /**
     * Schreibt Daten in $_SESSION
     * @param string $varName
     * @param mixed $value
     */
    public static function setSessionVar($varName, $value)
    {
        $_SESSION[$varName] = $value;
    }

    /**
     * Führt Filter auf einen String aus,
     * Verwendung v. A. für Werte aus Formularen, etc.
     * 
     * @param string $filterString
     * @param array $filters @see FILTER_* constants
     * 
     * * allowedtags - erlaubte HTML-Tags für "1 - strip_tags"
     * * mode - Modus für
     * * * "2 - htmlspecialchars"
     * * * "3 - htmlentities",
     * * * "5 - htmlspecialchars_decode"
     * * * "6 - html_entity_decode"
     * * object - json_decode-Ergebnis als Objekt oder Array
     * 
     * @return mixed
     */
    public static function filter($filterString, array $filters)
    {
        if (!$filterString) {
            return $filterString;
        }

        if (is_array($filterString)) {

            foreach ($filterString as &$value) {
                $value = static::filter($value, $filters);
            }

            return $filterString;
        }

        $htmlMode = $filters['mode'] ?? (ENT_COMPAT | ENT_HTML401);

        foreach ($filters as $filter) {
            $filter = (int) $filter;
            switch ($filter) {
                case self::FILTER_STRIPTAGS :
                    $filterString = strip_tags($filterString, $filters['allowedtags'] ?? '');
                    break;
                case self::FILTER_HTMLSPECIALCHARS :
                    $filterString = htmlspecialchars($filterString, $htmlMode);
                    break;
                case self::FILTER_HTMLENTITIES :
                    $filterString = htmlentities($filterString, $htmlMode);
                    break;
                case self::FILTER_STRIPSLASHES :
                    $filterString = stripslashes($filterString);
                    break;
                case self::FILTER_HTMLSPECIALCHARS_DECODE :
                    $filterString = htmlspecialchars_decode($filterString, $htmlMode);
                    break;
                case self::FILTER_HTMLENTITY_DECODE :
                    $filterString = html_entity_decode($filterString, $htmlMode);
                    break;
                case self::FILTER_TRIM :
                    $filterString = trim($filterString);
                    break;
                case self::FILTER_JSON_DECODE :
                    $filterString = json_decode($filterString, ($filters['object'] ? false : true));
                    break;
                case self::FILTER_CASTINT :
                    $filterString = (int) $filterString;
                    break;
                case self::FILTER_DECRYPT :
                    $crypt = new crypt();
                    $filterString = loader::getObject('\fpcm\classes\crypt')->decrypt($filterString);
                    break;
                case self::FILTER_URLDECODE :
                    $filterString = urldecode($filterString);
                    break;
                case self::FILTER_BASE64DECODE :
                    $filterString = base64_decode($filterString);
                    break;
                case self::FILTER_FIRSTUPPER :
                    $filterString = ucfirst($filterString);
                    break;
                case self::FILTER_REGEX :
                    preg_match($filters['regex'] ?? '', $filterString, $match);
                    $filterString = $match;
                    break;
                case self::FILTER_REGEX_REPLACE :
                    $filterString = preg_filter($filters['regex'] ?? '', $filters['regexReplace'] ?? '', $filterString);
                    break;
            }
        }

        return $filterString;
    }

}
