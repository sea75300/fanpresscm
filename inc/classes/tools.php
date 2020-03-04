<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FanPress CM internal tools class
 * 
 * @package fpcm\classes\tools
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.1.2
 */
final class tools {

    /**
     * Erzeugt für Wert in $value einen String mit passender Einheit und Anzahl Dezimalstellen
     * von Byte (B) bis Terrabyte/Tebibyte (TB/TiB)
     * @param number $value ein numerischer Wert
     * @param int $decimals Anzahl Dezimalstellen, Default = 2
     * @param string $delimDec Dezimal-Trennzeichen
     * @param string $delimTousands Tausender-Trennzeichen
     * @return string
     * @since FPCM 3.1.2
     */
    public static function calcSize($value, $decimals = 2, $delimDec = ',', $delimTousands = '.')
    {
        if (!is_numeric($value)) {
            return $value;
        }

        $sizeUnits = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];

        $unitIdx = 0;
        while ($value > 1024) {
            $value = $value / 1024;
            $unitIdx++;
        }

        $suffix = isset($sizeUnits[$unitIdx]) ? $sizeUnits[$unitIdx] : ' ?';
        return number_format($value, $decimals, $delimDec, $delimTousands) . ' ' . $suffix;
    }

    /**
     * Create controller link
     * @param string $controller
     * @param array $params
     * @return string
     * @since FPCM 3.4
     */
    public static function getControllerLink($controller = '', array $params = [])
    {
        $redirectString = dirs::getRootUrl("index.php?module={$controller}");
        if (!count($params)) {
            return $redirectString;
        }

        return $redirectString . '&' . http_build_query($params);
    }

    /**
     * Create controller link
     * @param string $controller
     * @param array $params
     * @return string
     * @since FPCM 3.4
     */
    public static function getFullControllerLink($controller = '', array $params = [])
    {
        return self::getControllerLink($controller, $params);
    }

    /**
     * Berechnet Werte für Seitennavigation
     * @param int $pageLimit
     * @param int $currentPage
     * @param int $maxItemCount
     * @param int $currentItemCount
     * @return array
     * @since FPCM 3.4
     */
    public static function calcPagination($pageLimit, $currentPage, $maxItemCount, $currentItemCount)
    {
        $data = [
            'pageCurrent' => 1,
            'pageCount' => 1,
            'backBtn' => false,
            'nextBtn' => false,
            'pageSelectOptions' => [],
            'listActionLimit' => $currentPage,
        ];

        if ($pageLimit === false) {
            return $data;
        }

        $pageCurrent = 1;
        $data['pageCount'] = ceil($maxItemCount / $pageLimit);

        if ($currentPage) {
            $pageCurrent = (int) $currentPage;

            $backBtnValue = $pageCurrent - 1;
            $data['backBtn'] = $backBtnValue;

            $nextBtnValue = $pageCurrent + 1;
            $data['nextBtn'] = ($nextBtnValue <= $data['pageCount'] ? $nextBtnValue : false);

            $data['listActionLimit'] = '&page=' . $currentPage;
        } elseif (!$currentPage && $currentItemCount < $maxItemCount && !(2 * $pageLimit >= $maxItemCount + $pageLimit)) {
            $data['nextBtn'] = 2;
        }

        $data['pageCount'] = ($data['pageCount'] ? $data['pageCount'] : 1);
        $data['pageCurrent'] = $pageCurrent;

        /* @var $langObj language */
        $langObj = loader::getObject('\fpcm\classes\language', loader::getObject('\fpcm\model\system\config')->system_lang);

        for ($i = 1; $i <= $data['pageCount']; $i++) {
            $data['pageSelectOptions'][$langObj->translate('GLOBAL_PAGER', array('{{current}}' => $i, '{{total}}' => $data['pageCount']))] = $i;
        }

        return $data;
    }

    /**
     * Berechnet Limit anhand von Seite und Limit pro Seite
     * @param int $page
     * @param int $pageLimit
     * @return int
     * @since FPCM 3.4
     */
    public static function getPageOffset($page, $pageLimit)
    {
        return ($page ? ($page - 1) * $pageLimit : 0);
    }

    /**
     * String zum Setzen des aktuell aktiven Modules in Navigation
     * @return string
     * @since FPCM 3.5
     */
    public static function getNavigationActiveCheckStr()
    {
        $data = [];

        $request = new \fpcm\model\http\request();
        
        $mode = $request->fromGET('mode');
        $key = $request->fromGET('key');

        $data[] = $request->fromGET('module');
        $data[] = ($mode ? '&mode=' . $mode : '');
        $data[] = ($key ? '&key=' . $key : '');

        return implode('', $data);
    }

    /**
     * Escapes filename
     * @param string $filename
     * @return string
     */
    public static function escapeFileName($filename)
    {
        return preg_replace('/[^A-Za-z0-9_.\-]/', '', htmlentities($filename, ENT_COMPAT | ENT_HTML401));
    }

    /**
     * Create Hash of $data with @see security::defaultHashAlgo
     * @param string $data
     * @return string
     */
    public static function getHash($data)
    {
        return hash(security::defaultHashAlgo, $data);
    }

}

?>