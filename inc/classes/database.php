<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FanPress CM Database abstraction layer
 * 
 * @package fpcm\classes\database
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 */
final class database {

    /**
     * Article-Tabelle
     */
    const tableArticles = 'articles';

    /**
     * Benutzer-Tabelle
     */
    const tableAuthors = 'authors';

    /**
     * Kategorie-Tabelle
     */
    const tableCategories = 'categories';

    /**
     * Kommentar-Tabelle
     */
    const tableComments = 'comments';

    /**
     * Config-Tabelle
     */
    const tableConfig = 'config';

    /**
     * Cronjob-Tabelle
     */
    const tableCronjobs = 'cronjobs';

    /**
     * Dateiindex-Tabelle
     */
    const tableFiles = 'uploadfiles';

    /**
     * Tabeller gesperrter IP-Adressen
     */
    const tableIpAdresses = 'blockedip';

    /**
     * Modul-Tabelle
     */
    const tableModules = 'modules';

    /**
     * Tabelle für Berechtigungen
     */
    const tablePermissions = 'permissions';

    /**
     * Benutzerrollen-Tabelle
     */
    const tableRoll = 'userrolls';

    /**
     * Sessions-Tabelle
     */
    const tableSessions = 'sessions';

    /**
     * Share-Count-Tabelle
     */
    const tableShares = 'shares';

    /**
     * Smiley-Tabelle
     */
    const tableSmileys = 'smileys';

    /**
     * Wordsperre-Tabelle
     * @since FPCM 3.2
     */
    const tableTexts = 'texts';

    /**
     * Tabelle für revisionen
     * @since FPCM 3.3
     */
    const tableRevisions = 'revisions';

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
     * Table structure cache
     * @var array
     */
    private $structCache = [];

    /**
     * Konstruktor
     * @param array $dbconfig alternative Datenbank-Zugangsdaten, wenn false werden Daten aus FPCM-Config genutzt
     * @param bool $dieOnError wenn Verbindung fehlschlägt, soll Ausführung vollständig abgebrochen werden
     * @return void
     */
    public function __construct($dbconfig = false, $dieOnError = true)
    {
        $dbconfig = (is_array($dbconfig) ? $dbconfig : baseconfig::getDatabaseConfig());

        if (!isset($dbconfig['DBTYPE'])) {
            return;
        }

        $driverClass = '\\fpcm\\drivers\\' . $dbconfig['DBTYPE'];

        if (!class_exists($driverClass)) {
            fpcmLogSql('SQL driver not found for ' . $dbconfig['DBTYPE']);
            $this->dieError();
        }

        $this->driver = new $driverClass();
        if (!is_a($this->driver, '\\fpcm\\drivers\\sqlDriver')) {
            fpcmLogSql('SQL driver ' . $driverClass . ' must be an instance of "\\fpcm\\drivers\\sqlDriver"!');
            $this->dieError();
        }

        try {
            $this->connection = new \PDO($dbconfig['DBTYPE'] . ':' . $this->driver->getPdoDns($dbconfig), $dbconfig['DBUSER'], $dbconfig['DBPASS'], $this->driver->getPdoOptions());
        } catch (\PDOException $e) {
            fpcmLogSql($e->getMessage());
            if (!$dieOnError) {
                return;
            }
            $this->dieError();
        }

        $this->dbprefix = $dbconfig['DBPREF'];
        $this->dbtype = $dbconfig['DBTYPE'];
    }

