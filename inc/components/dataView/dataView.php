<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Simple data view component
 * 
 * @package fpcm\components\dataView
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
     * Init data view
     * @var bool
     */
    protected $init     = '';
    
    /**
     * Konstruktor
     * @param string $name
     * @param bool $init
     */
    public function __construct($name, $init = true)
    {
        $this->name = $name;
        $this->init = $init;
    }
    
    /**
     * Returns dataview columns
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns dataview rowns
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Returns dataview name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dataview rowns
     * @param array $rows
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Set dataview name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Add single dataview row
     * @param array $row
     */
    public function addRow(row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * Add dataview colunms
     * @param array $columns
     */
    public function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * Add single dataview colunm
     * @param \fpcm\components\dataView\column $column
     */
    public function addColumn(column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * Get dataview JavaScript components
     * @return array
     */
    public function getJsFiles()
    {
        return ['ui/dataview.js'];
    }

    /**
     * Get dataview JavaScript variables array
     * @return array
     */
    public function getJsVars()
    {
        return [
            'dataviews'         => [
                $this->name     => [
                    'columns'   => $this->columns,
                    'rows'      => $this->rows,
                    'init'      => $this->init
                ],
                'rolColTypes'   => [
                    'coltypeValue'     => rowCol::COLTYPE_VALUE,
                    'coltypeElement'   => rowCol::COLTYPE_ELEMENT
                ],
                'data'          => []
            ]
        ];
    }

    /**
     * Returns dataview JavaScript language variables
     * @return array
     */
    public function getJsLangVars()
    {
        return ['GLOBAL_NOTFOUND2'];
    }

}
