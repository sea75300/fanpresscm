<?php
    /**
     * FanPress CM constants
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    if (file_exists(dirname(__DIR__).'/data/config/constants.custom.php')) {
        include_once dirname(__DIR__).'/data/config/constants.custom.php';
    }

    /**
     * Mindest-PHP-Version
     * @since FPCM 3.5
     * @ignore
     */
    define ('FPCM_PHP_REQUIRED', '5.5.3');

    if (!defined('FPCM_DEBUG')) {
        /**
         * Debug-Modus aktivieren
         */
        define ('FPCM_DEBUG', false);
    }
    
    if (!defined('FPCM_DEBUG_SQL')) {
        /**
         * SQL-Debug-Modus aktivieren
         */
        define ('FPCM_DEBUG_SQL', false);
    }
    
    if (!defined('FPCM_MODULE_IGNORE_DEPENDENCIES')) {
        /**
         * ModulAbhängigkeiten ignorieren
         */
        define ('FPCM_MODULE_IGNORE_DEPENDENCIES', false);
    }
    
    if (!defined('FPCM_IGNORE_INSTALLER_DISABLED')) {
        /**
         * aktiven Installer ignorieren
         */
        define ('FPCM_IGNORE_INSTALLER_DISABLED', false);
    }
    
    if (!defined('FPCM_LANGCACHE_TIMEOUT')) {
        /**
         * Timeout für Sprach-Cache
         */
        define ('FPCM_LANGCACHE_TIMEOUT', 3600 * 24 * 31);
    }
    
    if (!defined('FPCM_PAGETOKENCACHE_TIMEOUT')) {
        /**
         * Timeout für Pagetoken-Cache
         */
        define ('FPCM_PAGETOKENCACHE_TIMEOUT', 3600 * 5);
    }
    
    if (!defined('FPCM_DEFAULT_LANGUAGE_CODE')) {
        /**
         * Standard-Sprachcode
         */
        define ('FPCM_DEFAULT_LANGUAGE_CODE', 'de');
    }
    
    if (!defined('FPCM_CACHE_DEBUG')) {
        /**
         * Cache-Datei-Namen nicht hashen
         * @since FPCM 3.2
         */
        define ('FPCM_CACHE_DEBUG', false);
    }
    
    if (!defined('FPCM_CACHEMODULE_DEBUG')) {
        /**
         * Cache-Moduleordner-Namen nicht hashen
         * @since FPCM 3.4
         */
        define ('FPCM_CACHEMODULE_DEBUG', false);
    }
    
    if (!defined('FPCM_NOJSCSSPHP_FILESIZE_HEADER')) {
        /**
         * Dateigröße in style.php und script.php nicht als Header mitschicken
         * @since FPCM 3.4
         */
        define ('FPCM_NOJSCSSPHP_FILESIZE_HEADER', false);
    }
    
    if (!defined('FPCM_ARTICLE_LOCKED_INTERVAL')) {
        /**
         * Interval um Artikel auf "In Bearbeitung" zu setzten bzw. zu prüfen
         * @since FPCM 3.5
         */
        define ('FPCM_ARTICLE_LOCKED_INTERVAL', 60);
    }
    
    if (!defined('FPCM_ARTICLE_DISABLE_SHORTLINKS')) {
        /**
         * Erstellung von Artikel-Shortlink über is.gd deaktivieren
         * @since FPCM 3.5
         */
        define ('FPCM_ARTICLE_DISABLE_SHORTLINKS', 60);
    }
    
    if (!defined('FPCM_AUTHOR_IMAGE_MAX_SIZE')) {
        /**
         * Maximale Größe von Author-Bildern
         * @since FPCM 3.6
         */
        define ('FPCM_AUTHOR_IMAGE_MAX_SIZE', 32768);
    }