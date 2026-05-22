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
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class iplist
extends \fpcm\model\abstracts\tablelist
implements \fpcm\model\interfaces\gsearchIndex {

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
     * Returns list of ip addresses
     * @param string $sorting
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function getIpAll(string $sorting = '', ?int $offset = null, ?int $limit = null)
    {
        $sObj = new \fpcm\model\dbal\selectParams($this->table);

        $where = [];

        if (trim($sorting)) {
            $where[] = $this->dbcon->orderBy(['iptime '.$sorting]);
        }

        if ($offset !== null && $limit !== null) {
            $where[] = $this->dbcon->limitQuery($limit, $offset);
        }

        if (count($where)) {
            $sObj->setWhere( sprintf( 'id > 0 %s', implode(' ', $where) ) );
        }

        $items = $this->dbcon->selectFetch($sObj->setFetchAll(true));

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
     * Delete ip address set
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

    /**
     * Count locked ip address sets
     * @return int
     */
    public function getCount() : int
    {
        return $this->dbcon->count($this->table);
    }

    /**
     * Returns count
     * @return \fpcm\model\dbal\selectParams
     */
    public function getCountQuery(): \fpcm\model\dbal\selectParams
    {
        return $this->getSearchQueryObj()->setItem('\'ipaddress\' as model, count(id) as count');
    }

    /**
     * Returns icon
     * @return \fpcm\view\helper\icon
     */
    public function getElementIcon(): \fpcm\view\helper\icon
    {
        return new \fpcm\view\helper\icon('globe');
    }

    /**
     * Returns element link
     * @param mixed $id
     * @return string
     */
    public function getElementLink(mixed $id): string
    {
        $tmp = \fpcm\classes\loader::getObject('\fpcm\model\ips\ipaddress', null);
        $tmp->setId($id);

        return $tmp->getEditLink();
    }

    /**
     * Return search data
     * @return \fpcm\model\dbal\selectParams
     */
    public function getSearchQuery(): \fpcm\model\dbal\selectParams
    {
        return $this->getSearchQueryObj()->setItem('\'ipaddress\' as model, id as oid, '.$this->dbcon->concatString(['ipaddress', '";"', 'iptime']).' as text, \'\' as meta')->setFetchAll(true);
    }

    /**
     * Return element text
     * @param string $text
     * @return string
     */
    public function prepareText(string $text): string
    {
        list($ip, $date) = explode(';', $text);
        return sprintf('%s<br><span class="fpcm ui-font-small text-secondary">%s</span>', new \fpcm\view\helper\escape($ip), new \fpcm\view\helper\dateText($date));
    }

    /**
     * Returns selectParams object instance
     * @return \fpcm\model\dbal\selectParams
     * @since 5.1-dev
     */
    private function getSearchQueryObj(): \fpcm\model\dbal\selectParams
    {
        return (new \fpcm\model\dbal\selectParams($this->table))->setWhere('ipaddress LIKE :term');
    }

}
