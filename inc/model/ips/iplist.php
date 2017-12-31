<?php
    /**
     * IP address list object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
         * Konstruktor
         */
        public function __construct() {
            $this->table = \fpcm\classes\database::tableIpAdresses;
            
            parent::__construct();
        }
        
        /**
         * Liefert IP-Adressen aus Datenbank zurück
         * @return array
         */
        public function getIpAll() {
            $items = $this->dbcon->fetch($this->dbcon->select($this->table), true);
            
            $res = [];

            foreach ($items as $item) {
                $ipaddress = new ipaddress();
                if ($ipaddress->createFromDbObject($item)) {
                    $res[$ipaddress->getId()] = $ipaddress;
                }
            }
            
            return $res;            
        }

        /**
         * Prüft ob IP-Adresse gesperrt ist
         * @param string $lockType
         * @return bool
         */
        public function ipIsLocked($lockType = 'noaccess') {
            
            $delim = strpos(\fpcm\classes\http::getIp(), ':') !== false ? ':' : '.';
            
            $ipAddress      = explode($delim, \fpcm\classes\http::getIp());
            
            $adresses       = [];
            $adresses[]     = implode($delim, $ipAddress);
            
            $where = array('ipaddress '.$this->dbcon->dbLike().' ?');
            $counts = count($ipAddress) - 1;
            for ($i = $counts; $i > 0; $i--) {
                $ipAddress[$i]   = '*';
                $adresses[]     = implode($delim, $ipAddress);
                $where[] = 'ipaddress '.$this->dbcon->dbLike().' ?';
            }
            
            $where = "(".implode(' OR ', $where).") AND $lockType = 1";

            $result = $this->dbcon->fetch($this->dbcon->select($this->table, 'count(id) AS counted', $where, $adresses));
            
            return $result->counted ? true : false;
        }
        
        /**
         * Löscht IP-Adressen aus Datenbank
         * @param array $ids
         * @return bool
         */
        public function deleteIpAdresses(array $ids) {
            return $this->dbcon->delete($this->table, 'id IN ('.implode(',', $ids).')');
        }
        
    }
