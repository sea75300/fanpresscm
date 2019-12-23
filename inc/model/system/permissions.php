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
 * @deprecated since version FPCm 4.4
 */
class permissions extends \fpcm\model\permissions\permissions {

//    /**
//     * Constructor
//     * @param int $rollid
//     * @return void
//     */
//    final public function __construct($rollid = 0)
//    {
//        trigger_error('Permissions objects of instance \\fpcm\\model\\system\\permissions are deprecated. Use \\fpcm\\model\\permissions\\permissions instead', E_USER_DEPRECATED);
//        return parent::__construct($rollid);
//    }

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

}
