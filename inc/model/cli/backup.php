<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli backup module
 *
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.3-dev
 */
final class backup extends \fpcm\model\abstracts\cli {

    /**
     * Database object
     * @var \fpcm\classes\database
     */
    private \fpcm\classes\database $db;

    /**
     * Database config
     * @var array
     */
    private array $dbConfig = [];

    /**
     * Database dump file
     * @var string
     */
    protected string $dumpfile;

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {

        $fn = match ($this->funcParams[0]) {
            self::PARAM_BACKUP_DATABASE => 'backupDb',
            default => null
        };


        if (!$fn) {
            $this->output('Invalid params', true);
        }

        $this->db = new \fpcm\classes\database();
        $type = ucfirst($this->db->getDbtype());

        $this->dbConfig = \fpcm\classes\baseconfig::getDatabaseConfig();
        $this->dumpfile = \fpcm\model\crons\dbBackup::getDumpFileName($this->dbConfig['DBNAME']).'.gz';

        $fn .= $type;

        if (!method_exists($this, $fn)) {
            $this->output('Invalid params', true);
        }

        $this->{$fn}();
    }

    /**
     * 
     * @return void
     */
    private function backupDbMysql() : void
    {
        $sqlBin = exec('which mysqldump');
        $gzipBin = exec('which gzip');
        if (!$sqlBin || !$gzipBin) {
            $this->output('Unable to retrieve mysqldump or gzip binary file', true);
        }

        $cmd = sprintf('%s %s -nt -h %s -u %s --password=%s | %s > %s',
            $sqlBin,
            $this->dbConfig['DBNAME'],
            $this->dbConfig['DBHOST'],
            $this->dbConfig['DBUSER'],
            $this->dbConfig['DBPASS'],
            $gzipBin,
            $this->dumpfile
        );

        system($cmd);
        
        $this->output(sprintf('Backup created in %s', $this->dumpfile));
    }

    /**
     * 
     * @return void
     */
    private function backupDbPgsql() : void
    {
        $sqlBin = exec('which pg_dump');
        //$gzipBin = exec('which gzip');
        if (!$sqlBin) {
            $this->output('Unable to retrieve pg_dump or gzip binary file', true);
        }

        $cmd = sprintf('%s "-d %s -h %s -U %s -Z 9 -f %s -W %s"',
            $sqlBin,
            $this->dbConfig['DBNAME'],
            $this->dbConfig['DBHOST'],
            $this->dbConfig['DBUSER'],
            $this->dumpfile,
            $this->dbConfig['DBPASS']
        );

        $out = system($cmd);
        $this->output($out);
        
        $this->output(sprintf('Backup created in %s', $this->dumpfile));
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {

        $lines = [];
        $lines[] = '> Cache:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php backup <action params> <internal cache name> <internal cache module>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --database       database backup';
        return $lines;
    }

}
