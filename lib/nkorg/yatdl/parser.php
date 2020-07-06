<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * Parser
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL4.0
 */
final class parser {

    /**
     * Check von geparstem YAML-String fehlgeschlagen
     */
    const ERROR_YAMLCHECK_FAILED = -1;

    /**
     * Beim Parsen der Spalten ist ein Feler aufgetreten
     */
    const ERROR_YAMLPARSER_COLS = -2;

    /**
     * Beim Parsen der Spalten ist ein Feler aufgetreten
     */
    const ERROR_YAMLPARSER_AUTOINCREMENT = -3;

    /**
     * Beim Parsen von Index-Definitionen ist ein Feler aufgetreten
     */
    const ERROR_YAMLPARSER_INDICES = -4;

    /**
     * Error while parsing a view file
     */
    const ERROR_YAMLPARSER_VIEW = -5;

    /**
     * In Array geparstes YAML-String
     * @var tableItem
     */
    protected $yamlArray;

    /**
     * Array mit SQL-Strings
     * @var array
     */
    protected $sqlArray = [];

    /**
     * fertige SQL-Query
     * @var string
     */
    protected $sqlString = '';

    /**
     * Parsen erfolgreich abgeschlossen
     * @var bool
     */
    protected $parsingOk = false;

    /**
     * Zusätzliches Tabellen-Prefix
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * Datentypen mit Längen-Angabe
     * @var driver
     */
    protected $driver = [];

    /**
     * Current databse driver
     * @var string
     */
    protected $currentDriver = '';

    /**
     * Liste mit Datentypen, die gemappt sein müssen
     * @var array
     */
    protected $dataTypeList = [
        'int', 'bigint',
        'varchar', 'text', 'mtext', 'ltext', 'char',
        'bool', 'bin', 'lbin',
        'float', 'double'
    ];

    /**
     * 
     * @param array $yamlArray
     * @param string $driver
     * @param array $dataTypes
     * @throws Exception
     */
    public function __construct(array $yamlArray, string $driver, array $dataTypes)
    {
        $drvPath = __DIR__ . DIRECTORY_SEPARATOR . $driver . '.php';
        if (!file_exists($drvPath)) {
            throw new \Exception('Unsupported database driver ' . $driver);
        }

        $dtCheck = $this->checkDataTypes($dataTypes);
        if (is_array($dtCheck)) {
            throw new \Exception('Data type map check failed. Undefined data types found: ' . implode(', ', $dtCheck));
        }

        $this->currentDriver = $driver;
        $className = 'nkorg\\yatdl\\' . $driver;
        $this->driver = new $className($dataTypes);
        $this->yamlArray = $yamlArray;
    }

    /**
     * Setzt zusätzliches Tabellen-Prefix
     * @param string $tablePrefix
     */
    public function setTablePrefix(string $tablePrefix)
    {
        $this->yamlArray['name'] = $tablePrefix . $this->yamlArray['name'];
    }

    /**
     * Parst Array aus YAML-String in SQL-String
     * @return boolean
     */
    public function parse()
    {
        $this->sqlArray = [];
        $this->sqlString = '';

        if (!$this->checkYamlArray()) {
            return self::ERROR_YAMLCHECK_FAILED;
        }
        
        $this->driver->setYamlArray($this->yamlArray);
        if ($this->yamlArray->isview === null) {
            return $this->parseTable();
        }

        return $this->parseView();
    }

    /**
     * Gibt fertigen SQL-String zurück
     * @return string
     */
    public function getSqlString()
    {
        if (!$this->parsingOk || !is_string($this->sqlString)) {
            return '';
        }

        return $this->sqlString;
    }

    /**
     * 
     * @return bool
     */
    public function getParsingOk()
    {
        return $this->parsingOk;
    }

    /**
     * Debug-Ausgabe von geparstem YAML-String
     */
    public function dumpYamlArray()
    {
        print '<pre>' . print_r($this->yamlArray, true) . '</pre>';
    }

    /**
     * Gibt geparsten YAML-String als Array zurück
     * @return array
     */
    public function getArray()
    {
        return $this->yamlArray;
    }

    /**
     * Parse yatdl-file as table
     * @return bool
     * @since YaTDL4.0
     */
    private function parseTable() : bool
    {
        $this->driver->createTableString($this->sqlArray);

        if (!$this->driver->createColRows($this->sqlArray)) {
            return self::ERROR_YAMLPARSER_COLS;
        }

        $this->driver->createTableEndline($this->sqlArray);

        if (!$this->createAutoincrement()) {
            return self::ERROR_YAMLPARSER_AUTOINCREMENT;
        }

        $this->createPrimaryKey();

        if (!$this->createIndices()) {
            return self::ERROR_YAMLPARSER_INDICES;
        }

        $this->createDefaultInsert();

        $this->sqlArray['cols'] = implode(',' . PHP_EOL, $this->sqlArray['cols']);
        $this->sqlString = implode(PHP_EOL, $this->sqlArray);

        $this->parsingOk = true;
        return true;
    }