    /**
     * Der Destruktor
     */
    public function __destruct()
    {
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
    public function select($table, $item = '*', $where = null, array $params = [], $distinct = false)
    {
        $sql = $distinct ? "SELECT DISTINCT $item FROM {$this->getTablePrefixed($table)}" : "SELECT $item FROM {$this->getTablePrefixed($table)}";
        if (!is_null($where)) {
            $sql .= " WHERE $where";
        }

        return $this->query($sql, $params);
    }

    /**
     * Executes select and fetch in one function
     * @param \fpcm\model\dbal\selectParams $obj
     * @return \PDOStatement|array
     */
    public function selectFetch(\fpcm\model\dbal\selectParams $obj)
    {
        $sql = $obj->getDistinct() ? 'SELECT DISTINCT' : 'SELECT';
        $sql .= " {$obj->getItem()} FROM {$this->getTablePrefixed($obj->getTable())}";
        $sql .= $obj->getWhere() ? " WHERE {$obj->getWhere()}" : "";

        $result = $this->query($sql, $obj->getParams());
        if ($obj->getReturnResult()) {
            return $result;
        }

        return $this->fetch($result, $obj->getFetchAll(), $obj->getFetchStyle());
    }

    /**
     * Execute insert query
     * @param string $table
     * @param string $values
     * @return int
     */
    public function insert($table, $values)
    {
        $fields = implode(', ', array_keys($values));
        $vars = implode(', ', array_fill(0, (int) count($values), '?'));

        $table = $this->getTablePrefixed($table);
        $this->lastTable = $table;

        if ($this->exec("INSERT INTO {$table} ({$fields}) VALUES ({$vars});", array_values($values)) === false) {
            return false;
        }

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
    public function update($table, array $fields, array $params = [], $where = null)
    {
        $sql = "UPDATE {$this->getTablePrefixed($table)} SET " . implode(' = ?, ', $fields) . ' = ?';
        if (!is_null($where)) {
            $sql .= " WHERE $where";
        }

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
    public function updateMultiple($table, array $fields, array $params = [], array $where = [])
    {
        if ($this->dbtype === self::DBTYPE_POSTGRES) {
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        }

        $sql = '';
        $values = [];

        $updateStr = 'UPDATE ' . $this->getTablePrefixed($table) . ' SET ' . implode(' = ?, ', $fields) . ' = ?';

        foreach ($params as $i => $row) {

            $sql .= $updateStr;

            if (isset($where[$i])) {
                $sql .= " WHERE {$where[$i]}";
            }

            $values = array_merge($values, $row);
            $sql .= ';' . PHP_EOL;
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
    public function delete($table, $where = null, array $params = [])
    {
        $sql = "DELETE FROM {$this->getTablePrefixed($table)}";
        if (!is_null($where))
            $sql .= " WHERE $where";

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
    public function alter($table, $methode, $field, $where = "", $checkExists = true)
    {
        $struct = $this->getTableStructure($table);
        if ($checkExists && !isset($struct[$field])) {
            return true;
        }

        return $this->exec("ALTER TABLE {$this->getTablePrefixed($table)} {$methode} {$field} {$where}");
    }

    /**
     * Zählt nach den angebenen Einstellungen
     * @param string $table In welcher Tabelle soll gezählt werden
     * @param string $countitem Welche Spalte soll gezählt werden
     * @param string $where Nach welchen Filterkriterien soll gezählt werden
     * @param array $params
     * @return bool
     */
    public function count($table, $countitem = '*', $where = null, array $params = [])
    {
        $sql = "SELECT count(" . $countitem . ") AS counted FROM {$this->getTablePrefixed($table)}";
        if (!is_null($where)) {
            $sql .= " WHERE " . $where . ";";
        }

        $result = $this->query($sql, $params);
        if ($result === false) {
            $this->getError();
            return false;
        }
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
    public function reverseBool($table, $field, $where)
    {
        $sql = "UPDATE {$this->getTablePrefixed($table)} SET " . $this->driver->getNotQuery($field);
        if (!is_null($where)) {
            $sql .= " WHERE $where";
        }
        return $this->exec($sql);
    }

    /**
     * Liefert höchten Wert einer Tabellen-ID
     * @param string $table Tabellen-Name
     * @return int
     */
    public function getMaxTableId($table)
    {
        $sql = "SELECT max(id) as maxid from {$this->getTablePrefixed($table)};";
        $data = $this->fetch($this->query($sql));

        if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
            fpcmLogSql("MAXID from {$this->getTablePrefixed($table)} is {$data->maxid}.");
        }

        return $data->maxid;
    }

    /**
     * Tabelle via DROP TABLE löschen
     * @param string $table
     * @return bool
     */
    public function drop($table)
    {
        return $this->exec("DROP TABLE {$this->getTablePrefixed($table)}");
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
    public function createIndex($table, $indexName, $field, $isUnique = false)
    {
        $sql = $this->driver->createIndexString($this->getTablePrefixed($table), $indexName, $field, $isUnique);
        return $this->exec($sql);
    }

    /**
     * Führt ein SQL Kommando aus
     * @param string $command SQL String
     * @param array $bindParams Paramater, welche gebunden werden sollen
     * @return bool
     */
    public function exec($command, array $bindParams = [])
    {
        if (!trim($command)) {
            trigger_error('Invalid SQL command detected, query was empty!');
            return false;
        }

        $this->queryCount++;

        if ($this->explain) {
            $command = 'EXPLAIN ' . $command;
        }

        $statement = $this->connection->prepare($command);
        if (!trim($statement->queryString)) {
            trigger_error('Invalid SQL command detected, query was empty!');
            return false;
        }

        if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
            fpcmLogSql($statement->queryString);
        }

        $this->lastQueryString = $statement->queryString;

        try {
            $res = $statement->execute($bindParams);
        } catch (\PDOException $e) {
            fpcmLogSql((string) $e);
            return false;
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
     * @return bool
     * @since FPCM 3.2.0
     */
    public function execSqlFile($path)
    {
        if (substr($path, -4) != '.sql') {
            trigger_error('Given file was not SQL file ' . $path);
            return false;
        }

        if (!file_exists($path) || filesize($path) < 1) {
            trigger_error('File not found or file is empty in ' . $path);
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
            fpcmLogSql((string) $e);
            return false;
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
     * @return bool
     * @since FPCM 3.2.0
     */
    public function execYaTdl($path)
    {
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
            fpcmLogSql((string) $e);
            return false;
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
    public function query($command, array $bindParams = [])
    {
        if (!trim($command)) {
            trigger_error('Invalid SQL command detected, query was empty!');
            return false;
        }

        $this->queryCount++;

        if ($this->explain) {
            $command = 'EXPLAIN ' . $command;
        }

        $statement = $this->connection->prepare($command);
        if (!trim($statement->queryString)) {
            trigger_error('Invalid SQL command detected, query was empty!');
            return false;
        }

        if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
            fpcmLogSql($statement->queryString);
        }

        $this->lastQueryString = $statement->queryString;

        try {
            $res = $statement->execute($bindParams);
        } catch (\PDOException $e) {
            fpcmLogSql((string) $e);
            return false;
        }

        if (!$res) {
            $this->getStatementError($statement);
        }

        return $statement;
    }

    /**
     * Schreibt letzte Fehlermeldung der DB-Verbindung in DB-Log
     * @return bool
     */
    public function getError()
    {
        fpcmLogSql(print_r($this->connection->errorInfo(), true));
        return true;
    }

    /**
     * Schreibt letzte Fehlermeldung des ausgefühtren Statements in DB-Log
     * @param \PDOStatement $statement
     * @return bool
     */
    public function getStatementError(\PDOStatement &$statement)
    {
        $info = $statement->errorInfo();

        $err = 'ERROR MESSAGE: ' . $info[2] . PHP_EOL;
        $err .= 'SQL STATE: ' . $info[0] . PHP_EOL;
        $err .= $this->getDbtype() . ' ERROR CODE: ' . $info[1] . PHP_EOL;
        $err .= 'Query: ' . $statement->queryString . PHP_EOL;

        fpcmLogSql($err);
        return true;
    }
    
    /**
     * Returns result set from database query
     * @param \PDOStatement $result
     * @param bool $getAll
     * @param int $style
     * @return mixed
     */
    public function fetch(\PDOStatement $result, $getAll = false, $style = \PDO::FETCH_OBJ)
    {
        if ($result->rowCount() > 1 || $getAll == true) {
            return $result->fetchAll($style);
        }

        return $result->fetch($style);
    }

    /**
     * Liefert ID des letzten Insert-Eintrags
     * @return string
     */
    public function getLastInsertId()
    {
        $this->queryCount++;

        $params = $this->driver->getLastInsertIdParams($this->lastTable);
        $return = $params ? $this->connection->lastInsertId($params) : $this->connection->lastInsertId();

        if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_SQL') && FPCM_DEBUG_SQL) {
            fpcmLogSql("Last insert id was: $return");
        }

        return $return;
    }

    /**
     * Liefert zuletzt ausgeführten Query-String zurück
     * @return string
     */
    public function getLastQueryString()
    {
        return $this->lastQueryString;
    }

    /**
     * Erzeugt LIMIT-SQL-String
     * @param int $offset
     * @param int $limit
     * @return string
     */
    public function limitQuery($offset, $limit)
    {
        return $this->driver->limitQuery($offset, $limit);
    }

    /**
     * Erzeugt ORDER BY-SQL-String
     * @param array $conditions
     * @return string
     */
    public function orderBy(array $conditions)
    {
        return $this->driver->orderBy($conditions);
    }

    /**
     * Erzeugt CONCAT SQL-String
     * @param array $fields
     * @return string
     * @since FPCM 3.1.0
     */
    public function concatString(array $fields)
    {
        return $this->driver->concatString($fields);
    }

    /**
     * Erzeugt CONCAT_WS SQL-String
     * @param string $delim
     * @param array $fields
     * @return string
     * @since FPCM 3.4
     */
    public function implodeCols($delim, array $fields)
    {
        return $this->driver->implodeCols($delim, $fields);
    }

    /**
     * Creates IN-Query for prepared statement
     * @param string $field
     * @param array $values
     * @param bool $notId
     * @return string
     * @since FPCm 4.2.1
     */
    public function inQuery(string $field, array $values, bool $notId = false) : string
    {
        return $field.($notId ? ' NOT ' : '').' IN ('. implode(', ', array_fill(0, count($values), '?')).')';
    }

    /**
     * Erzeugt LIKE-SQL-String
     * @return string
     * @since FPCM 3.2.0
     */
    public function dbLike()
    {
        return $this->driver->getDbLike();
    }

    /**
     * Erzeugt Query für Optimierungsvorgang auf Datenbank-Tabellen
     * @param string $table Name der Tabelle
     * @return bool
     * @since FPCM 3.3.0
     */
    public function optimize($table)
    {
        return $this->driver->optimize($this->getTablePrefixed($table));
    }

    /**
     * Tabellen-Prefix zurückgeben
     * @return string
     */
    public function getDbprefix()
    {
        return $this->dbprefix;
    }

    /**
     * Kompletten Tabellen-Name mit Prefix zurückgeben
     * @param string $table
     * @return string
     * @since FPCM 3.4
     */
    public function getTablePrefixed($table)
    {
        if (is_array($table)) {
            return $this->dbprefix . '_' . implode(', ' . $this->dbprefix . '_', $table);
        }

        return $this->dbprefix . '_' . $table;
    }

    /**
     * Datenbank-Typ zurückgeben
     * @return string
     * @since FPCM 3.2
     */
    public function getDbtype()
    {
        return $this->dbtype;
    }

    /**
     * Gibt Anzahl an ausgeführt Datenbank-Queries zurück
     * @return int
     * @since FPCM 3.1.0
     */
    public function getQueryCount()
    {
        return $this->queryCount;
    }

    /**
     * Gibt Datentypen-Map zurück für YATDL
     * @return array
     * @since FPCM 3.2.0
     */
    public function getYaTDLDataTypes()
    {
        return $this->driver->getYaTDLDataTypes();
    }

    /**
     * Gibt Version des verbundenen Datenbank-Systems zurück
     * @return string
     * @since FPCM 3.4
     */
    public function getDbVersion()
    {
        return $this->connection->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    /**
     * Prüft, ob aktuelle Version des DBMS >= der empfohlenen Version ist
     * @return string
     * @since FPCM 3.4
     */
    public function checkDbVersion()
    {
        return version_compare($this->getDbVersion(), $this->getRecommendVersion(), '>=') ? true : false;
    }

    /**
     * Gibt Version des verbundenen Datenbank-Systems zurück
     * @return string
     * @since FPCM 3.4
     */
    public function getRecommendVersion()
    {
        return $this->driver->getRecommendVersion();
    }

    /**
     * Returns database structure for certain table and/or column
     * @param string $table
     * @param string $field
     * @param string $cache
     * @return array
     * @since FPCM 3.3.2
     */
    public function getTableStructure($table, $field = false, $cache = true)
    {
        $cacheName = $table . '_struct';
        if ($cache && isset($this->structCache[$cacheName])) {
            return $this->structCache[$cacheName];
        }

        $query = $this->driver->getTableStructureQuery($this->getTablePrefixed($table), $field);

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

        $this->structCache[$cacheName] = $data;
        return $data;
    }

    /**
     * Add columns to database table by definition in object of type @see \fpcm\model\system\yatdl 
     * @param \fpcm\model\system\yatdl $yatdl
     * @return bool
     */
    public function addTableCols(\fpcm\model\system\yatdl $yatdl)
    {
        $isPg = $this->dbtype === self::DBTYPE_POSTGRES ? true : false;
        $typeMap = $this->driver->getYaTDLDataTypes();

        $data = $yatdl->getArray();
        $table = $data['name'];

        $structure = $this->getTableStructure($table);
        $lenghtTypes = $this->getLenghtTypes();

        foreach ($data['cols'] as $col => $attr) {

            if (isset($structure[$col])) {
                continue;
            }

            if (!isset($typeMap[$attr['type']])) {
                trigger_error('Undefined data type for column ' . $col);
                continue;
            }

            $type = $typeMap[$attr['type']];
            if ($attr['length'] && in_array($attr['type'], $lenghtTypes)) {
                $length = (int) $attr['length'];
                $type .= "({$length})";
            }

            if ($isPg) {
                $attr['params'] = str_replace('NOT NULL', '', $attr['params']);
            }

            $type .= trim($attr['params']) ? ' ' . $attr['params'] : '';
            if (!$this->alter($table, 'ADD', $col, $type, false)) {
                fpcmLogSql($this->lastQueryString);
                return false;
            }
        }

        return true;
    }

    /**
     * Removes columns to database table by definition in object of type @see \fpcm\model\system\yatdl 
     * @param \fpcm\model\system\yatdl $yatdl
     * @return bool
     */
    public function removeTableCols(\fpcm\model\system\yatdl $yatdl)
    {
        $data = $yatdl->getArray();
        $table = $data['name'];

        $structure = $this->getTableStructure($table);
        foreach ($structure as $col => $attr) {

            if (isset($data['cols'][$col]) || $col === 'id') {
                continue;
            }

            if (!$this->alter($table, 'DROP', $col, '', true)) {
                fpcmLogSql($this->lastQueryString);
                return false;
            }
        }

        return true;
    }

    /**
     * Add columns to database table by definition in object of type @see \fpcm\model\system\yatdl 
     * @param \fpcm\model\system\yatdl $yatdl
     * @return bool
     * @since FPCm 4.1
     */
    public function addTableIndices(\fpcm\model\system\yatdl $yatdl)
    {
        $data = $yatdl->getArray();
        $table = $data['name'];

        fpcmLogSql("Check indices for table {$table}...");
        if (!is_array($data['indices']) || !count($data['indices'])) {
            return true;
        }

        $existingIndices = $this->getTableIndices($table);
        if (!is_array($existingIndices) || !count($existingIndices)) {
            return true;
        }

        foreach ($data['indices'] as $idxName => $idxValue) {

            $fullIdxName = $this->getTablePrefixed($table) . '_' . $idxName;
            $fullIdxName64 = substr($fullIdxName, 0, 63);

            if (array_key_exists($fullIdxName, $existingIndices) || array_key_exists($fullIdxName64, $existingIndices)) {
                continue;
            }

            fpcmLogSql("Create index {$fullIdxName} on table {$table}...");
            if (!$this->createIndex($table, $idxName, $idxValue['col'], $idxValue['isUnqiue'])) {
                trigger_error("Unable to create index {$fullIdxName} on table {$table}, see database log for further information.");
                return false;
            }
        }

        return true;
    }

    /**
     * Datei data/config/database.php erzeugen
     * @param array $data
     * @return bool
     * @since FPCM 3.5.1
     */
    public function createDbConfigFile(array $data)
    {

        include_once dirs::getDataDirPath(dirs::DATA_CONFIG, 'database.php.sample');

        foreach ($data as $key => $value) {
            $config[$key] = $value;
        }

        $content = [];
        $content[] = '<?php';
        $content[] = '/**';
        $content[] = ' * FanPress CM Database connection configuration file';
        $content[] = ' * Only edit this file, if you know what you are doing!!!';
        $content[] = ' *';
        $content[] = ' * DBTYPE => Database type, mysql support only so far';
        $content[] = ' * DBHOST => mostly localhost, modify this if you use a different name';
        $content[] = ' * DBNAME => the database to connect to';
        $content[] = ' * DBUSER => user to connect to database';
        $content[] = ' * DBPASS => the users password to connect to database';
        $content[] = ' * DBPREF => table prefix';
        $content[] = ' *';
        $content[] = ' */';
        $content[] = '$config = ' . var_export($config, true) . ';';
        $content[] = '?>';
        file_put_contents(dirs::getDataDirPath(dirs::DATA_CONFIG, 'database.php'), implode(PHP_EOL, $content));

        return true;
    }

    /**
     * Error die
     */
    private function dieError()
    {
        exit('Connection to database failed!');
    }

    /**
     * Liefert YMl-Dateien aus Pfad zurück
     * @param string $path
     * @return array
     * @since FPCM 3.3.2
     */
    public static function getTableFiles($path = false)
    {
        if (!$path) {
            $path = dirs::getDataDirPath(dirs::DATA_DBSTRUCT, '/');
        }

        if (!is_dir($path)) {
            trigger_error('Invalid path given, ' . $path . ' is not a directory');
            return [];
        }

        $files = glob($path . '*.yml');
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
    public function setExplain($explain)
    {
        $this->explain = (bool) $explain;
    }

    /**
     * Returns PDO DNS string
     * @return string
     */
    public function getPdoDns()
    {
        $dbconfig = baseconfig::getDatabaseConfig();
        return $dbconfig['DBTYPE'] . ':' . $this->driver->getPdoDns($dbconfig);
    }

    /**
     * Returns PDO options array
     * @return string
     */
    public function getPdoOptions()
    {
        return $this->driver->getPdoOptions();
    }

    /**
     * Returns indices defined for the certain table and or column
     * @param string $table
     * @param string $field
     * @param bool $cache
     * @return array
     * @since FPCm 4.1
     */
    public function getTableIndices(string $table, $field = false, $cache = true) : array
    {
        $cacheName = $table . '_indices';
        if ($cache && isset($this->structCache[$cacheName])) {
            return $this->structCache[$cacheName];
        }

        $query = $this->driver->getTableIndexQuery($this->getTablePrefixed($table), $field);

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
            $this->driver->prepareIndexRow($this->getTablePrefixed($table), $colRow, $data);
        }

        $this->structCache[$cacheName] = $data;
        return $data;
    }

    /**
     * Return data types with length params
     * @return array
     */
    private function getLenghtTypes()
    {

        $lenghtTypes = ['varchar', 'char'];
        if ($this->dbtype === self::DBTYPE_POSTGRES) {
            return $lenghtTypes;
        };

        $lenghtTypes[] = 'int';
        $lenghtTypes[] = 'bigint';
        $lenghtTypes[] = 'bool';
        $lenghtTypes[] = 'smallint';
        $lenghtTypes[] = 'float';
        $lenghtTypes[] = 'double';

        return $lenghtTypes;
    }

}
