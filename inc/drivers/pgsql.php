<?php
    /**
     * FanPress CM Postgres database driver class
     * 
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\drivers;

    /**
     * Postgres database driver class
     * 
     * @package fpcm\drivers\pgsql
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.2
     */ 
    final class pgsql implements sqlDriver {
        
        /**
         * Erzeugt DNS-String für \PDO:__construct
         * @param array $dbconfig
         * @return string
         * @link http://php.net/manual/de/pdo.construct.php
         */
        public function getPdoDns(array $dbconfig) {
            return 'dbname='.$dbconfig['DBNAME'].';host='.$dbconfig['DBHOST'];
        }
        
        /**
         * Liefert Options-Array für \PDO:__construct
         * @return array
         * @link http://php.net/manual/de/pdo.construct.php
         */        
        public function getPdoOptions() {
            return [];
        }
        
        /**
         * Erzeugt CONCAT SQl_String
         * @param array $fields
         * @return string
         */ 
        public function concatString(array $fields) {
            return ' CONCAT ('.implode(', ', array_map('trim', $fields)).') ';
        }

        /**
         * Erzeugt LIKE-SQL-String
         * @return string
         */
        public function getDbLike() {
            return 'LIKE';
        }

        /**
         * Erzeugt LIMIT-SQL-String
         * @param int $offset
         * @param int $limit
         * @return string
         */
        public function limitQuery($offset, $limit) {
            return ' LIMIT '.(int) $offset.' OFFSET '.(int) $limit;
        }
        
        /**
         * Erzeugt ORDER BY-SQL-String
         * @param array $conditions
         * @return string
         */  
        public function orderBy(array $conditions) {
            return ' ORDER BY '.implode(', ', array_map('trim', $conditions));
        }
        
        /**
         * Erzeugt Parameter für @see \PDO::lastInsertId()
         * @param string $table
         * @return string
         */
        public function getLastInsertIdParams($table) {
            return $table.'_id_seq';
        }

        /**
         * Query-String um Wert in angegebener Spalte zu negieren
         * @param string $field
         * @return string
         */
        public function getNotQuery($field) {
            return "$field = (CASE $field WHEN 1 THEN 0 WHEN 0 THEN 1 END)";
        }

        /**
         * Erzeugt Query für Optimierungsvorgang auf Datenbank-Tabellen
         * @param string $table
         * @return string
         * @since FPCM 3.3.0
         */
        public function optimize($table) {
            return "VACUUM $table";
        }

        /**
         * Query-String um Index auf Tabellenspalte zu setzen
         * @param string $table
         * @param string $indexName
         * @param string $field
         * @param string $isUnique
         * @return string
         * @since FPCM 3.3.1
         */
        public function createIndexString($table, $indexName, $field, $isUnique) {
            $index = ($isUnique ? 'UNIQUE INDEX' : 'INDEX');
            return "CREATE {$index} {$table}_{$indexName} ON {$table} USING btree ({$field});";
        }

        /**
         * Erzeugt CONCAT_WS SQL-String
         * @param string $delim
         * @param array $fields
         * @return string
         * @since FPCM 3.4
         */
        public function implodeCols($delim, array $fields) {
            return " CONCAT_WS('{$delim}', '".  implode("', '", $fields)."')";
        }

        /**
         * Datentyp-Mapping für Yaml-basierte Tabelle-Definitionen
         * @return array
         */
        public function getYaTDLDataTypes() {

            return array(
                'int'       => 'int',
                'bigint'    => 'bigint',
                'varchar'   => 'character varying',
                'text'      => 'text',
                'mtext'     => 'text',
                'ltext'     => 'text',
                'bool'      => 'smallint',
                'bin'       => 'bytea',
                'lbin'      => 'bytea',
                'float'     => 'real',
                'double'    => 'decimal',
                'char'      => 'char'
            );

        }

        /**
         * Liefert empfohlene Version für Datenbank-System zurück
         * @return string
         * @since FPCM 3.4
         */
        public function getRecommendVersion() {
            return '9.1';
        }

        /**
         * Liefert Struktur-Infos für eine Bestimmte Tabelle und ggf. Spalte zurück
         * @param string $table
         * @param string $field
         * @return array
         * @since FPCM 3.3.2
         */
        public function getTableStructureQuery($table, $field = false) {

            $query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$table}'";
            if ($field !== false && trim($field)) {
                $query .= " AND COLUMN_NAME = '{$field}'";
            }
            
            return $query;

        }

        /**
         * Bereitet Treiber-spezifische Struktur von Tabelle-Struktur-Infos aus
         * @param object $colRow
         * @param array $data
         * @return array
         * @since FPCM 3.3.2
         */
        public function prepareColRow($colRow, array &$data) {

            $data[$colRow->column_name] = array(
                'type'   => $colRow->data_type,
                'length' => (int) $colRow->character_maximum_length
            );
            
            
        }

    }
