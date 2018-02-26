<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Simple data view component
 * 
 * @package fpcm\drivers\mysql
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class dataView {

    /**
     * Data view columns
     * @var array
     */
    protected $columns  = [];

    /**
     * Data rows
     * @var array
     */
    protected $rows     = [];

    /**
     * Data view name
     * @var string
     */
    protected $name     = '';

    /**
     * Konstruktor
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * 
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * 
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @param array $rows
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;
    }

    /**
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * 
     * @param array $row
     */
    public function addRow(row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * 
     * @param array $columns
     */
    public function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * 
     * @param \fpcm\components\dataView\column $column
     */
    public function addColumn(column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * 
     * @return array
     */
    public function getJsFiles()
    {
        return ['dataview.js'];
    }

    /**
     * 
     * @return array
     */
    public function getJsVars()
    {
        return [
            'dataviews'         => [
                $this->name     => [
                    'columns'   => $this->columns,
                    'rows'      => $this->rows
                ],
                'rolColTypes'   => [
                    'coltypeValue'     => rowCol::COLTYPE_VALUE,
                    'coltypeElement'   => rowCol::COLTYPE_ELEMENT
                ]
            ]
        ];
    }

    /**
     * 
     * @return array
     */
    public function getJsLangVars()
    {
        return ['GLOBAL_NOTFOUND2'];
    }

}
