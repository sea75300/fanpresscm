<?php
    namespace nkorg\yatdl;

    /**
     * YaML Table Definition Language Parser Libary\n
     * Driver Abstract
     * 
     * @package nkorg\yatdl
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @version YaTDL2.0
     */
    abstract class driver {
        
        /**
         * In Array geparstes YAML-String
         * @var array
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
        public function __construct($types) {

            $this->colTypes    = $types;            
            $this->lenghtTypes = ['varchar', 'char'];

        }

        /**
         * YAML-Array setzen
         * @param type $yamlArray
         */
        public function setYamlArray($yamlArray) {
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
         * @return boolean
         */
        abstract public function createAutoincrement(&$sqlArray);
        
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
         * @param array $row
         * @return boolean
         */
        protected function checkYamlIndiceRow($rowName, array $row) {
            
            if (!isset($row['col']) || (is_array($row['col']) && !count($row['col'])) || (!is_array($row['col']) && !trim($row['col']))) {
                trigger_error('Invalid YAML indice row data, no "col" property found!');
                return false;
            }

            if (!$rowName) {
                trigger_error('Invalid YAML indice row data, key must include column name!');
                return false;
            }

            if (!isset($row['isUnqiue'])) {
                trigger_error('Invalid YAML indice row data, no "name" property found!');
                return false;
            }
            
            return true;
            
        }

        /**
         * Spalten-Zeile prüfen, ob alle nötigen Daten vorhanden sind
         * @param string $colName
         * @param array $col
         * @return boolean
         */
        protected function checkYamlColRow($colName, array $col) {
            
            if (!$colName) {
                trigger_error('Invalid YAML col data, key must include column name!');
                return false;
            }

            if (!isset($col['type']) || (is_array($col['type']) && !count($col['type']))) {
                trigger_error('Invalid YAML col data, no "type" property found!');
                return false;
            }

            if (!isset($this->colTypes[$col['type']])) {
                trigger_error('Invalid YAML col data, undefined col type found!');
                return false;
            }

            if (!isset($col['length'])) {
                trigger_error('Invalid YAML col data, no "isNull" property found!');
                return false;
            }

            if (!isset($col['params'])) {
                trigger_error('Invalid YAML col data, no "params" property found!');
                return false;
            }
            
            return true;
            
        }

    }
