<?php

/**
 * FanPress CM User List Model
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Benutzerrollen-Liste Objekt
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class userRollList extends \fpcm\model\abstracts\tablelist {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableRoll;

        parent::__construct();
    }

    /**
     * Liefert ein array aller Benutzer-Rollen
     * @return array
     */
    public function getUserRolls()
    {
        $rolls = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true)->setWhere('id>0 '.$this->dbcon->orderBy(['id ASC, leveltitle ASC'])) );

        $res = [];
        foreach ($rolls as $roll) {
            $userRoll = new userRoll();
            if ($userRoll->createFromDbObject($roll)) {
                $res[$userRoll->getId()] = $userRoll;
            }
        }

        return $res;
    }

    /**
     * Liefert Array mit Benutzerrollen für gegebenes IDs
     * @param array $ids
     * @return array
     */
    public function getUserRollsByIds(array $ids)
    {

        $ids = array_map('intval', $ids);
        $rolls = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'id IN (' . implode(',', $ids) . ')'), true);

        $res = [];
        foreach ($rolls as $roll) {
            $userRoll = new userRoll();
            if ($userRoll->createFromDbObject($roll)) {
                $res[$userRoll->getId()] = $userRoll;
            }
        }

        return $res;
    }

    /**
     * Liefert ein array aller Benutzer-Rollen mit übersetzen Texten
     * @return array
     */
    public function getUserRollsTranslated()
    {
        if (isset($this->data['translatedRolls']) &&
            is_array($this->data['translatedRolls']) &&
            count($this->data['translatedRolls'])) {
            return $this->data['translatedRolls'];
        }
        
        $arr = $this->getUserRolls();
        array_walk($arr, function(userRoll $obj) {
            $this->data['translatedRolls'][$this->language->translate($obj->getRollName())] = $obj->getId();
        });

        return $this->data['translatedRolls'];
    }

    /**
     * Übersetzte Rollen zurückgeben
     * @param array $ids
     * @return array
     */
    public function getRollsbyIdsTranslated(array $ids)
    {
        
        if (isset($this->data['translatedRollsByID']) &&
            is_array($this->data['translatedRollsByID']) &&
            count($this->data['translatedRollsByID'])) {
            return $this->data['translatedRollsByID'];
        }
        
        $arr = $this->getUserRollsByIds($ids);
        array_walk($arr, function(userRoll $obj) {
            $this->data['translatedRollsByID'][$this->language->translate($obj->getRollName())] = $obj->getId();
        });

        return $this->data['translatedRollsByID'];
    }
    
    /**
     * Returns translated IDs by id string
     * @param string $data
     * @return array
     * @since 4.0.3
     */
    public function getRollsbyIdString(string $data) : array
    {
        if (!trim($data)) {
            return [];
        }

        $idx = 'getRollsbyIdString'.$data;
        if (isset($this->data[$idx]) && is_array($this->data[$idx]) && count($this->data[$idx])) {
            return $this->data[$idx];
        }

        $this->data[$idx] = [];

        $rolls = $this->getUserRollsByIds(explode(';', $data) );
        array_walk($rolls, function(userRoll $obj) use ($idx) {            
            $this->data[$idx][$this->language->translate($obj->getRollName())] = $obj->getId();
        });
        
        return $this->data[$idx];
    }

}
