<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Directory base config
 *
 * @package fpcm\classes\baseconfig
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 */
final class dirs {

    const DATA_CONFIG = 'config';
    const DATA_CACHE = 'cache';
    const DATA_FMTMP = 'filemanager';
    const DATA_LOGS = 'logs';
    const DATA_STYLES = 'styles';
    const DATA_SHARE = 'share';
    const DATA_SMILEYS = 'smileys';
    const DATA_TEMP = 'temp';
    const DATA_UPLOADS = 'uploads';
    const DATA_DBDUMP = 'dbdump';
    const DATA_DBSTRUCT = 'dbstruct';
    const DATA_DRAFTS = 'drafts';
    const DATA_PROFILES = 'profiles';
    const DATA_MODULES = 'modules';
    const DATA_OPTIONS = 'options';
    const DATA_BACKUP = 'backup';
    const CORE_JS = 'js';
    const CORE_THEME = 'theme';
    const CORE_VIEWS = 'views';

    /**
     * @ignore
     */
    public static function init()
    {
        self::initDirs();
        self::initUrls();
    }

    /**
     * Initialisiert Basis-Ordner
     * @return bool
     */
    private static function initDirs()
    {
        $GLOBALS['fpcm']['dir']['base'] = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
        $GLOBALS['fpcm']['dir']['core'] = $GLOBALS['fpcm']['dir']['base'] . 'core' . DIRECTORY_SEPARATOR;
        $GLOBALS['fpcm']['dir']['data'] = $GLOBALS['fpcm']['dir']['base'] . 'data' . DIRECTORY_SEPARATOR;
        $GLOBALS['fpcm']['dir']['inc'] = $GLOBALS['fpcm']['dir']['base'] . 'inc' . DIRECTORY_SEPARATOR;
        $GLOBALS['fpcm']['dir']['lib'] = $GLOBALS['fpcm']['dir']['base'] . 'lib' . DIRECTORY_SEPARATOR;

        return true;
    }

    /**
     * Initialisiert Basis-URLs
     * @return bool
     */
    private static function initUrls()
    {
        $isCli = baseconfig::isCli();
        $hasConst = defined('FPCM_URLS_BASE') && trim(FPCM_URLS_BASE);

        if ($isCli) {
            $GLOBALS['fpcm']['urls']['base'] = 'localhost';
        }
        elseif ($hasConst) {
            $GLOBALS['fpcm']['urls']['base'] = FPCM_URLS_BASE;
        }
        else {
            $GLOBALS['fpcm']['urls']['base'] = sprintf(
                "%s://%s/%s/",
                baseconfig::canHttps() ? 'https' : 'http',
                $_SERVER['HTTP_HOST'],
                trim(dirname($_SERVER['PHP_SELF']), '/')
            );
        }
        
        $fpcmBase = basename($GLOBALS['fpcm']['dir']['base']);

        if ($isCli) {
            $GLOBALS['fpcm']['urls']['base'] .= '/' . $fpcmBase . '/';
        }
        elseif (!$hasConst) {
            $parsed = parse_url($GLOBALS['fpcm']['urls']['base']);

            if (!str_ends_with($GLOBALS['fpcm']['dir']['base'], $parsed['path']) && 
                $parsed['path'] !== $fpcmBase) {
                $GLOBALS['fpcm']['urls']['base'] = sprintf("%s://%s/%s/", $parsed['scheme'], $parsed['host'], $fpcmBase);
            }
        }

        $GLOBALS['fpcm']['urls']['data'] = $GLOBALS['fpcm']['urls']['base'] . 'data/';
        $GLOBALS['fpcm']['urls']['core'] = $GLOBALS['fpcm']['urls']['base'] . 'core/';
        $GLOBALS['fpcm']['urls']['lib'] = $GLOBALS['fpcm']['urls']['base'] . 'lib/';

        return true;
    }

    /**
     * Kompletten Ordner-Pfad ausgehend von Basis-Ordner ermitteln
     * @param string $type
     * @param string $path
     * @param boolean $base
     * @return string
     */
    public static function getFullDirPath($type, $path = '', $base = false) : string
    {
        $path = $GLOBALS['fpcm']['dir']['base'] . $type . (trim($path ? DIRECTORY_SEPARATOR . $path : ''));
        return str_replace('//', DIRECTORY_SEPARATOR, ($base ? basename($path) : $path));
    }

    /**
     * Kompletten Ordner-Pfad ausgehend von data-Ordner ermitteln
     * @param string $type
     * @param string $path
     * @param boolean $base
     * @return string
     */
    public static function getDataDirPath($type, $path = '', $base = false) : string
    {
        $path = $GLOBALS['fpcm']['dir']['data'] . $type . ($path ? DIRECTORY_SEPARATOR . $path : '');
        return str_replace('//', DIRECTORY_SEPARATOR, ($base ? basename($path) : $path));
    }

    /**
     * Kompletten Ordner-Pfad ausgehend von inc-Ordner ermitteln
     * @param string $path
     * @return string
     */
    public static function getIncDirPath($path = '') : string
    {
        return str_replace('//', DIRECTORY_SEPARATOR, $GLOBALS['fpcm']['dir']['inc'] . $path);
    }

    /**
     * Komplette URL ausgehend von core-Ordner ermitteln
     * @param string $type
     * @param string $path
     * @return string
     */
    public static function getCoreDirPath($type, $path = '') : string
    {
        return str_replace('//', DIRECTORY_SEPARATOR, $GLOBALS['fpcm']['dir']['core'] . $type . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Komplette URL ausgehend vom root-Ebene ermitteln
     * @param string $path
     * @return string
     */
    public static function getRootUrl($path = '') : string
    {
        return $GLOBALS['fpcm']['urls']['base'] . $path;
    }

    /**
     * Komplette URL ausgehend von data-Ordner ermitteln
     * @param string $type
     * @param string $path
     * @return string
     */
    public static function getDataUrl($type, $path) : string
    {
        return $GLOBALS['fpcm']['urls']['data'] . $type . '/' . $path;
    }

    /**
     * Komplette URL ausgehend von core-Ordner ermitteln
     * @param string $type
     * @param string $path
     * @return string
     */
    public static function getCoreUrl($type, $path = '') : string
    {
        return $GLOBALS['fpcm']['urls']['core'] . $type . '/' . $path;
    }

    /**
     * Komplette URL ausgehend von lib-Ordner ermitteln
     * @param string $path
     * @return string
     */
    public static function getLibUrl($path) : string
    {
        return $GLOBALS['fpcm']['urls']['lib'] . $path;
    }

    /**
     * Get full url for fpcm-installation/modules
     * @return string
     * @since 5.2
     */
    public static function getECMAurl() : string
    {
        if (!isset($GLOBALS['fpcm']['url']['ecma'])) {
            $GLOBALS['fpcm']['url']['ecma'] = dirs::getRootUrl('modules');
        }

        return $GLOBALS['fpcm']['url']['ecma'];
    }

}
