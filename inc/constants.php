<?php

/**
 * FanPress CM constants
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (file_exists(dirname(__DIR__) . '/data/config/constants.custom.php')) {
    include_once dirname(__DIR__) . '/data/config/constants.custom.php';
}

/**
 * Minimum required PHP version
 * @since 3.5
 * @ignore
 */
define('FPCM_PHP_REQUIRED', '8.0.0');

/**
 * Constant of seconds per day
 * @since 3.5
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
     * @since 3.2
     */
    define('FPCM_CACHE_DEBUG', false);
}

if (!defined('FPCM_CACHEMODULE_DEBUG')) {
    /**
     * Cache-Moduleordner-Namen nicht hashen
     * @since 3.4
     */
    define('FPCM_CACHEMODULE_DEBUG', false);
}

if (!defined('FPCM_ARTICLE_LOCKED_INTERVAL')) {
    /**
     * Interval um Artikel auf "In Bearbeitung" zu setzten bzw. zu prüfen
     * @since 3.5
     */
    define('FPCM_ARTICLE_LOCKED_INTERVAL', 60);
}

if (!defined('FPCM_ARTICLE_DISABLE_SHORTLINKS')) {
    /**
     * Erstellung von Artikel-Shortlink über is.gd deaktivieren
     * @since 3.5
     */
    define('FPCM_ARTICLE_DISABLE_SHORTLINKS', false);
}

if (!defined('FPCM_AUTHOR_IMAGE_MAX_SIZE')) {
    /**
     * Maximale Größe von Author-Bildern
     * @since 3.6
     */
    define('FPCM_AUTHOR_IMAGE_MAX_SIZE', 32768);
}

if (!defined('FPCM_INSECURE_USERNAMES')) {
    /**
     * List of insecure usernams
     * @since 3.6
     */
    define('FPCM_INSECURE_USERNAMES', ['admin', 'root', 'test', 'support', 'administrator', 'adm']);
}

if (!defined('FPCM_ARTICLES_SOURCES_AUTOCOMPLETE')) {
    /**
     * Number of sources entries saved in file options
     * @since 4.1
     */
    define('FPCM_ARTICLES_SOURCES_AUTOCOMPLETE', 25);
}

if (!defined('FPCM_REQUEST_EXIT')) {
    /**
     * Enable extended request check
     * @since 4.2.1
     */
    define('FPCM_REQUEST_EXIT', true);
}

if (!defined('FPCM_MODULE_DEV')) {
    /**
     * Enable module developement
     * @since 4.2.1
     */
    define('FPCM_MODULE_DEV', false);
}

if (!defined('FPCM_PAGETOKEN_MAX')) {
    /**
     * Timeout für Pagetoken-Cache
     * @since 4.3
     */
    define('FPCM_PAGETOKEN_MAX', 10);
}

if (!defined('FPCM_DISABLE_MODULE_ZIPUPLOAD')) {
    /**
     * Disable upload für module package files
     * @since 4.4
     */
    define('FPCM_DISABLE_MODULE_ZIPUPLOAD', true);
}

if (!defined('FPCM_VIEW_JS_USE_MINIFIED')) {
    /**
     * Disable upload für module package files
     * @since 4.5
     */
    define('FPCM_VIEW_JS_USE_MINIFIED', false);
}

if (!defined('FPCM_PUB_SEARCH_MINLEN')) {
    /**
     * Minimum number of chars for public search
     * @since 4.5
     */
    define('FPCM_PUB_SEARCH_MINLEN', 4);
}

if (!defined('FPCM_DB_PERSISTENT')) {
    /**
     * Toggle persistent databse connection
     * @since 4.5
     */
    define('FPCM_DB_PERSISTENT', true);
}

if (!defined('FPCM_CSV_IMPORT')) {
    /**
     * Enable CSV import
     * @since 4.5
     */
    define('FPCM_CSV_IMPORT', false);
}

if (!defined('FPCM_CRON_DBDUMP_NOMAIL')) {
    /**
     * No attachments from sql dump cronjob
     * @since 4.5.3
     */
    define('FPCM_CRON_DBDUMP_NOMAIL', false);
}

if (!defined('FPCM_USER_SESSION')) {
    /**
     * Default session lenght, replaces ACP setting
     * @since 5.0.0-a3
     */
    define('FPCM_USER_SESSION', 3600);
}

if (!defined('FPCM_FILEMAGER_ITEMS_ROW')) {
    /**
     * Default session lenght, replaces ACP setting
     * @since 5.0.0-b5
     */
    define('FPCM_FILEMAGER_ITEMS_ROW', 5);
}

if (!defined('FPCM_CACHE_BACKEND')) {
    /**
     * Cache backend
     * @since 5.1-dev
     */
    define('FPCM_CACHE_BACKEND', '\\fpcm\\model\\cache\\fsBackend');
}   

if (!defined('FPCM_SMTP_TIMEOUT')) {
    /**
     * SMTP connection timeout
     * @since 5.1-dev
     */
    define('FPCM_SMTP_TIMEOUT', 5);
}

if (!defined('FPCM_DISABLE_AJAX_CRONJOBS_PUB')) {
    /**
     * Disable AJAX cronjobs on public controller
     * @since 5.1.0-b3
     */
    define('FPCM_DISABLE_AJAX_CRONJOBS_PUB', false);
}

if (!defined('FPCM_DISABLE_AJAX_CRONJOBS_REFRESH')) {

    /**
     * Disable AJAX cronjobs on refresh controller
     * @since 5.1.0-b3
     */
    define('FPCM_DISABLE_AJAX_CRONJOBS_REFRESH', false);
}

if (!defined('FPCM_TWITTER_DSIABLE_API')) {

    /**
     * Disable twitter api connector
     * @link https://www.heise.de/news/Twitter-macht-API-Zugang-kostenpflichtig-mit-einer-Woche-Vorlaufzeit-7480995.html
     * @since 5.1.0-b4
     */
    define('FPCM_TWITTER_DSIABLE_API', true);
}

