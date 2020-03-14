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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class selectParams {

    /**
     * Database table name
     * @var string
     */
    private $table = '';

    /**
     * Select item string
     * @var string
     */
    private $item = '*';

    /**
     * Where clause
     * @var string
     */
    private $where = '';

    /**
     * JOIN clause
     * @var string
     */
    private $join = '';

    /**
     * Select params
     * @var array 
     */    
    private $params = [];

    /**
     * SELECT DISTINCT flag
     * @var bool
     */
    private $distinct = false;

    /**
     * Return raw result flag
     * @var bool
     */
    private $returnResult = false;

    /**
     * Fetch all mode
     * @var bool
     */
    private $fetchAll = false;

    /**
     * Fetch style
     * @var int
     * @since FPCm 4.2.1
     */
    private $fetchStyle = 5;

    /**
     * Constructor method, as of FPCm 4.1 the destination table(s) can be set directly
     * @param string|array $table (@since FPCM 4.1)
     */
    public function __construct($table = '')
    {
        $this->table = $table;
        $this->fetchStyle = \PDO::FETCH_OBJ;
    }

        /**
     * Returns database name(s)
     * @return string|array
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Returns items
     * @return string
     */
    public function getItem() : string
    {
        return $this->item;
    }

    /**
     * Returns where clause
     * @return string
     */
    public function getWhere() : string
    {
        return $this->where;
    }

    /**
     * Returns join clause
     * @return string
     */
    public function getJoin(): string
    {
        return $this->join;
    }
    
    /**
     * Return select params
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * return Distinct select mode
     * @return bool
     */
    public function getDistinct() : bool
    {
        return $this->distinct;
    }

    /**
     * Returns mode of result return
     * @return bool
     */
    public function getReturnResult() : bool
    {
        return $this->returnResult;
    }

    /**
     * Returns fetch mode
     * @return bool
     */
    public function getFetchAll() : bool
    {
        return $this->fetchAll;
    }

    /**
     * Returns fetch style
     * @return int
     * @since FPCm 4.2.1
     */
    public function getFetchStyle() : int {
        return $this->fetchStyle;
    }
    
    /**
     * Set database table name(s)
     * @param string|array $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set select items
     * @param string $item
     * @return $this
     */
    public function setItem(string $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Set Where clause
     * @param string $where
     * @return $this
     */
    public function setWhere(string $where)
    {
        $this->where = $where;
        return $this;
    }

    /**
     * Set JOIN clause
     * @param string $join
     * @return $this
     */
    public function setJoin(string $join)
    {
        $this->join = $join;
        return $this;
    }

    /**
     * Set select data params for where clause
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set distinct select mode
     * @param bool $distinct
     * @return $this
     */
    public function setDistinct(bool $distinct)
    {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * Set return of raw result
     * @param bool $returnResult
     * @return $this
     */
    public function setReturnResult(bool $returnResult)
    {
        $this->returnResult = $returnResult;
        return $this;
    }

    /**
     * Set fetch mode to all or single mode
     * @param bool $fetchAll
     * @return $this
     */
    public function setFetchAll(bool $fetchAll)
    {
        $this->fetchAll = $fetchAll;
        return $this;
    }

    /**
     * Set fetch style
     * @param int $fetchStyle
     * @return $this
     * @since FPCm 4.2.1
     */
    public function setFetchStyle(int $fetchStyle) {
        $this->fetchStyle = $fetchStyle;
        return $this;
    }



}
