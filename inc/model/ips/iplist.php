<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\ips;

/**
 * IP-Listen Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
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

        $ip = \fpcm\classes\loader::getObject('\fpcm\model\http\request')->getIp();
        if (isset($this->lockCache[$ip])) {
            return (bool) ($this->lockCache[$ip][$lockType] ?? false);
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

        $this->lockCache[$ip] = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($this->dbcon->inQuery('ipaddress', $where))
            ->setItem('nocomments, nologin, noaccess')
            ->setParams($adresses)
            ->setFetchStyle(\PDO::FETCH_ASSOC));

        return (bool) ($this->lockCache[$ip][$lockType] ?? false);
    }

    /**
     * Löscht IP-Adressen aus Datenbank
     * @param array $ids
     * @return bool
     */
    public function deleteIpAdresses(array $ids)
    {
        if (!count($ids)) {
            return false;
        }
        
        return $this->dbcon->delete($this->table, 'id IN (' . implode(',', $ids) . ')');
    }

}
