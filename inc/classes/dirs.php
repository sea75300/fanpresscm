<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\classes;

    /**
     * Directory base config
     * 
     * @package fpcm\classes\baseconfig
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     */     
    final class dirs {

        const DATA_CONFIG   = 'config';
        const DATA_CACHE    = 'cache';
        const DATA_FMTMP    = 'filemanager';
        const DATA_LOGS     = 'logs';
        const DATA_STYLES   = 'styles';
        const DATA_SHARE    = 'share';
        const DATA_SMILEYS  = 'smileys';
        const DATA_TEMP     = 'temp';
        const DATA_UPLOADS  = 'uploads';
        const DATA_DBDUMP   = 'dbdump';
        const DATA_DBSTRUCT = 'dbstruct';
        const DATA_DRAFTS   = 'drafts';
        const DATA_PROFILES = 'profiles';
        const DATA_MODULES  = 'modules';

        const CORE_JS       = 'js';
        const CORE_THEME    = 'theme';
        const CORE_VIEWS    = 'views';

        public static function init()
        {
            self::initDirs();
            self::initUrls();
        }
        
        /**
         * Initialisiert Basis-Ordner
         * @return boolean
         */
        private static function initDirs() {
            $GLOBALS['fpcm']['dir']['base'] = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR;                        
            $GLOBALS['fpcm']['dir']['core'] = $GLOBALS['fpcm']['dir']['base'].'core'.DIRECTORY_SEPARATOR;
            $GLOBALS['fpcm']['dir']['data'] = $GLOBALS['fpcm']['dir']['base'].'data'.DIRECTORY_SEPARATOR;
            $GLOBALS['fpcm']['dir']['inc']  = $GLOBALS['fpcm']['dir']['base'].'inc'.DIRECTORY_SEPARATOR;
            $GLOBALS['fpcm']['dir']['lib']  = $GLOBALS['fpcm']['dir']['base'].'lib'.DIRECTORY_SEPARATOR;

            return true;
        }

        /**
         * Initialisiert Basis-URLs
         * @return boolean
         */
        private static function initUrls() {

            if (baseconfig::isCli()) {
                $GLOBALS['fpcm']['urls']['base'] = '';
                return false;
            }

            $GLOBALS['fpcm']['urls']['base'] = (baseconfig::canHttps() ? 'https://' : 'http://').$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']).'/';
            $GLOBALS['fpcm']['urls']['data'] = $GLOBALS['fpcm']['urls']['base'].'data/';
            $GLOBALS['fpcm']['urls']['core'] = $GLOBALS['fpcm']['urls']['base'].'core/';
            $GLOBALS['fpcm']['urls']['lib']  = $GLOBALS['fpcm']['urls']['base'].'lib/';
            
            return true;
        }

        /**
         * Kompletten Ordner-Pfad ausgehend von Basis-Ordner ermitteln
         * @param string $type
         * @param string $path
         * @param boolean $base
         * @return string
         */
        public static function getFullDirPath($type, $path = '', $base = false)
        {
            $path = $GLOBALS['fpcm']['dir']['base'].$type.(trim($path ? DIRECTORY_SEPARATOR.$path : ''));
            return ($base ? basename($path) : $path);
        }

        /**
         * Kompletten Ordner-Pfad ausgehend von data-Ordner ermitteln
         * @param string $type
         * @param string $path
         * @param boolean $base
         * @return string
         */
        public static function getDataDirPath($type, $path = '', $base = false)
        {
            $path = $GLOBALS['fpcm']['dir']['data'].$type.($path ? DIRECTORY_SEPARATOR.$path : '');
            return ($base ? basename($path) : $path);
        }

        /**
         * Kompletten Ordner-Pfad ausgehend von inc-Ordner ermitteln
         * @param string $path
         * @return string
         */
        public static function getIncDirPath($path = '')
        {
            return $GLOBALS['fpcm']['dir']['inc'].$path;
        }

        /**
         * Komplette URL ausgehend von core-Ordner ermitteln
         * @param string $type
         * @param string $path
         * @return string
         */
        public static function getCoreDirPath($type, $path = '')
        {
            return $GLOBALS['fpcm']['dir']['core'].$type.DIRECTORY_SEPARATOR.$path;
        }

        /**
         * Komplette URL ausgehend vom root-Ebene ermitteln
         * @param string $type
         * @param string $path
         * @return string
         */
        public static function getRootUrl($path = '')
        {
            return $GLOBALS['fpcm']['urls']['base'].$path;
        }

        /**
         * Komplette URL ausgehend von data-Ordner ermitteln
         * @param string $type
         * @param string $path
         * @return string
         */
        public static function getDataUrl($type, $path)
        {
            return $GLOBALS['fpcm']['urls']['data'].$type.'/'.$path;
        }

        /**
         * Komplette URL ausgehend von core-Ordner ermitteln
         * @param string $type
         * @param string $path
         * @return string
         */
        public static function getCoreUrl($type, $path = '')
        {
            return $GLOBALS['fpcm']['urls']['core'].$type.'/'.$path;
        }

        /**
         * Komplette URL ausgehend von lib-Ordner ermitteln
         * @param string $path
         * @return string
         */
        public static function getLibUrl($path)
        {
            return $GLOBALS['fpcm']['urls']['lib'].$path;
        }

    }
?>