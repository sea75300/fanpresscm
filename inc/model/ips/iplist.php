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

    /**
     * IP-locks check cache
     * @var array
     */
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
        $items = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true));
        if (!$items) {
            return [];
        }

        $this->data = [];
        foreach ($items as $item) {
            $ipaddress = new ipaddress();
            if (!$ipaddress->createFromDbObject($item)) {
                continue;
            }

            $this->data[$ipaddress->getId()] = $ipaddress;
        }

        return $this->data;
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

        $result = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere("ipaddress IN (" . implode(', ', $where) . ") AND {$lockType} = 1")
            ->setItem('count(id) AS counted')
            ->setParams($adresses));
            
        $this->lockCache[$ip.'-'.$lockType] = is_object($result) && isset($result->counted) && $result->counted ? true : false;

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
