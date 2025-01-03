<?php
/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * FanPress CM database dump cronjub
 *
 * @package fpcm\model\crons
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class dbBackup extends \fpcm\model\abstracts\cron {

    /**
     * Backup-Pfad-Datei
     * @var string
     */
    protected $dumpfile;

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {

        if (\fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() == \fpcm\classes\database::DBTYPE_POSTGRES) {
            $this->updateLastExecTime();
            return true;
        }

        include_once \fpcm\classes\loader::libGetFilePath('Ifsnop/Mysqldump/Mysqldump.php');

        fpcmLogCron('Check database config...');

        $dbconfig = \fpcm\classes\baseconfig::getDatabaseConfig();

        $dumpSettings = [];

        $this->dumpfile = self::getDumpFileName($dbconfig['DBNAME']);

        if (function_exists('gzopen')) {
            $dumpSettings['compress'] = \Ifsnop\Mysqldump\Mysqldump::GZIP;
            $this->dumpfile .= '.gz';
        }

        $dumpSettings['single-transaction'] = false;
        $dumpSettings['lock-tables'] = false;
        $dumpSettings['add-locks'] = false;
        $dumpSettings['extended-insert'] = false;
        $dumpSettings['no-autocommit'] = false;
        $dumpSettings['default-character-set'] = \Ifsnop\Mysqldump\Mysqldump::UTF8MB4;

        fpcmLogCron('Fetch database tables for backup...');

        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableArticles);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableAuthors);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableCategories);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableComments);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableConfig);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableCronjobs);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableFiles);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableIpAdresses);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableModules);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tablePermissions);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableSessions);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableSmileys);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableTexts);
        $dumpSettings['include-tables'][] = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRevisions);
        $dumpSettings['include-tables'] = $this->events->trigger('cron\includeDumpTables', $dumpSettings['include-tables'])->getData();

        fpcmLogCron('Create new database dump in "' . \fpcm\model\files\ops::removeBaseDir($this->dumpfile, true) . '"...');

        try {

            $mysqlDump = new \Ifsnop\Mysqldump\Mysqldump(
                $this->dbcon->getPdoDns(),
                $dbconfig['DBUSER'],
                $dbconfig['DBPASS'],
                $dumpSettings
            );

            $mysqlDump->start($this->dumpfile);
            $this->updateLastExecTime();

        } catch (\Exception $exc) {
            trigger_error('Error while databse dump: '.$exc->getMessage());
            return false;
        }

        if (!file_exists($this->dumpfile)) {
            fpcmLogCron('Unable to create database dump in "' . \fpcm\model\files\ops::removeBaseDir($this->dumpfile, true) . '", file not found. See system check and error log!');
            return false;
        }

        fpcmLogCron('New database dump created in "' . \fpcm\model\files\ops::removeBaseDir($this->dumpfile, true) . '".');
        return $this->doMail();
    }

    /**
     * Submit e-mail width databse dump file
     * @return bool
     * @since 4.5.3
     */
    private function doMail() : bool
    {
        fpcmLogCron('Create email notification for new Database backup');

        $email = new \fpcm\classes\email(
            \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_email,
            \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate('CRONJOB_DBBACKUPS_SUBJECT'),
            \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate('CRONJOB_DBBACKUPS_TEXT', array(
                '{{filetime}}' => date(\fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_dtmask, $this->getLastExecTime()),
                '{{dumpfile}}' => \fpcm\model\files\ops::removeBaseDir($this->dumpfile)
            ))
        );

        if (defined('FPCM_CRON_DBDUMP_NOMAIL') && FPCM_CRON_DBDUMP_NOMAIL) {
            $email->submit();
            return true;
        }

        if (filesize($this->dumpfile) <= \fpcm\classes\baseconfig::memoryLimit(true) / 8) {
            $email->setAttachments([
                $this->dumpfile
            ]);
        }

        $email->submit();
        return true;
    }

    public static function getDumpFileName(string $dbName) : string
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBDUMP, $dbName . '_' . date('Y-m-d_H-i-s') . '.sql');
    }

}
