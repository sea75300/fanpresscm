<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * FanPress CM Database abstraction layer
     * 
     * @package fpcm\classes\database
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     */ 
    final class database {

        /**
         * Article-Tabelle
         */
        const tableArticles     = 'articles';
        
        /**
         * Benutzer-Tabelle
         */
        const tableAuthors      = 'authors';
        
        /**
         * Kategorie-Tabelle
         */
        const tableCategories   = 'categories';
        
        /**
         * Kommentar-Tabelle
         */
        const tableComments     = 'comments';
        
        /**
         * Config-Tabelle
         */
        const tableConfig       = 'config';
        
        /**
         * Cronjob-Tabelle
         */
        const tableCronjobs     = 'cronjobs';
        
        /**
         * Dateiindex-Tabelle
         */
        const tableFiles        = 'uploadfiles';
        
        /**
         * Tabeller gesperrter IP-Adressen
         */
        const tableIpAdresses   = 'blockedip';
        
        /**
         * Modul-Tabelle
         */
        const tableModules      = 'modules';
        
        /**
         * Tabelle für Berechtigungen
         */
        const tablePermissions  = 'permissions';
        
        /**
         * Benutzerrollen-Tabelle
         */
        const tableRoll         = 'userrolls';
        
        /**
         * Sessions-Tabelle
         */
        const tableSessions     = 'sessions';
        
        /**
         * Smiley-Tabelle
         */
        const tableSmileys      = 'smileys';
        
        /**
         * Wordsperre-Tabelle
         * @since FPCM 3.2
         */
        const tableTexts  = 'texts';
        
        /**
         * Tabelle für revisionen
         * @since FPCM 3.3
         */
        const tableRevisions  = 'revisions';

        /**
         * Datenbank-TypKonstante MySQL
         * @since FPCM 3.5
         */
        const DBTYPE_MYSQLMARIADB = 'mysql';

        /**
         * Datenbank-TypKonstante Postgres
         * @since FPCM 3.5
         */        
        const DBTYPE_POSTGRES = 'pgsql';

        /**
         * Liste mit unterstützten Datenbanksystemen
         * @since FPCM 3.3
         * mysql => MySQL 5.5 + oder MariaDB 10 +
         * pgsql => Postgres 9 +
         */
        public static $supportedDBMS = array(
            'mysql' => 'MySQL/ MariaDB',
            'pgsql' => 'Postgres'
        );

        /**
         * Datenbank-Verbindung
         * @var \PDO
         */
        private $connection;
        
        /**
         * Datenbank-Treiber
         * @var \fpcm\drivers\sqlDriver
         * @since FPCM 3.2
         */
        private $driver;

        /**
         * Tabellen-Prefix
         * @var string
         */
        private $dbprefix;

        /**
         * Datenbank-Typ
         * @var string
         * @since FPCM 3.2
         */
        private $dbtype;
        
        /**
         * letzter ausgeführter Datenbank-Querystring
         * @var string
         */
        private $lastQueryString = '';
        
        /**
         * Anzahl an Datenbank abgesetzte Queries
         * @var int
         */
        private $queryCount = 0;
        
        /**
         * Tabelle, in welcher zuletzt eine Aktion durchgeführt wurde
         * @var string
         * @since FPCM 3.2
         */
        private $lastTable = '';
        
        /**
         * SQL-Query via explain testen
         * @var bool
         * @since FPCM 3.6
         */
        private $explain = false;

        /**
         * Konstruktor
         * @param array $dbconfig alternative Datenbank-Zugangsdaten, wenn false werden Daten aus FPCM-Config genutzt
         * @param bool $dieOnError wenn Verbindung fehlschlägt, soll Ausführung vollständig abgebrochen werden
         * @return void
         */
        public function __construct($dbconfig = false, $dieOnError = true) {   

            $dbconfig   = (is_array($dbconfig)
                        ? $dbconfig
                        : baseconfig::getDatabaseConfig());

            $driverClass = '\\fpcm\\drivers\\'.$dbconfig['DBTYPE'];

            if (!class_exists($driverClass)) {
                fpcmLogSql('SQL driver not found for '.$dbconfig['DBTYPE']);
                $this->dieError();
            }
            
            $this->driver = new $driverClass();
            if (!is_a($this->driver, '\\fpcm\\drivers\\sqlDriver')) {
                fpcmLogSql('SQL driver '.$driverClass.' must be an instance of "\\fpcm\\drivers\\sqlDriver"!');
                $this->dieError();
            }

            try {
                $this->connection = new \PDO($dbconfig['DBTYPE'].':'.$this->driver->getPdoDns($dbconfig), $dbconfig['DBUSER'], $dbconfig['DBPASS'], $this->driver->getPdoOptions());
            } catch(PDOException $e) {
                fpcmLogSql($e->getMessage());
                if (!$dieOnError) {
                    return;
                }
                $this->dieError();
            }

            $this->dbprefix = $dbconfig['DBPREF'];
            $this->dbtype   = $dbconfig['DBTYPE'];
        }

        /**
         * Der Destruktor
         */
        public function __destruct() {
            $this->connection = null;
        }

        /**
         * Führt SELECT-Befehl auf DB aus
         * @param string $table select table
         * @param string $item select items
         * @param string|null $where select condition
         * @param array $params select condition params
         * @param bool $distinct Distinct select
         * @return mixed
         */
        public function select($table, $item = '*', $where = null, array $params = [], $distinct = false) {            
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql = $distinct ? "SELECT DISTINCT $item FROM $table" : "SELECT $item FROM $table";
            if (!is_null($where)) $sql .= " WHERE $where";

            return $this->query($sql, $params);            
        }

        /**
         * Führt INSERT-Befehl auf DB aus
         * @param string $table
         * @param string $fields
         * @param string $values
         * @param array $params
         * @return bool|int
         */
        public function insert($table, $fields, $values, array $params = []) {
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql = "INSERT INTO $table ($fields) VALUES ($values);";

            $this->lastTable = $table;
            
            $this->exec($sql, $params);
            return $this->getLastInsertId();
        }

        /**
         * Führt UPDATE-Befehl auf DB aus
         * @param string $table
         * @param array $fields
         * @param array $params
         * @param string $where
         * @return bool
         */
        public function update($table, array $fields, array $params = [], $where = null) {
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql = "UPDATE $table SET ";
            $sql .= implode(' = ?, ', $fields).' = ?';            
            if (!is_null($where)) $sql .= " WHERE $where";
            return $this->exec($sql, $params);            
        }

        /**
         * Führt mehrere UPDATE-Befehl auf DB mit einmal aus
         * @param string $table
         * @param array $fields
         * @param array $params
         * @param array $where
         * @return bool
         * @since FPCM 3.5
         */
        public function updateMultiple($table, array $fields, array $params = [], array $where = []) {

            if ($this->dbtype === self::DBTYPE_POSTGRES) {
                $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
            }

            $sql    = '';
            $values = [];

            $updateStr = 'UPDATE '.$this->getTablePrefixed($table).' SET '.implode(' = ?, ', $fields).' = ?';

            foreach ($params as $i => $row) {

                $sql .= $updateStr;

                if (isset($where[$i])) {
                    $sql .= " WHERE {$where[$i]}";
                }

                $values  = array_merge($values, $row);
                $sql    .= ';'.PHP_EOL;
            }

            $res = $this->exec($sql, $values);

            if ($this->dbtype === self::DBTYPE_POSTGRES) {
                $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
            
            return $res;

        }

        /**
         * Führt DELETE-Befehl auf DB aus
         * @param string $table
         * @param string $where
         * @param array $params
         * @return bool
         */
        public function delete($table, $where = null, array $params = []) {
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql    = "DELETE FROM $table";
            if (!is_null($where)) $sql .= " WHERE $where";

            return $this->exec($sql, $params);
        }

        /**
         * Ändert Tabellenstruktur via ALTER TABLE
         * @param string $table
         * @param string $methode
         * @param string $field
         * @param string $where
         * @param bool $checkExists Prüfung durchführen, ob Feld existiert
         * @return bool
         */
        public function alter($table, $methode, $field, $where = "", $checkExists = true) {

            if ($checkExists && $this->fetch($this->select($table, $field)) !== false) return true;
            
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql = "ALTER TABLE $table $methode $field $where";

            return $this->exec($sql);
        }

        /**
         * Zählt nach den angebenen Einstellungen
         * @param string $table In welcher Tabelle soll gezählt werden
         * @param string $countitem Welche Spalte soll gezählt werden
         * @param string $where Nach welchen Filterkriterien soll gezählt werden
         * @param array $params
         * @return boolean
         */
        public function count($table, $countitem = '*', $where = null, array $params = []) {
            $sql = "SELECT count(".$countitem.") AS counted FROM {$this->dbprefix}_{$table}";
            if (!is_null($where)) {
                $sql .= " WHERE ".$where.";";                
            }

            $result = $this->query($sql, $params);	
            if ($result === false) { $this->getError();return false; }
            $row = $this->fetch($result);

            return isset($row->counted) ? $row->counted : 0;
        }

        /**
         * Negiert den Wert des übergebenen Feldes
         * @param string|array $table
         * @param string $field
         * @param string $where
         * @return bool
         */
        public function reverseBool($table, $field, $where) {        
            $table = (is_array($table)) ? $this->dbprefix.'_'.implode(', '.$this->dbprefix.'_', $table) : $this->dbprefix."_$table";
            $sql = "UPDATE $table SET ".$this->driver->getNotQuery($field);
            if (!is_null($where)) $sql .= " WHERE $where";
            return $this->exec($sql);
        }        
        
        /**
         * Liefert höchten Wert einer Tabellen-ID
         * @param string $table Tabellen-Name
         * @return int
         */
        public function getMaxTableId($table) {
            $sql = "SELECT max(id) as maxid from {$this->dbprefix}_{$table};";
            $data = $this->fetch($this->query($sql));

            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql("MAXID from {$this->dbprefix}_{$table} is {$data->maxid}.");
            }

            return $data->maxid;
        }
        
        /**
         * Tabelle via DROP TABLE löschen
         * @param string $table
         * @return bool
         */
        public function drop($table) {            
            return $this->exec("DROP TABLE {$this->dbprefix}_{$table}");            
        }

        /**
         * Index auf eine Spalte in der übergebenen Tabelle erzeugen
         * @param string $table
         * @param string $indexName
         * @param string $field
         * @param bool $isUnique
         * @return bool
         * @since FPCM 3.3.1
         */
        public function createIndex($table, $indexName, $field, $isUnique = false) {
            
            $sql = $this->driver->createIndexString("{$this->dbprefix}_{$table}", $indexName, $field, $isUnique);
            return $this->exec($sql);

        }

        /**
         * Führt ein SQL Kommando aus
         * @param string $command SQL String
         * @param array $bindParams Paramater, welche gebunden werden sollen
         * @return bool
         */
        public function exec($command, array $bindParams = []) {
            
            $this->queryCount++;
            
            if ($this->explain) {
                $command = 'EXPLAIN '.$command;
            }
            
            $statement = $this->connection->prepare($command);     

            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql($statement->queryString);
            }

            $this->lastQueryString = $statement->queryString;

            try {
                $res = $statement->execute($bindParams);
            } catch (\PDOException $e) {
                fpcmLogSql($e);
            }            
            
            if (!$res) {
                $this->getStatementError($statement);
                return false;
            }

            return true;
        }

        /**
         * Führt SQL-Datei aus
         * @param string $path
         * @return boolean
         * @since FPCM 3.2.0
         */
        public function execSqlFile($path) {

            if (substr($path, -4) != '.sql') {
                trigger_error('Given file was not SQL file '.$path);
                return false;
            }
            
            if (!file_exists($path) || filesize($path) < 1) {
                trigger_error('File not found or file is empty in '.$path);
                return false;
            }

            $this->queryCount++;

            $sql = str_replace('{{dbpref}}', $this->getDbprefix(), file_get_contents($path));
            $this->lastQueryString = $sql;
            
            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql($sql);
            }

            try {
                $res = $this->connection->exec($sql);
            } catch (\PDOException $e) {
                fpcmLogSql($e);
            }            
            
            if ($res === false) {
                $this->getError();
                return false;
            }

            return true;
        }

        /**
         * Parst YaTDL-Datei und führt SQL-Statement aus
         * @param string $path
         * @return boolean
         * @since FPCM 3.2.0
         */
        public function execYaTdl($path) {

            if (substr($path, -4) !== '.yml') {
                $path .= '.yml';
            }
            
            $yatdl = new \fpcm\model\system\yatdl($path);
            if ($yatdl->parse() !== true) {
                trigger_error('An YaTDL parser error occurred!');
                return false;
            }
            
            $sql = str_replace('{{dbpref}}', $this->getDbprefix(), $yatdl->getSqlString());
            $this->lastQueryString = $sql;

            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql($sql);
            }

            try {
                $res = $this->connection->exec($sql);
            } catch (\PDOException $e) {
                fpcmLogSql($e);
            }            
            
            if ($res === false) {
                $this->getError();
                return false;
            }

            return true;
        }

        /**
         * Führt ein SQL Kommando aus und gibt Result-Set zurück
         * @param string $command SQL String
         * @param array $bindParams Paramater, welche gebunden werden sollen
         * @return PDOStatement Zeilen in der Datenbank
         */
        public function query($command, array $bindParams = []) {
            
            $this->queryCount++;
            
            if ($this->explain) {
                $command = 'EXPLAIN '.$command;
            }

            $statement = $this->connection->prepare($command);

            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql($statement->queryString);
            }
            
            $this->lastQueryString = $statement->queryString;
            
            try {
                $res = $statement->execute($bindParams);
            } catch (\PDOException $e) {
                fpcmLogSql($e);
            }
            
            if (!$res) {
                $this->getStatementError($statement);
            }

            return $statement;
        }

        /**
         * Schreibt letzte Fehlermeldung der DB-Verbindung in DB-Log
         * @return boolean
         */
        public function getError() {	
            fpcmLogSql(print_r($this->connection->errorInfo(), true));
            
            return true;
        }

        /**
         * Schreibt letzte Fehlermeldung des ausgefühtren Statements in DB-Log
         * @param \PDOStatement $statement
         * @return boolean
         */
        public function getStatementError(\PDOStatement &$statement) {	
            fpcmLogSql(print_r($statement->errorInfo(), true));
            
            return true;
        }

        /**
         * Liefert eine Zeile des results als Objekt zurück
         * @param PDOStatement $result Resultset
         * @param bool $getAll soll fetchAll() erzwungen werden
         * @return array
         */		
        public function fetch(\PDOStatement $result,$getAll = false) {
            if ($result->rowCount() > 1 || $getAll == true) {
                return $result->fetchAll(\PDO::FETCH_OBJ);
            }
            
            return $result->fetch(\PDO::FETCH_OBJ);
        }

        /**
         * Liefert ID des letzten Insert-Eintrags
         * @return string
         */
        public function getLastInsertId() {
            
            $this->queryCount++;
            
            $params = $this->driver->getLastInsertIdParams($this->lastTable);
            $return = $params
                    ? $this->connection->lastInsertId($params)
                    : $this->connection->lastInsertId();

            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
                fpcmLogSql("Last insert id was: $return");
            }        

            return $return;
        }

        /**
         * Liefert zuletzt ausgeführten Query-String zurück
         * @return string
         */
        public function getLastQueryString() {
            return $this->lastQueryString;
        }
        
        /**
         * Erzeugt LIMIT-SQL-String
         * @param int $offset
         * @param int $limit
         * @return string
         */
        public function limitQuery($offset, $limit) {
            return $this->driver->limitQuery($offset, $limit);
        }
        
        /**
         * Erzeugt ORDER BY-SQL-String
         * @param array $conditions
         * @return string
         */
        public function orderBy(array $conditions) {
            return $this->driver->orderBy($conditions);
        }
        
        /**
         * Erzeugt CONCAT SQL-String
         * @param array $fields
         * @return string
         * @since FPCM 3.1.0
         */
        public function concatString(array $fields) {
            return $this->driver->concatString($fields);
        }

        /**
         * Erzeugt CONCAT_WS SQL-String
         * @param string $delim
         * @param array $fields
         * @return string
         * @since FPCM 3.4
         */
        public function implodeCols($delim, array $fields) {
            return $this->driver->implodeCols($delim, $fields);
        }
        
        /**
         * Erzeugt LIKE-SQL-String
         * @return string
         * @since FPCM 3.2.0
         */
        public function dbLike() {
            return $this->driver->getDbLike();
        }
        
        /**
         * Erzeugt Query für Optimierungsvorgang auf Datenbank-Tabellen
         * @param string $table Name der Tabelle
         * @return bool
         * @since FPCM 3.3.0
         */
        public function optimize($table) {
            return $this->driver->optimize($this->dbprefix.'_'.$table);
        }

        /**
         * Tabellen-Prefix zurückgeben
         * @return string
         */
        public function getDbprefix() {
            return $this->dbprefix;
        }

        /**
         * vollen Tabellen-Name mit Prefix zurückgeben
         * @param string $table
         * @return string
         * @since FPCM 3.4
         */
        public function getTablePrefixed($table) {
            return $this->dbprefix.'_'.$table;
        }
        
        /**
         * Datenbank-Typ zurückgeben
         * @return string
         * @since FPCM 3.2
         */
        public function getDbtype() {
            return $this->dbtype;
        }
                
        /**
         * Gibt Anzahl an ausgeführt Datenbank-Queries zurück
         * @return int
         * @since FPCM 3.1.0
         */
        public function getQueryCount() {
            return $this->queryCount;
        }
        
        /**
         * Gibt Datentypen-Map zurück für YATDL
         * @return array
         * @since FPCM 3.2.0
         */
        public function getYaTDLDataTypes() {
            return $this->driver->getYaTDLDataTypes();
        }

        /**
         * Gibt Version des verbundenen Datenbank-Systems zurück
         * @return string
         * @since FPCM 3.4
         */
        public function getDbVersion() {            
            return $this->connection->getAttribute(\PDO::ATTR_SERVER_VERSION);
        }

        /**
         * Prüft, ob aktuelle Version des DBMS >= der empfohlenen Version ist
         * @return string
         * @since FPCM 3.4
         */
        public function checkDbVersion() {            
            return version_compare($this->getDbVersion(), $this->getRecommendVersion(), '>=') ? true : false;            
        }

        /**
         * Gibt Version des verbundenen Datenbank-Systems zurück
         * @return string
         * @since FPCM 3.4
         */
        public function getRecommendVersion() {            
            return $this->driver->getRecommendVersion();
        }

        /**
         * Liefert Struktur-Infos für eine Bestimmte Tabelle und ggf. Spalte zurück
         * @param string $table
         * @param string $field
         * @return array
         * @since FPCM 3.3.2
         */
        public function getTableStructure($table, $field = false) {

            $query = $this->driver->getTableStructureQuery($this->dbprefix.'_'.$table, $field);

            $result = $this->query($query);
            if ($result === false) {
                return [];
            }

            $colRows = $this->fetch($result, true);
            if (!is_array($colRows) || !count($colRows)) {
                return [];
            }

            $data = [];
            foreach ($colRows as $colRow) {
                $this->driver->prepareColRow($colRow, $data);
            }
            
            return $data;
        }

        /**
         * Vergleicht die aktuelle Struktur der Tabelle in der DB mit der Struktur der YML-Datei und 
         *  fügt ggf. fehlende Spalten zur Tabelle in der DB hinzu
         * @param string $tableFile
         * @return boolean
         * @since FPCM 3.3.2
         */
        public function checkTableStructure($tableFile) {

            $isPg    = $this->dbtype === 'pgsql' ? true : false;
            $typeMap = $this->driver->getYaTDLDataTypes();

            $yatdl = new \fpcm\model\system\yatdl(baseconfig::$dbStructPath.$tableFile.'.yml');
            
            $data  = $yatdl->getArray();            
            $table = $data['name'];

            $structure = $this->getTableStructure($table);

            $lenghtTypes = array('varchar', 'char');
            if (!$isPg) {
                $lenghtTypes[] = 'int';
                $lenghtTypes[] = 'bigint';
                $lenghtTypes[] = 'bool';
                $lenghtTypes[] = 'smallint';
                $lenghtTypes[] = 'float';
                $lenghtTypes[] = 'double';
            }

            foreach ($data['cols'] as $col => $attr) {
                
                if (isset($structure[$col])) {
                    continue;
                }

                if (!isset($typeMap[$attr['type']])) {
                    trigger_error('Undefined data type for column '.$col);
                    continue;
                }

                $type   = $typeMap[$attr['type']];
                if ($attr['length'] && in_array($attr['type'], $lenghtTypes)) {
                    $length = (int) $attr['length'];
                    $type .= "({$length})";
                }

                if ($isPg) {                    
                    $attr['params'] = str_replace('NOT NULL', '', $attr['params']);                    
                }

                $type .= trim($attr['params']) ? ' '.$attr['params'] : '';
                if (!$this->alter($table, 'ADD', $col, $type, false)) {
                    fpcmLogSql($this->lastQueryString);
                    return false;
                }

            }

            return true;

        }

        /**
         * Datei data/config/database.php erzeugen
         * @param array $data
         * @return boolean
         * @since FPCM 3.5.1
         */
        public function createDbConfigFile(array $data) {
            
            include_once \fpcm\classes\baseconfig::$configDir.'/database.php.sample';
            
            foreach ($data as $key => $value) {
                $config[$key] = $value;
            }
            
            $content    = [];
            $content[]  = '<?php';
            $content[]  = '/**';
            $content[]  = ' * FanPress CM databse connection configuration file';
            $content[]  = ' * Only edit this file, if you know what you are doing!!!';
            $content[]  = ' *';
            $content[]  = ' * DBTYPE => databse type, mysql support only so far';
            $content[]  = ' * DBHOST => mostly localhost, modify this if you use a different name';
            $content[]  = ' * DBNAME => the database to connect to';
            $content[]  = ' * DBUSER => user to connect to database';
            $content[]  = ' * DBPASS => the users password to connect to database';
            $content[]  = ' * DBPREF => table prefix';
            $content[]  = ' *';
            $content[]  = ' */';
            $content[]  = '$config = '.var_export($config, true).';';
            $content[]  = '?>';
            file_put_contents(\fpcm\classes\baseconfig::$configDir.'/database.php', implode(PHP_EOL, $content));
            
            return true;
        }

        /**
         * Error die
         */
        private function dieError() {
            die('Connection to database failed!');
        }

        /**
         * Liefert YMl-Dateien aus Pfad zurück
         * @param string $path
         * @return array
         * @since FPCM 3.3.2
         */
        public static function getTableFiles($path = false) {
            
            if (!$path) {
                $path = \fpcm\classes\baseconfig::$dbStructPath;
            }
            
            if (!is_dir($path)) {
                trigger_error('Invalid path given, '.$path.' is not a directory');
                return [];
            }

            $files = glob($path.'*.yml');
            if (!is_array($files) || !count($files)) {
                return [];
            }

            return $files;

        }

        /**
         * SQL-EXPLAIN de/aktivieren
         * @param bool $explain
         * @since FPCM 3.6
         */
        public function setExplain($explain) {
            $this->explain = (bool) $explain;
        }

    }
