<?php

/**
 * IP address list object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\ips;

/**
 * IP-Listen Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class iplist extends \fpcm\model\abstracts\tablelist {

    protected $lockCache = [];

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableIpAdresses;

        parent::__construct();
    }

    /**
     * Liefert IP-Adressen aus Datenbank zurück
     * @return array
     */
    public function getIpAll()
    {
        $items = $this->dbcon->fetch($this->dbcon->select($this->table), true);

        $res = [];

        foreach ($items as $item) {
            $ipaddress = new ipaddress();
            if (!$ipaddress->createFromDbObject($item)) {
                continue;
            }

            $res[$ipaddress->getId()] = $ipaddress;
        }

        return $res;
    }

    /**
     * Prüft ob IP-Adresse gesperrt ist
     * @param string $lockType
     * @return bool
     */
    public function ipIsLocked($lockType = 'noaccess')
    {
        $types = ['nocomments', 'nologin', 'noaccess'];
        if (!in_array($lockType, $types)) {
            return true;
        }

        $ip = \fpcm\classes\http::getIp();
        if (isset($this->lockCache[$ip.'-'.$lockType])) {
            return $this->lockCache[$ip.'-'.$lockType];
        }
        
        $delim = strpos($ip, ':') !== false ? ':' : '.';

        $ipAddress = explode($delim, $ip);

        $adresses = [implode($delim, $ipAddress)];
        $where = ['?'];

        $counts = count($ipAddress) - 1;
        for ($i = $counts; $i > 0; $i--) {
            $ipAddress[$i] = '*';
            $adresses[] = implode($delim, $ipAddress);
            $where[] = '?';
        }

        $where = "ipaddress IN (" . implode(', ', $where) . ") AND $lockType = 1";

        $result = $this->dbcon->fetch($this->dbcon->select($this->table, 'count(id) AS counted', $where, $adresses));
        $this->lockCache[$ip.'-'.$lockType] = $result->counted ? true : false;

        return $this->lockCache[$ip.'-'.$lockType];
    }

    /**
     * Löscht IP-Adressen aus Datenbank
     * @param array $ids
     * @return bool
     */
    public function deleteIpAdresses(array $ids)
    {
        return $this->dbcon->delete($this->table, 'id IN (' . implode(',', $ids) . ')');
    }

}
