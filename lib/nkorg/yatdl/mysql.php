<?php

namespace nkorg\yatdl;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'driver.php';

/**
 * YaML Table Definition Language Parser Libary\n
 * MySQL Driver
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL3.0
 */
class mysql extends driver {

    /**
     * Konstruktor
     * @param array $types
     */
    public function __construct(array $types)
    {
        parent::__construct($types);
        $this->lenghtTypes[] = 'int';
        $this->lenghtTypes[] = 'bigint';
        $this->lenghtTypes[] = 'bool';
        $this->lenghtTypes[] = 'smallint';
        $this->lenghtTypes[] = 'float';
        $this->lenghtTypes[] = 'double';
    }

    /**
     * Create Table Statement erzeugen
     */
    public function createTableString(&$sqlArray)
    {
        $sqlArray[] = "CREATE TABLE IF NOT EXISTS `{{dbpref}}_{$this->yamlArray->name}` (";
        return true;
    }

    /**
     * Create Table Statement Abschluss-Zeile erzeugen
     */
    public function createTableEndline(&$sqlArray)
    {
        if (!$this->yamlArray->autoincrement) {
            return true;
        }
        
        $aiItem = new autoIncrementItem($this->yamlArray->autoincrement);
        $sqlArray[] = ") ENGINE={$this->yamlArray->engine} DEFAULT CHARSET={$this->yamlArray->charset} AUTO_INCREMENT={$aiItem->start};";
        return true;
    }

    /**
     * Spalten parsen
     * @return boolean
     */
    public function createColRows(&$sqlArray)
    {
        foreach ($this->yamlArray->cols as $colName => $col) {
            
            $col = new columnItem($col);
            if (!$this->checkYamlColRow($colName, $col)) {
                return false;
            }            

            $colName = strtolower($colName);
            $sql = "`{$colName}`";

            $sql .= " {$this->colTypes[$col->type]}";
            $sql .= ($col->length && in_array($col->type, $this->lenghtTypes)) ? "({$col->length}) " : " ";
            $sql .= ($col->charset) ? "CHARACTER SET {$col->charset} " : " ";

            if ($col->params) {
                $sql .= $col->params;
            }

            if ($col->defaultValue) {
                $sql .= " DEFAULT '{$col->defaultValue}'";
            }

            $sqlArray['cols'][$colName] = $sql;
        }

        return true;
    }

    /**
     * Auto Increment Angaben übersetzen
     * @param array $sqlArray SQL array data
     * @param autoIncrementItem $params Auto increment params
     * @return boolean
     */
    public function createAutoincrement(array &$sqlArray, autoIncrementItem $column)
    {
        $sqlArray['cols'][$column->colname] .= ' AUTO_INCREMENT';
        return true;
    }

    /**
     * Primary Key angabe anlegen
     * @return boolean
     */
    public function createPrimaryKey(&$sqlArray)
    {
        $sqlArray['cols'][] = "PRIMARY KEY (`{$this->yamlArray->primarykey}`)";
        return true;
    }

    /**
     * Index-Angabe erzeugen
     * @return boolean
     */
    public function createIndices(&$sqlArray)
    {
        if (!is_array($this->yamlArray->indices) || !count($this->yamlArray->indices)) {
            return true;
        }

        foreach ($this->yamlArray->indices as $rowName => $row) {
            
            $row = new indiceItem($row);
            if (!$this->checkYamlIndiceRow($rowName, $row)) {
                return false;
            }

            if (is_array($row->col)) {
                $row->col = implode('`,`', $row->col);
            }

            $index = ($row->isUnqiue ? 'UNIQUE' : 'INDEX');
            $sql = "ALTER TABLE {{dbpref}}_{$this->yamlArray->name} ADD {$index} `{$rowName}` ( `{$row->col}` );";

            $sqlArray[] = $sql;
        }

        return true;
    }

}
