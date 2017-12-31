<?php
    /**
     * FanPress CM Database dump cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\crons;
    
    /**
     * FanPress CM Database dump cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\crons
     * @author Stefan Seehafer <sea75300@yahoo.de>
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
        public function run() {

            if (\fpcm\classes\baseconfig::$fpcmDatabase->getDbtype() == \fpcm\classes\database::DBTYPE_POSTGRES) {
                $this->updateLastExecTime();
                return true;
            }
            
            include_once \fpcm\classes\loader::libGetFilePath('Ifsnop/Mysqldump', 'Mysqldump.php');
            
            fpcmLogCron('Check database config...');
            
            $dbconfig = \fpcm\classes\baseconfig::getDatabaseConfig();
            
            $dumpSettings = [];
            
            $this->dumpfile = \fpcm\classes\baseconfig::$dbdumpDir.$dbconfig['DBNAME'].'_'.date('Y-m-d_H-i-s').'.sql';
            if (function_exists('gzopen')) {
                $dumpSettings['compress'] = \Ifsnop\Mysqldump\Mysqldump::GZIP;
                $this->dumpfile .= '.gz';
            }

            $dumpSettings['single-transaction']   = false;
            $dumpSettings['lock-tables']          = false;
            $dumpSettings['add-locks']            = false;
            $dumpSettings['extended-insert']      = false;
            $dumpSettings['no-autocommit']        = false;
            
            fpcmLogCron('Fetch database tables for backup...');
            
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableArticles;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableAuthors;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableCategories;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableComments;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableConfig;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableCronjobs;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableFiles;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableIpAdresses;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableModules;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tablePermissions;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableRoll;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableSessions;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableSmileys;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableTexts;
            $dumpSettings['include-tables'][] = $dbconfig['DBPREF'].'_'.\fpcm\classes\database::tableRevisions;
            
            $dumpSettings['include-tables']   = $this->events->runEvent('cronjobDbDumpIncludeTables', $dumpSettings['include-tables']);

            fpcmLogCron('Create new database dump in "'.\fpcm\model\files\ops::removeBaseDir($this->dumpfile, true).'"...');
            
            $mysqlDump = new \Ifsnop\Mysqldump\Mysqldump($dbconfig['DBNAME'],
                                                         $dbconfig['DBUSER'],
                                                         $dbconfig['DBPASS'],
                                                         $dbconfig['DBHOST'],
                                                         $dbconfig['DBTYPE'],
                                                         $dumpSettings);
            $mysqlDump->start($this->dumpfile);
            
            $this->updateLastExecTime();
            if (!file_exists($this->dumpfile)) {
                fpcmLogCron('Unable to create database dump in "'.\fpcm\model\files\ops::removeBaseDir($this->dumpfile, true).'", file not found. See system check and error log!');
                return false;
            }
            
            fpcmLogCron('New database dump created in "'.\fpcm\model\files\ops::removeBaseDir($this->dumpfile, true).'".');            

            $text  = \fpcm\classes\baseconfig::$fpcmLanguage->translate('CRONJOB_DBBACKUPS_TEXT', array(
                '{{filetime}}' => date(\fpcm\classes\baseconfig::$fpcmConfig->system_dtmask, $this->getLastExecTime()),
                '{{dumpfile}}' => \fpcm\model\files\ops::removeBaseDir($this->dumpfile)
            ));

            fpcmLogCron('Create email notification for new databse backup');
            
            $email = new \fpcm\classes\email(
                \fpcm\classes\baseconfig::$fpcmConfig->system_email,
                \fpcm\classes\baseconfig::$fpcmLanguage->translate('CRONJOB_DBBACKUPS_SUBJECT'),
                $text
            );

            if (filesize($this->dumpfile) <= \fpcm\classes\baseconfig::memoryLimit(true) / 8) {                
                $email->setAttachments([
                    $this->dumpfile
                ]);
            }
            

            $email->submit();
            
            return true;
        }
    }
