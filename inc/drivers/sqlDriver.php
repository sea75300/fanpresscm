<?php
    /**
     * FanPress CM Database driver base class
     * 
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\drivers;

    /**
     * Database driver base class
     * 
     * @package fpcm\drivers\sqlDriver
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.2
     */ 
    interface sqlDriver {
        
        /**
         * Erzeugt DNS-String für \PDO:__construct
         * @param array $dbconfig
         * @return string
         * @link http://php.net/manual/de/pdo.construct.php
         */
        public function getPdoDns(array $dbconfig);
        
        /**
         * Liefert Options-Array für \PDO:__construct
         * @return array
         * @link http://php.net/manual/de/pdo.construct.php
         */
        public function getPdoOptions();

        /**
         * Erzeugt LIKE-SQL-String
         * @return string
         */
        public function getDbLike();

        /**
         * Erzeugt Query für Optimierungsvorgang auf Datenbank-Tabellen
         * @param string $table
         * @return string
         * @since FPCM 3.3.0
         */
        public function optimize($table);

        /**
         * Erzeugt LIMIT-SQL-String
         * @param int $limit
         * @param int $offset
         * @return string
         */
        public function limitQuery($limit, $offset);
        
        /**
         * Erzeugt ORDER BY-SQL-String
         * @param array $conditions
         * @return string
         */        
        public function orderBy(array $conditions);
        
        /**
         * Erzeugt CONCAT SQL-String
         * @param array $fields
         * @return string
         */        
        public function concatString(array $fields);
        
        /**
         * Erzeugt CONCAT_WS SQL-String
         * @param string $delim
         * @param array $fields
         * @return string
         * @since FPCM 3.4
         */       
        public function implodeCols($delim, array $fields);
        
        /**
         * Erzeugt Parameter für @see \PDO::lastInsertId()
         * @param string $table
         * @return string
         */
        public function getLastInsertIdParams($table);
        
        /**
         * Query-String um Wert in angegebener Spalte zu negieren
         * @param string $field
         * @return string
         */
        public function getNotQuery($field);
        
        /**
         * Datentyp-Mapping für Yaml-basierte Tabelle-Definitionen
         * @return array
         */
        public function getYaTDLDataTypes();

        /**
         * Query-String um Index auf Tabellenspalte zu setzen
         * @param string $table
         * @param string $indexName
         * @param string $field
         * @param string $isUnique
         * @return string
         * @since FPCM 3.3.1
         */
        public function createIndexString($table, $indexName, $field, $isUnique);

        /**
         * Liefert Struktur-Infos für eine Bestimmte Tabelle und ggf. Spalte zurück
         * @param string $table
         * @param string $field
         * @return array
         * @since FPCM 3.3.2
         */
        public function getTableStructureQuery($table, $field = false);
        
        /**
         * Bereitet Treiber-spezifische Struktur von Tabelle-Struktur-Infos aus
         * @param object $colRow
         * @param array $data
         * @return array
         * @since FPCM 3.3.2
         */
        public function prepareColRow($colRow, array &$data);

        /**
         * Liefert empfohlene Version für Datenbank-System zurück
         * @return string
         * @since FPCM 3.4
         */
        public function getRecommendVersion();
        
    }
