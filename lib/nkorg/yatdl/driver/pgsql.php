<?php

namespace nkorg\yatdl\driver;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'driver.php';

/**
 * YaML Table Definition Language Parser Libary\n
 * Postgres Driver
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version 4.0
 */
class pgsql extends driver {

    /**
     * Create Table Statement erzeugen
     */
    public function createTableString(&$sqlArray)
    {
        $sqlArray[] = "CREATE TABLE {{dbpref}}_{$this->yamlArray->name} (";
        return true;
    }

    /**
     * Create Table Statement Abschluss-Zeile erzeugen
     */
    public function createTableEndline(&$sqlArray)
    {
        $sqlArray[] = ");";
        return true;
    }

    /**
     * Spalten parsen
     * @return boolean
     */
    public function createColRows(&$sqlArray)
    {
        foreach ($this->yamlArray->cols as $colName => $col) {

            $col = new \nkorg\yatdl\columnItem($col);
            if (!$this->checkYamlColRow($colName, $col)) {
                return false;
            }

            $colName = strtolower($colName);
            $sql = "{$colName}";

            $sql .= " {$this->colTypes[$col->type]}";
            $sql .= ($col->length && in_array($col->type, $this->lenghtTypes)) ? "({$col->length}) " : " ";

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
    public function createAutoincrement(array &$sqlArray, \nkorg\yatdl\autoIncrementItem $column)
    {
        $seqName = "{{dbpref}}_{$this->yamlArray->name}_{$column->colname}_seq";

        $seq = "CREATE SEQUENCE {$seqName}";
        $seq .= " START WITH {$column->start}";
        $seq .= " INCREMENT BY 1";
        $seq .= " NO MINVALUE";
        $seq .= " NO MAXVALUE";
        $seq .= " CACHE 1;";

        $sqlArray[] = $seq;
        $sqlArray[] = "ALTER SEQUENCE {$seqName} OWNED BY {{dbpref}}_{$this->yamlArray->name}.{$column->colname};";
        $sqlArray[] = "ALTER TABLE ONLY {{dbpref}}_{$this->yamlArray->name} ALTER COLUMN id SET DEFAULT nextval('{$seqName}'::regclass);";

        return true;
    }

    /**
     * Primary Key angabe anlegen
     * @return boolean
     */
    public function createPrimaryKey(&$sqlArray)
    {
        $sqlArray[] = "ALTER TABLE ONLY {{dbpref}}_{$this->yamlArray->name} ADD CONSTRAINT {{dbpref}}_{$this->yamlArray->name}_{$this->yamlArray->primarykey} PRIMARY KEY ({$this->yamlArray->primarykey});";
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

            $row = new \nkorg\yatdl\indiceItem($row);
            if (!$this->checkYamlIndiceRow($rowName, $row)) {
                return false;
            }

            if (is_array($row->col)) {
                $row->col = implode(',', $row->col);
            }

            $index = ($row->isUnqiue ? 'UNIQUE INDEX' : 'INDEX');
            $sql = "CREATE {$index} {{dbpref}}_{$this->yamlArray->name}_{$rowName} ON {{dbpref}}_{$this->yamlArray->name} USING btree ({$row->col});";

            $sqlArray[] = $sql;
        }

        return true;
    }

}
