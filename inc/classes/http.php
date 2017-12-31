<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * FanPress CM HTTP request class
     * Handler für $_GET, $_POST, $_COOKIE, $_FILES, $_SESSION
     * 
     * @package fpcm\classes\http
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     */ 
    final class http {

        /**
         * HTTP Filter strip_tags
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_STRIPTAGS = 1;

        /**
         * HTTP Filter htmlspecialchars
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_HTMLSPECIALCHARS = 2;

        /**
         * HTTP Filter htmlentities
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_HTMLENTITIES = 3;

        /**
         * HTTP Filter stripslashes
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_STRIPSLASHES = 4;

        /**
         * HTTP Filter htmlspecialchars_decode
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_HTMLSPECIALCHARS_DECODE = 5;

        /**
         * HTTP Filter html_entity_decode
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_HTMLENTITY_DECODE = 6;

        /**
         * HTTP Filter trim
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_TRIM = 7;

        /**
         * HTTP Filter json_decode
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_JSON_DECODE = 8;

        /**
         * HTTP Filter intval
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_CASTINT = 9;

        /**
         * HTTP Filter crypt::decrypt
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_DECRYPT = 10;

        /**
         * HTTP Filter urldecode
         * @since FPCM 3.5.2
         */
        const FPCM_REQFILTER_URLDECODE = 11;
        
        /**
         * HTTP-Reuqest aus $_REQUEST und $_COOKIE
         * @var array
         */
        private static $request;

        /**
         * HTTP initialisieren
         */
        public static function init() {
            self::$request = array_merge($_REQUEST, $_COOKIE);
        }

        /**
         * Daten aus $_REQUEST, $_POST, $_GET, $_COOKIE auslesen
         * Ersetz für direkten Zugriff auf $_REQUEST, $_POST, $_GET, $_COOKIE!!!
         * @param string $varname Variablenname
         * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
         * @return mixed null wenn Variable nicht gesetzt
         */
        public static function get($varname = null, array $filter = [self::FPCM_REQFILTER_STRIPTAGS,self::FPCM_REQFILTER_STRIPSLASHES,self::FPCM_REQFILTER_TRIM]) {
            if (is_null($varname)) return self::$request;
            $returnVal = (isset(self::$request[$varname])) ? self::filter(self::$request[$varname], $filter) : null;
            return $returnVal;            
        }

        /**
         * Daten aus $_POST
         * Ersetz für direkten Zugriff auf $_POST
         * @param string $varname Variablenname
         * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
         * @return mixed null wenn Variable nicht gesetzt
         */
        public static function postOnly($varname = null, array $filter = [self::FPCM_REQFILTER_STRIPTAGS,self::FPCM_REQFILTER_STRIPSLASHES,self::FPCM_REQFILTER_TRIM]) {
            if (is_null($varname)) return $_POST;
            $returnVal  = (isset($_POST[$varname])) ? self::filter($_POST[$varname], $filter) : null;
            return $returnVal;
        }

        /**
         * Daten aus $_GET
         * Ersetz für direkten Zugriff auf $_GET
         * @param string $varname Variablenname
         * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
         * @return mixed null wenn Variable nicht gesetzt
         */
        public static function getOnly($varname = null, array $filter = [self::FPCM_REQFILTER_STRIPTAGS,self::FPCM_REQFILTER_STRIPSLASHES,self::FPCM_REQFILTER_TRIM]) {
            if (is_null($varname)) return $_GET;
            $returnVal  = (isset($_GET[$varname])) ? self::filter($_GET[$varname], $filter) : null;
            return $returnVal;
        }

        /**
         * Daten aus $_COOKIE
         * Ersetz für direkten Zugriff auf $_COOKIE
         * @param string $varname Variablenname
         * @param array $filter Filter vor Rückgabe durchführen, @see http::filter()
         * @return mixed null wenn Variable nicht gesetzt
         */
        public static function cookieOnly($varname = null, array $filter = [self::FPCM_REQFILTER_STRIPTAGS,self::FPCM_REQFILTER_STRIPSLASHES,self::FPCM_REQFILTER_TRIM]) {
            return (isset($_COOKIE[$varname])) ? self::filter($_COOKIE[$varname], $filter) : null;
        }
        
        /**
         * Gibt IP-Adresse des aktuellen Nutzers zurück
         * @return string
         */
        public static function getIp() {
            return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        }
        
        /**
         * Gibt HTTP-Host des aktuellen Nutzers zurück
         * @return string
         */
        public static function getHttpHost() {
            return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        }
        
        /**
         * Gibt Page-Token-Informationen zurück
         * @return string
         */
        public static function getPageToken() {
            return self::postOnly(security::getPageTokenFieldName());
        }

        /**
         * Gibt Inhalt von Dateiupload via PHP zurück
         * @return array
         */
        public static function getFiles() {
            return (isset($_FILES['files']) && count($_FILES['files'])) ? $_FILES['files'] : null;
        }

        /**
         * Ließt Daten aus $_SESSION
         * @param string $varName
         * @return mixed false, wenn Variable nicht gesetzt
         */
        public static function getSessionVar($varName) {  
            return isset($_SESSION[$varName]) ? $_SESSION[$varName] : false;            
        }

        /**
         * Schreibt Daten in $_SESSION
         * @param string $varName
         * @param mixed $value
         */
        public static function setSessionVar($varName, $value) {            
            $_SESSION[$varName] = $value;
        }

        /**
         * Führt Filter auf einen String aus,
         * Verwendung v. A. für Werte aus Formularen, etc.
         * 
         * @param string $filterString
         * @param array $filters
         * * 1 - strip_tags
         * * 2 - htmlspecialchars
         * * 3 - htmlentities
         * * 4 - stripslashes
         * * 5 - htmlspecialchars_decode
         * * 6 - html_entity_decode
         * * 7 - trim
         * * 8 - json_decode
         * * 9 - (int)-Cast
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
        public static function filter($filterString, array $filters) {

            if (!$filterString) {
                return $filterString;
            }
            
            if (is_array($filterString)) {  
                foreach ($filterString as $value) {
                    static::filter($value, $filters);
                }
                return $filterString;
            }
            
            $allowedTags = (isset($filters['allowedtags'])) ? $filters['allowedtags'] : '';
            $htmlMode    = isset($filters['mode']) ? $filters['mode'] : (ENT_COMPAT | ENT_HTML401);
            foreach ($filters as $filter) {          
                $filter = (int) $filter;
                switch ($filter) {
                    case self::FPCM_REQFILTER_STRIPTAGS :
                        $filterString = strip_tags($filterString, $allowedTags);
                    break;
                    case self::FPCM_REQFILTER_HTMLSPECIALCHARS :
                        $filterString = htmlspecialchars($filterString, $htmlMode);
                    break;
                    case self::FPCM_REQFILTER_HTMLENTITIES :
                        $filterString = htmlentities($filterString, $htmlMode);
                    break;
                    case self::FPCM_REQFILTER_STRIPSLASHES :
                        $filterString = stripslashes($filterString);
                    break;
                    case self::FPCM_REQFILTER_HTMLSPECIALCHARS_DECODE :
                        $filterString = htmlspecialchars_decode($filterString, $htmlMode);
                    break;
                    case self::FPCM_REQFILTER_HTMLENTITY_DECODE :
                        $filterString = html_entity_decode($filterString, $htmlMode);
                    break;
                    case self::FPCM_REQFILTER_TRIM :
                        $filterString = trim($filterString);
                    break;
                    case self::FPCM_REQFILTER_JSON_DECODE :
                        $filterString = json_decode($filterString, ($filters['object'] ? false : true));
                    break;
                    case self::FPCM_REQFILTER_CASTINT :
                        $filterString = (int) $filterString;
                    break;
                    case self::FPCM_REQFILTER_DECRYPT :
                        $crypt = new crypt();
                        $filterString = $crypt->decrypt($filterString);
                    break;
                    case self::FPCM_REQFILTER_URLDECODE :
                        $filterString = urldecode($filterString);
                    break;
                }
            }            
            
            return $filterString;             
        }
        
    }
