<?php

    namespace nkorg\yatdl;

    require_once __DIR__.DIRECTORY_SEPARATOR.'driver.php';

    /**
     * YaML Table Definition Language Parser Libary\n
     * MySQL Driver
     * 
     * @package nkorg\yatdl
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @version YaTDL2.0
     */
    class mysql extends driver {

        /**
         * Konstruktor
         * @param array $types
         */
        public function __construct(array $types) {

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
        public function createTableString(&$sqlArray) {
            
            $sqlArray[] = "CREATE TABLE IF NOT EXISTS `{{dbpref}}_{$this->yamlArray['name']}` (";
            return true;
            
        }
        
        /**
         * Create Table Statement Abschluss-Zeile erzeugen
         */
        public function createTableEndline(&$sqlArray) {

            $sqlArray[] = ") ENGINE={$this->yamlArray['engine']} DEFAULT CHARSET={$this->yamlArray['charset']}".
                          " AUTO_INCREMENT={$this->yamlArray['autoincrement']['start']};";
            return true;
            
        }
        
        /**
         * Spalten parsen
         * @return boolean
         */
        public function createColRows(&$sqlArray) {

            foreach ($this->yamlArray['cols'] as $colName => $col) {
                
                if (!$this->checkYamlColRow($colName, $col)) {
                    return false;
                }

                $colName = strtolower($colName);
                $sql = "`{$colName}`";
                
                $sql .= " {$this->colTypes[$col['type']]}";
                $sql .= ($col['length'] && in_array($col['type'], $this->lenghtTypes))
                      ? "({$col['length']}) " 
                      : " ";

                if ($col['params']) {
                    $sql .= $col['params'];
                }
                
                $sqlArray['cols'][$colName] = $sql;
                
            }
            
            return true;
            
        }
        
        /**
         * Auto Increment Angaben übersetzen
         * @return boolean
         */
        public function createAutoincrement(&$sqlArray) {
            
            $sqlArray['cols'][$this->yamlArray['autoincrement']['colname']] .= ' AUTO_INCREMENT';
            return true;

        }
        
        /**
         * Primary Key angabe anlegen
         * @return boolean
         */
        public function createPrimaryKey(&$sqlArray) {

            $sqlArray['cols'][] = "PRIMARY KEY (`{$this->yamlArray['primarykey']}`)";
            
            return true;
        }
        
        /**
         * Index-Angabe erzeugen
         * @return boolean
         */
        public function createIndices(&$sqlArray) {

            foreach ($this->yamlArray['indices'] as $rowName => $row) {
                
                if (!$this->checkYamlIndiceRow($rowName, $row)) {
                    return false;
                }

                if (is_array($row['col'])) {
                    $row['col'] = implode('`,`', $row['col']);
                }

                $index = ($row['isUnqiue'] ? 'UNIQUE' : 'INDEX');
                $sql   = "ALTER TABLE {{dbpref}}_{$this->yamlArray['name']} ADD {$index} `{$rowName}` ( `{$row['col']}` );";

                $sqlArray[] = $sql;
                
            }
            
            return true;
        }

    }
