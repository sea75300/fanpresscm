<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dbal;

/**
 * IP-Listen Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class selectParams {

    /**
     *
     * @var string
     */
    private $table = '';

    /**
     *
     * @var string
     */
    private $item = '*';

    /**
     *
     * @var string
     */
    private $where = '';

    /**
     *
     * @var array 
     */    
    private $params = [];

    /**
     *
     * @var bool
     */
    private $distinct = false;

    /**
     *
     * @var bool
     */
    private $returnResult = false;

    /**
     *
     * @var bool
     */
    private $fetchAll = false;

    /**
     * 
     * @return string
     */
    public function getTable() : string
    {
        return $this->table;
    }

    /**
     * 
     * @return string
     */
    public function getItem() : string
    {
        return $this->item;
    }

    /**
     * 
     * @return string
     */
    public function getWhere() : string
    {
        return $this->where;
    }

    /**
     * 
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * 
     * @return bool
     */
    public function getDistinct() : bool
    {
        return $this->distinct;
    }

    /**
     * 
     * @return bool
     */
    public function getReturnResult() : bool
    {
        return $this->returnResult;
    }

    /**
     * 
     * @return bool
     */
    public function getFetchAll() : bool
    {
        return $this->fetchAll;
    }

    /**
     * 
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 
     * @param string $item
     * @return $this
     */
    public function setItem(string $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * 
     * @param string $where
     * @return $this
     */
    public function setWhere(string $where)
    {
        $this->where = $where;
        return $this;
    }

    /**
     * 
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * 
     * @param bool $distinct
     * @return $this
     */
    public function setDistinct(bool $distinct)
    {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * 
     * @param bool $returnResult
     * @return $this
     */
    public function setReturnResult(bool $returnResult)
    {
        $this->returnResult = $returnResult;
        return $this;
    }

    /**
     * 
     * @param bool $fetchAll
     * @return $this
     */
    public function setFetchAll(bool $fetchAll)
    {
        $this->fetchAll = $fetchAll;
        return $this;
    }


}
