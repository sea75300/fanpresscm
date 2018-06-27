<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Zeitzonen trait
 * 
 * @package fpcm\controller\traits\common\timezone
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait timezone {

    /**
     * Gibt übersetzte Zeitzonen zurück
     * @return array
     */
    public function getTimeZones() : array
    {
        $timezones = [];

        foreach ($this->language->translate('SYSTEM_OPTIONS_TZ') as $timeZoneArea => $timeZoneAreaName) {
            $timezones[$timeZoneAreaName] = \DateTimeZone::listIdentifiers($timeZoneArea);
        }
        return $timezones;
    }

    /**
     * Returns timezones by global area
     * @return array
     */
    public function getTimeZonesAreas(): array
    {
        $timezones = [];

        foreach ($this->language->translate('SYSTEM_OPTIONS_TZ') as $timeZoneArea => $timeZoneAreaName) {
            foreach (\DateTimeZone::listIdentifiers($timeZoneArea) as $value) {
                $timezones[$timeZoneAreaName][$value] = $value;
            }
        }

        return $timezones;
    }

    /**
     * DateTime-Maske mit Beispielen
     * @return array
     * @since FPCM 3.6
     */
    public function getDateTimeMasks()
    {
        $data = [];
        foreach (\fpcm\classes\baseconfig::$dateTimeMasks as $value) {
            $data[] = [
                'value' => $value,
                'label' => $value . ' (' . date($value) . ')',
            ];
        }

        return $data;
    }

}

?>