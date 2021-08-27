<?php

/**
 * FanPress CM Word Ban Item List
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 */

namespace fpcm\model\wordban;

/**
 * Word Ban Item Object List
 * 
 * @package fpcm\model\wordban
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 3.2.0
 */
class items extends \fpcm\model\abstracts\tablelist {

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableTexts;
        parent::__construct();
    }

    /**
     * Ruft Liste von Text-Sperren ab
     * @return array
     */
    public function getItems()
    {
        $obj = new \fpcm\model\dbal\selectParams($this->table);
        $obj->setFetchAll(true);
        
        $list = $this->dbcon->selectFetch($obj);
        if (!$list) {
            return [];
        }

        $res = [];
        foreach ($list as $item) {
            $wbItem = new item();
            if ($wbItem->createFromDbObject($item)) {
                $res[$wbItem->getId()] = $wbItem;
            }
        }

        return $res;
    }

    /**
     * Löscht Wort-Sperren
     * @param array $ids
     * @return bool
     */
    public function deleteItems(array $ids)
    {
        if (!count($ids)) {
            return false;
        }

        $this->cache->cleanup();

        $ids = array_map('intval', $ids);
        return $this->dbcon->delete($this->table, 'id IN (' . implode(', ', $ids) . ')');
    }

    /**
     * Ersetzt gefundene Wörter/ Zeichenketten durch Ersetzungstext
     * @param string $text
     * @return string
     */
    public function replaceItems($text)
    {
        $cacheName = 'wordban/itemsReplace';

        $data = ['search' => [], 'replace' => []];

        if ($this->cache->isExpired($cacheName) || !is_array($this->cache->read($cacheName))) {
            $items = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'replacetxt = 1'), true);

            if (!is_array($items) || !count($items)) {
                return $text;
            }

            foreach ($items as $value) {
                $data['search'][] = $value->searchtext;
                $data['replace'][] = $value->replacementtext;
            }


            $this->cache->write($cacheName, $data);
        } else {
            $data = $this->cache->read($cacheName);
        }

        return str_replace($data['search'], $data['replace'], $text);
    }

    /**
     * Prüft, ob Suchtext in $text angegeben ist um Artikel auf zu Prüfung zu setzen
     * @param string $text
     * @return bool
     * @since 3.5
     */
    public function checkArticleApproval($text)
    {
        $cacheName = 'wordban/itemsArticleApproval';
        $data = [];

        if ($this->cache->isExpired($cacheName) || !is_array($this->cache->read($cacheName))) {
            $items = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'lockarticle = 1'), true);

            if (!is_array($items) || !count($items)) {
                return false;
            }

            foreach ($items as $value) {
                $data[] = $value->searchtext;
            }

            $data = implode('|', $data);
            $this->cache->write($cacheName, $data);
        } else {
            $data = $this->cache->read($cacheName);
        }

        if (!trim($data)) {
            return false;
        }

        return preg_match_all('/(' . $data . ')/is', $text);
    }

    /**
     * Prüft, ob Suchtext in $text angegeben ist um Kommentar auf zu Prüfung zu setzen
     * @param string $text
     * @return bool
     * @since 3.5
     */
    public function checkCommentApproval($text)
    {
        $cacheName = 'wordban/ItemsCommentApproval';
        $data = [];

        if ($this->cache->isExpired($cacheName) || !is_array($this->cache->read($cacheName))) {
            $items = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'commentapproval = 1'), true);

            if (!is_array($items) || !count($items)) {
                return false;
            }

            foreach ($items as $value) {
                $data[] = $value->searchtext;
            }

            $data = implode('|', $data);
            $this->cache->write($cacheName, $data);
        } else {
            $data = $this->cache->read($cacheName);
        }

        if (!trim($data)) {
            return false;
        }

        return preg_match_all('/(' . $data . ')/is', $text);
    }

}
