<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * Old Permission handler Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class permissions extends \fpcm\model\permissions\permissions {

    /**
     * Berechtigungen initialisieren
     * @return void
     */
    final public function init()
    {
        if (!$this->cache->isExpired($this->cacheName)) {
            $this->permissiondata = $this->cache->read($this->cacheName);
            return true;
        }

        $data = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
                ->setWhere('rollid = ?')
                ->setParams([$this->rollid])
        );
        
        if (!is_object($data)) {
            return false;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->permissiondata = json_decode($this->permissiondata, true);
        $this->cache->write($this->cacheName, $this->permissiondata, $this->config->system_cache_timeout);
    }

    /**
     * Prüft ob Benutzer Berechtigung hat
     * @param array $permissionArray
     * @return bool
     */
    final public function check(array $permissionArray) : bool
    {
        if (!count($this->permissiondata)) {
            return false;
        }

        $res = true;

        $permissionArrayHash = \fpcm\classes\tools::getHash(json_encode($permissionArray));
        if (isset($this->checkedData[$permissionArrayHash])) {
            return $this->checkedData[$permissionArrayHash];
        }

        $permissionArray = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('permission\check', $permissionArray);
        foreach ($permissionArray as $module => $permission) {

            if (!isset($this->permissiondata[$module])) {
                trigger_error("No permissions available for module \"{$module}\" and roll \"{$this->rollid}\"!". PHP_EOL.
                              "   > Permission-Debug: ".PHP_EOL.(is_array($permission) ? implode(PHP_EOL, $permission) : $permission) );
                return false;
            }

            $check = false;
            if (is_array($permission)) {

                foreach ($permission as $permissionItem) {
                    $check = isset($this->permissiondata[$module][$permissionItem]) ? $this->permissiondata[$module][$permissionItem] : false;
                    if ($check) {
                        break;
                    }
                }
            } else {
                $check = isset($this->permissiondata[$module][$permission]) ? (bool) $this->permissiondata[$module][$permission] : false;
            }

            $res = $res && $check;
        }

        $this->checkedData[$permissionArrayHash] = $res;
        return $res;
    }

}
