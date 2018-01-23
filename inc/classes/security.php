<?php
    /**
     * FanPress CM 3.x
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
         * Cache-Modul für Page Tokens
         * @since FPCM 3.4
         */
        const pageTokenCacheModule = "pgtkn";
        
        /**
         * Session Cookie Name
         * @since FPCM 3.6
         */
        private static $cookieName;
        
        /**
         * Session Cookie Name
         * @since FPCM 3.6
         */
        private static $pageTokenName;
        
        /**
         * Cookie-Name zurückgeben
         * @return string
         */
        public static function getSessionCookieName()
        {
            if (self::$cookieName) {
                return self::$cookieName;
            }

            self::$cookieName = 'fpcm_sid'.md5(dirs::getRootUrl().'_'.date('d-m-Y'));
            if (isset($_COOKIE[self::$cookieName])) {
                return self::$cookieName;
            }
            
            $conf = baseconfig::getSecurityConfig();
            if (!is_array($conf) || !isset($conf['cookieName'])) {
                return false;
            }
            
            self::$cookieName = 'fpcmsid'.$conf['cookieName'];
            return self::$cookieName;
        }
        
        /**
         * Page-Token-Feld-Name zurückgeben
         * @param string $overrideModule
         * @return string
         */
        public static function getPageTokenFieldName($overrideModule = '')
        {
            if (self::$pageTokenName) {
                return self::$pageTokenName;
            }
            
            $conf                   = baseconfig::getSecurityConfig();
            self::$pageTokenName    = hash(self::defaultHashAlgo, $conf['pageTokenBase'].(trim($overrideModule) ? trim($overrideModule) : http::getOnly('module')));

            return self::$pageTokenName;
        }
        
        /**
         * gibt Inhalt von Session cookie zurück
         * @return string
         */
        public static function getSessionCookieValue()
        {
            $value = http::cookieOnly(self::getSessionCookieName(), array(1,4,7));
            if (substr($value, 0, 3) !== '_$$') {
                return $value;
            }

            $crypt = new crypt();
            return $crypt->decrypt($value);
        }
                
        /**
         * gibt Inhalt von Session cookie zurück
         * @return string
         */
        public static function createSessionId()
        {
            return hash(self::defaultHashAlgo, self::getSecureBaseString());
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
         * Passwort-Salt erzeugen
         * @param string $additional
         * @return string
         */
        public static function createSalt($additional = '')
        {
            return '$5$'.substr(hash(self::defaultHashAlgo, self::getSecureBaseString()), 0, 16).'$';
        }
        
        /**
         * Erzeugt Page-Token zur Absicherung gegen CSRF-Angriffe
         * @param bool $overrideModule
         * @return string
         */
        public static function createPageToken($overrideModule = '')
        {
            $str        = hash(self::defaultHashAlgo, uniqid(true, __FUNCTION__).mt_rand(). microtime(true));
            $cacheName  = self::pageTokenCacheModule.'/'.self::getPageTokenFieldName($overrideModule);

            /* @var $cache cache */
            $cache      = \fpcm\classes\loader::getObject('fpcm\classes\cache');
            $cache->cleanup($cacheName);            
            $cache->write($cacheName, \fpcm\classes\loader::getObject('fpcm\classes\crypt')->encrypt($str));
   
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
                'cookieName'    => hash(self::defaultHashAlgo, 'cookie'.uniqid('fpcm', true).dirs::getRootUrl()),
                'pageTokenBase' => hash(self::defaultHashAlgo, 'pgToken'.dirs::getRootUrl().'$')
            ];
            
            return file_put_contents(dirs::getDataDirPath(dirs::DATA_CONFIG, 'sec.php'), '<?php'.PHP_EOL.' $config = '.var_export($secConf, true).PHP_EOL.'?>');
            
        }

        /**
         * Erzeugt Basis-String für Hash-Funktionen
         * @return string
         */
        private static function getSecureBaseString()
        {
            $md5base = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : __FILE__;
            return uniqid('fpcm', true).'#'.microtime(true).'#'.md5($md5base).'#'.mt_rand();
        }
        
    }