    /**
     * Parse yatdl-file as view
     * @return bool
     * @since YaTDL4.0
     */
    private function parseView() : bool
    {
        if ($this->yamlArray->isview === '') {
            return self::ERROR_YAMLPARSER_VIEW;
        }

        if (!$this->yamlArray->isview) {
            return false;
        }

        $this->createViewString();
        $this->parsingOk = true;
        return true;
    }

    /**
     * Auto Increment Angaben übersetzen
     * @return boolean
     */
    private function createAutoincrement()
    {
        $aiItem = new autoIncrementItem($this->yamlArray->autoincrement);
        if ($aiItem->start === null) {
            trigger_error('Invalid YAML autoincrement data, no "start" property found!');
            return false;
        }

        if ($aiItem->colname === null) {
            trigger_error('Invalid YAML autoincrement data, no "colname" property found!');
            return false;
        }

        return $this->driver->createAutoincrement($this->sqlArray, $aiItem);
    }

    /**
     * Primary Key angabe anlegen
     * @return boolean
     */
    private function createPrimaryKey()
    {
        return $this->driver->createPrimaryKey($this->sqlArray);
    }

    /**
     * Index-Angabe erzeugen
     * @return boolean
     */
    private function createIndices()
    {
        if (!is_array($this->yamlArray->indices) || !count($this->yamlArray->indices)) {
            return true;
        }

        return $this->driver->createIndices($this->sqlArray);
    }

    /**
     * Standard-Werte-Einfügen erzeugen
     * @return boolean
     */
    private function createDefaultInsert()
    {
        if (!isset($this->yamlArray->defaultvalues) || !is_array($this->yamlArray->defaultvalues['rows']) || !count($this->yamlArray->defaultvalues['rows'])) {
            return true;
        }

        $textTypes = array('varchar', 'text', 'mtext', 'bin');

        $values = [];
        foreach ($this->yamlArray->defaultvalues['rows'] as $row) {

            $rowVal = [];
            foreach ($row as $col => $colval) {
                $rowVal[] = (in_array($this->yamlArray->cols[$col]['type'], $textTypes) ? "'{$colval}'" : $colval);
            }

            $values[] = implode(', ', $rowVal);
        }

        $cols = implode(', ', array_keys($this->yamlArray->cols));
        $values = implode('), (', $values);

        $this->sqlArray['defaultinsert'] = "INSERT INTO {{dbpref}}_{$this->yamlArray->name} ({$cols}) VALUES ($values);";

        return true;
    }

    /**
     * Array aus \Spyc-gepartem YAML-String prüfen
     * @return boolean
     */
    private function checkYamlArray()
    {
        if (!is_array($this->yamlArray)) {
            trigger_error('Invalid YAML data, no valid data available!');
            return false;
        }
        
        if (array_key_exists('isview', $this->yamlArray) && $this->yamlArray['isview']) {

            if (!array_key_exists('query', $this->yamlArray) && !array_key_exists('query'.$this->currentDriver, $this->yamlArray)) {
                trigger_error('Invalid YAML data, no "query" property found!');
                return false;
            }

            $this->yamlArray = new tableItem($this->yamlArray);
            return true;
        }

        if (!array_key_exists('name', $this->yamlArray) || !trim($this->yamlArray['name'])) {
            trigger_error('Invalid YAML data, no "name" property found!');
            return false;
        }

        if (!array_key_exists('cols', $this->yamlArray) || !is_array($this->yamlArray['cols']) || !count($this->yamlArray['cols'])) {
            trigger_error('Invalid YAML data, no "cols" property found!');
            return false;
        }

        if (!array_key_exists('indices', $this->yamlArray)) {
            trigger_error('Invalid YAML data, no "index" property found!');
            return false;
        }

        if (!array_key_exists('primarykey', $this->yamlArray)) {
            trigger_error('Invalid YAML data, no "primarykey" property found!');
            return false;
        }

        if (!array_key_exists('charset', $this->yamlArray)) {
            trigger_error('Invalid YAML data, no "charset" property found!');
            return false;
        }

        if (!array_key_exists('autoincrement', $this->yamlArray)) {
            trigger_error('Invalid YAML data, no "autoincrement" property found!');
            return false;
        }

        if (!array_key_exists('engine', $this->yamlArray)) {
            trigger_error('Invalid YAML data, no "engine" property found!');
            return false;
        }

        $this->yamlArray = new tableItem($this->yamlArray);
        return true;
    }

    /**
     * Prüfung, ob alle Datentypen definiert wurden
     * @param array $dataTypes
     * @return boolean
     */
    private function checkDataTypes(array $dataTypes)
    {
        $map = array_diff($this->dataTypeList, array_keys($dataTypes));
        if (count($map)) {
            return $map;
        }

        return true;
    }

    /**
     * Create view statement
     * @return bool
     * @since @since YaTDL4.0
     */
    private function createViewString() : bool
    {
        $queryStr   = $this->yamlArray->{'query'.$this->currentDriver}
                    ? $this->yamlArray->{'query'.$this->currentDriver}
                    : $this->yamlArray->query;
                    
                    
        if (!trim($queryStr)) {
            return false;
        }

        $this->sqlString = 'CREATE OR REPLACE VIEW {{dbpref}}_'.$this->yamlArray->name.' AS '.$queryStr;
        return true;
    }

}
