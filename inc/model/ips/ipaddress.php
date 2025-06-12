<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\ips;

/**
 * IP adress object
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class ipaddress extends \fpcm\model\abstracts\dataset {

    use \fpcm\model\traits\eventModuleEmpty;

    /**
     * IP-Adresse
     * @var string
     */
    protected $ipaddress;

    /**
     * Zeit der Sperrung der IP-Adresse
     * @var int
     */
    protected $iptime;

    /**
     * Benutzer, der die IP-Adresse gesperrt hat
     * @var int
     */
    protected $userid;

    /**
     * IP-Adresse für Kommentare gesperrt
     * @var bool
     */
    protected $nocomments = 0;

    /**
     * IP-Adresse für ACP-Login gesperrt
     * @var bool
     */
    protected $nologin = 0;

    /**
     * IP-Adresse für allgemeinen Zugriff gesperrt
     * @var bool
     */
    protected $noaccess = 0;

    /**
     * Action-String für edit-Action
     * @var string
     */
    protected $editAction = 'ips/edit&id=';

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->table = \fpcm\classes\database::tableIpAdresses;
        parent::__construct($id);
    }

    /**
     * Gibt gesperrte IP-Adresse zurück
     * @return string
     */
    public function getIpaddress()
    {
        return $this->ipaddress;
    }

    /**
     * Gibt Zeitpunkt der Sperrung zurück
     * @return int
     */
    public function getIptime()
    {
        return $this->iptime;
    }

    /**
     * Benutzer, der die Sperrung ausgeführt hat
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Sperrung für Kommentare
     * @return bool
     */
    public function getNocomments()
    {
        return $this->nocomments;
    }

    /**
     * Sperrung für ACP-Login
     * @return bool
     */
    public function getNologin()
    {
        return $this->nologin;
    }

    /**
     * Sperrung für allgemeinen Zugriff auf ACP & Frontend
     * @return bool
     */
    public function getNoaccess()
    {
        return $this->noaccess;
    }

    /**
     * Setter für zu sperrende IP
     * @param string $ipaddress
     */
    public function setIpaddress($ipaddress)
    {
        $this->ipaddress = $ipaddress;
    }

    /**
     * Setter für Sperrzeitpunkt
     * @param int $iptime
     */
    public function setIptime($iptime)
    {
        $this->iptime = (int) $iptime;
    }

    /**
     * Setter für sperrenden Benutzer
     * @param int $userid
     */
    public function setUserid($userid)
    {
        $this->userid = (int) $userid;
    }

    /**
     * Setter für Kommentar-Sperre
     * @param bool $nocomments
     */
    public function setNocomments($nocomments)
    {
        $this->nocomments = (int) $nocomments;
    }

    /**
     * Setter für ACP-Login-Sperre
     * @param bool $nologin
     */
    public function setNologin($nologin)
    {
        $this->nologin = (int) $nologin;
    }

    /**
     * Setter für allgemeine Zugriffsperre
     * @param bool $noaccess
     */
    public function setNoaccess($noaccess)
    {
        $this->noaccess = (int) $noaccess;
    }

    /**
     * Speichert einen neuen IP-Datensatz in der Datenbank
     * @return bool
     */
    public function save()
    {
        if ($this->check()) {
            return false;
        }

        $params = $this->getPreparedSaveParams();
        $params = $this->events->trigger('ipaddressSave', $params)->getData();

        $return = false;
        if ($this->dbcon->insert($this->table, $params)) {
            $return = true;
        }

        $this->cache->cleanup();
        $this->id = $this->dbcon->getLastInsertId();

        return $return;
    }

    /**
     * nicht verfügbar
     * @return bool
     */
    public function update()
    {
        $ap = sprintf("noaccess = %d AND nocomments = %d AND nologin = %d", $this->noaccess, $this->nocomments, $this->nologin);
        if ($this->check($ap)) {
            return true;
        }

        $params = $this->getPreparedSaveParams();
        $params = $this->events->trigger('ipaddressUpdate', $params)->getData();        
        $fields = array_keys($params);
        $params[] = $this->getId();

        $return = false;
        if ( $this->dbcon->update($this->table, $fields, array_values($params), 'id = ?') ) {
            $return = true;
        }

        $this->cache->cleanup();
        return $return;
    }

    /**
     * Check if ip address is locked
     * @param string $accessParams
     * @return bool
     */
    public function check(string $accessParams = 'noaccess = 1 OR nocomments = 1 OR nologin = 1') : bool
    {
        $delim = str_contains($this->ipaddress, ':') ? ':' : '.';

        $adresses = array($this->ipaddress);
        $ipAddress = explode($delim, $this->ipaddress);

        $where = array('ipaddress ' . $this->dbcon->dbLike() . ' ?');
        $counts = count($ipAddress) - 1;
        for ($i = $counts; $i > 0; $i--) {
            $ipAddress[$i] = '*';
            $adresses[] = implode($delim, $ipAddress);
            $where[] = 'ipaddress ' . $this->dbcon->dbLike() . ' ?';
        }

        $where = sprintf("(%s) AND (%s)", implode(' OR ', $where), $accessParams);
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, 'count(id) AS counted', $where, $adresses));

        return $result->counted ? true : false;
    }

}
