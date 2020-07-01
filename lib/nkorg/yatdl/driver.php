<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * Driver Abstract
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL3.0
 */
abstract class driver {

    /**
     * In Array geparstes YAML-String
     * @var tableItem
     */
    protected $yamlArray;

    /**
     * Feldtypen aus \fpcm\classes\database::getYaTDLDataTypes()
     * @var array
     */
    protected $colTypes = [];

    /**
     * Datentypen mit Längen-Angabe
     * @var array
     */
    protected $lenghtTypes = [];

    /**
     * Konstruktor
     * @param array $types
     */
    public function __construct($types)
    {
        $this->colTypes = $types;
        $this->lenghtTypes = ['varchar', 'char'];
    }

    /**
     * YAML-Array setzen
     * @param type $yamlArray
     */
    public function setYamlArray($yamlArray)
    {
        $this->yamlArray = $yamlArray;
    }

    /**
     * Create Table Statement erzeugen
     * @return true
     */
    abstract public function createTableString(&$sqlArray);

    /**
     * Create Table Statement Abschluss-Zeile erzeugen
     * @return true
     */
    abstract public function createTableEndline(&$sqlArray);

    /**
     * Spalten parsen
     * @return boolean
     */
    abstract public function createColRows(&$sqlArray);

    /**
     * Auto Increment Angaben übersetzen
     * @param array $sqlArray SQL array data
     * @param autoIncrementItem $params Auto increment params
     * @return boolean
     */
    abstract public function createAutoincrement(array &$sqlArray, autoIncrementItem $column);

    /**
     * Primary Key angabe anlegen
     * @return boolean
     */
    abstract public function createPrimaryKey(&$sqlArray);

    /**
     * Index-Angabe erzeugen
     * @return boolean
     */
    abstract public function createIndices(&$sqlArray);

    /**
     * Index-Zeile prüfen, ob alle nötigen Daten vorhanden sind
     * @param string $rowName
     * @param indiceItem $row
     * @return boolean
     */
    protected function checkYamlIndiceRow($rowName, indiceItem $row)
    {
        if (!$rowName) {
            trigger_error('Invalid YAML indice row data, key must include column name!');
            return false;
        }

        if ($row->col === null || (!is_array($row->col) && !trim($row->col))) {
            trigger_error('Invalid YAML indice row data, no "col" property found or property is empty!');
            return false;
        }

        if (is_array($row->col) && !count($row->col) ) {
            trigger_error('Invalid YAML indice row data, "col" property found as list but missing items!');
            return false;
        }

        if ($row->isUnqiue === null || !in_array($row->isUnqiue, [true, false, 0 , 1, 'true', 'false'])) {
            trigger_error('Invalid YAML indice row data, no "inUique" property found!');
            return false;
        }

        return true;
    }

    /**
     * Spalten-Zeile prüfen, ob alle nötigen Daten vorhanden sind
     * @param string $colName
     * @param columnItem $col
     * @return boolean
     */
    protected function checkYamlColRow($colName, columnItem $col)
    {
        if (!$colName) {
            trigger_error('Invalid YAML col data, key must include column name!');
            return false;
        }

        if ($col->type === null || is_array($col->type) || !trim($col->type)) {
            trigger_error('Invalid YAML col data, no "type" property found!');
            return false;
        }

        if (!isset($this->colTypes[$col->type])) {
            trigger_error('Invalid YAML col data, undefined col type found!');
            return false;
        }

        if ($col->length === null) {
            trigger_error('Invalid YAML col data, no "Lenght" property found!');
            return false;
        }

        if ($col->params === null && !$col->hasProperty('params')) {
            trigger_error('Invalid YAML col data, no "params" property found!');
            return false;
        }

        if ($col->defaultValue !== null && !is_scalar($col->defaultValue)) {
            trigger_error('Invalid YAML col data, "defaultValue" property must be a scalar value (like string, char, integer, float)!');
            return false;
        }

        return true;
    }

}
