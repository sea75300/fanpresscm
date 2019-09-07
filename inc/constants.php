<?php

/**
 * FanPress CM constants
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (file_exists(dirname(__DIR__) . '/data/config/constants.custom.php')) {
    include_once dirname(__DIR__) . '/data/config/constants.custom.php';
}

/**
 * Minimum required PHP version
 * @since FPCM 3.5
 * @ignore
 */
define('FPCM_PHP_REQUIRED', '7.0.0');


/**
 * Constant of seconds per day
 * @since FPCM 3.5
 * @ignore
 */
define('FPCM_DATE_SECONDS', 86400);

if (!defined('FPCM_DEBUG')) {
    /**
     * Debug-Modus aktivieren
     */
    define('FPCM_DEBUG', false);
}

if (!defined('FPCM_DEBUG_SQL')) {
    /**
     * SQL-Debug-Modus aktivieren
     */
    define('FPCM_DEBUG_SQL', false);
}

if (!defined('FPCM_DEBUG_ROUTES')) {
    /**
     * Controller-Routing-Debug-Modus aktivieren
     */
    define('FPCM_DEBUG_ROUTES', false);
}

if (!defined('FPCM_DEBUG_EVENTS')) {
    /**
     * Event-Debug-Modus aktivieren
     */
    define('FPCM_DEBUG_EVENTS', false);
}

if (!defined('FPCM_MODULE_IGNORE_DEPENDENCIES')) {
    /**
     * ModulAbhängigkeiten ignorieren
     */
    define('FPCM_MODULE_IGNORE_DEPENDENCIES', false);
}

if (!defined('FPCM_CACHE_DEFAULT_TIMEOUT')) {
    /**
     * Timeout für Sprach-Cache
     */
    define('FPCM_CACHE_DEFAULT_TIMEOUT', 3600);
}

if (!defined('FPCM_LANGCACHE_TIMEOUT')) {
    /**
     * Timeout für Sprach-Cache
     */
    define('FPCM_LANGCACHE_TIMEOUT', FPCM_DATE_SECONDS * 31);
}

if (!defined('FPCM_PAGETOKENCACHE_TIMEOUT')) {
    /**
     * Timeout für Pagetoken-Cache
     */
    define('FPCM_PAGETOKENCACHE_TIMEOUT', 3600 * 5);
}

if (!defined('FPCM_DEFAULT_LANGUAGE_CODE')) {
    /**
     * Standard-Sprachcode
     */
    define('FPCM_DEFAULT_LANGUAGE_CODE', 'de');
}

if (!defined('FPCM_CACHE_DEBUG')) {
    /**
     * Cache-Datei-Namen nicht hashen
     * @since FPCM 3.2
     */
    define('FPCM_CACHE_DEBUG', false);
}

if (!defined('FPCM_CACHEMODULE_DEBUG')) {
    /**
     * Cache-Moduleordner-Namen nicht hashen
     * @since FPCM 3.4
     */
    define('FPCM_CACHEMODULE_DEBUG', false);
}

if (!defined('FPCM_NOJSCSSPHP_FILESIZE_HEADER')) {
    /**
     * Dateigröße in style.php und script.php nicht als Header mitschicken
     * @since FPCM 3.4
     */
    define('FPCM_NOJSCSSPHP_FILESIZE_HEADER', false);
}

if (!defined('FPCM_ARTICLE_LOCKED_INTERVAL')) {
    /**
     * Interval um Artikel auf "In Bearbeitung" zu setzten bzw. zu prüfen
     * @since FPCM 3.5
     */
    define('FPCM_ARTICLE_LOCKED_INTERVAL', 60);
}

if (!defined('FPCM_ARTICLE_DISABLE_SHORTLINKS')) {
    /**
     * Erstellung von Artikel-Shortlink über is.gd deaktivieren
     * @since FPCM 3.5
     */
    define('FPCM_ARTICLE_DISABLE_SHORTLINKS', false);
}

if (!defined('FPCM_AUTHOR_IMAGE_MAX_SIZE')) {
    /**
     * Maximale Größe von Author-Bildern
     * @since FPCM 3.6
     */
    define('FPCM_AUTHOR_IMAGE_MAX_SIZE', 32768);
}

if (!defined('FPCM_INSECURE_USERNAMES')) {
    /**
     * List of insecure usernams
     * @since FPCM 3.6
     */
    define('FPCM_INSECURE_USERNAMES', ['admin', 'root', 'test', 'support', 'administrator', 'adm']);
}

if (!defined('FPCM_ARTICLES_SOURCES_AUTOCOMPLETE')) {
    /**
     * Number of sources entries saved in file options
     * @since FPCM 4.1
     */
    define('FPCM_ARTICLES_SOURCES_AUTOCOMPLETE', 25);
}

if (!defined('FPCM_REQUEST_EXIT')) {
    /**
     * Enable extended request check
     * @since FPCM 4.2.1
     */
    define('FPCM_REQUEST_EXIT', true);
}