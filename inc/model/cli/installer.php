<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli installer module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class installer extends \fpcm\model\abstracts\cli {
        
        use \fpcm\controller\traits\system\syscheck, 
            \fpcm\controller\traits\common\timezone;

        /**
         * Installer Konfiguration aus YML-Datei
         * @var array
         */
        protected $conf = [];

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            $this->output('FanPress CM cli installer (BETA)');
            
            $this->checkPreconditions();
            $this->loadConfig();
            $this->runSystemCheck();
            $this->initDatabase();
            $this->createTables();
            $this->initSystemConfig();
            $this->createUserAccount();
            $this->cleanupSystem();
            
            $this->output('FanPress CM has been installed successfully.');

        }

        /**
         * Prüfung, ob Installer aktiv ist
         * @return boolean
         */
        private function checkPreconditions() {

            if (!\fpcm\classes\baseconfig::installerEnabled()) {
                $this->output('Installer is not enabled. Run fpcmcli.php config --enable installer to enable installer.', true);
            }

            if (\fpcm\classes\baseconfig::dbConfigExists()) {
                $this->output('An database config file already exists. Installer cannot be executed.', true);
            }
            
            usleep(250000);

            return true;

        }

        /**
         * Installer YML Konfiguration auslesen
         * @return boolean
         */
        private function loadConfig() {
            
            $this->output(PHP_EOL.'Load installer config file...'.PHP_EOL);

            $configFile = \fpcm\classes\baseconfig::$configDir.'installer.yml';
            if (!file_exists($configFile)) {
                $this->output('No configuration file found in '.\fpcm\model\files\ops::removeBaseDir(\fpcm\classes\baseconfig::$configDir, true), true);
            }

            include_once \fpcm\classes\loader::libGetFilePath('spyc', 'Spyc.php');
            $this->conf = \Spyc::YAMLLoad($configFile);

            $this->lang = new \fpcm\classes\language($this->conf['config']['language']);
            usleep(250000);
            
            return true;
        }

        /**
         * System Check ausführen
         * @return boolean
         */
        private function runSystemCheck() {

            $this->output(PHP_EOL.'Executing system check...'.PHP_EOL);

            $rows     = $this->getCheckOptionsSystem();

            $checkFailed = false;
            
            $lines = [];
            foreach ($rows as $descr => $data) {

                print '.';
                
                $line = array(
                    '> '.strip_tags($descr),
                    '   current value     : '.(string) $data['current'],
                    '   recommended value : '.(string) $data['recommend'],
                    '   result            : '.($data['result'] ? 'OK' : '!!'),
                    '   optional          : '.($data['optional'] ? 'yes' : 'no'),
                    isset($data['notice']) && trim($data['notice']) ? ' '.$data['notice'].PHP_EOL : ''
                );
                
                $lines[] = implode(PHP_EOL, $line);

                usleep(50000);
                if ($data['optional'] || $data['result']) continue;
                $checkFailed = true;
            }

            $this->output(PHP_EOL.PHP_EOL.'System check executed, results are:'.PHP_EOL);
            usleep(250000);

            $this->output($lines);
            
            if ($checkFailed) {
                $this->output(PHP_EOL.'System check failed, FanPress CM cannot be installed.'.PHP_EOL, true);
            }
            
            $this->input('Press any key to proceed...');
            
            return true;

        }

        /**
         * Konfiguration für DB-Verbindung erzeugen
         * @return boolean
         */
        private function initDatabase() {
            
            $this->output(PHP_EOL.'Init database connection...'.PHP_EOL);
            
            $databaseInfo = array_combine(array_map('strtoupper', array_keys($this->conf['database'])), array_map('trim', array_values($this->conf['database'])));
            usleep(250000);

            try {
                $db = new \fpcm\classes\database($databaseInfo);                
            } catch (\PDOException $exc) {
                $this->debug($databaseInfo);
                $this->output(PHP_EOL.$exc->getMessage().PHP_EOL, true);
            }
            
            if (!$db->checkDbVersion()) {                
                $this->output(PHP_EOL.'Unsupported database system detected. Installed version is '.$db->getDbVersion().', FanPress CM requires version '.$db->getRecommendVersion().PHP_EOL, true);
            }

            $db->createDbConfigFile($databaseInfo);
            usleep(250000);
            
            $this->output(PHP_EOL.'Init system encryption...'.PHP_EOL);
            usleep(250000);
            
            $crypt = new \fpcm\classes\crypt();
            $crypt->initCrypt();

            usleep(250000);

            return true;
        }

        /**
         * Datenbank-Tabellen erzeugen
         * @return boolean
         */
        private function createTables() {
            
            $this->output(PHP_EOL.'Create tables...'.PHP_EOL);
            $files = \fpcm\classes\database::getTableFiles();

            \fpcm\classes\baseconfig::$fpcmDatabase = new \fpcm\classes\database();
            foreach ($files as $file) {

                $tabName = substr(basename($file, '.yml'), 2);
                $this->output(PHP_EOL.'Create table '.$tabName);
                print '...';
                usleep(50000);

                $res = \fpcm\classes\baseconfig::$fpcmDatabase->execYaTdl($file);
                print '.';
                usleep(50000);

                print '.';
                if (!$res) $this->output(PHP_EOL.'Failed to create table table '.$tabName.PHP_EOL, true);
                usleep(50000);
            }

            print PHP_EOL.PHP_EOL;

            return true;
        }

        /**
         * System-Konfiguration erzeugen
         * @return boolean
         */
        private function initSystemConfig() {
            
            $this->output(PHP_EOL.'Initialize system configuration...'.PHP_EOL);
            usleep(250000);

            include \fpcm\classes\baseconfig::$versionFile;

            $newconfig = [
                'system_version'                => $fpcmVersion,
                'system_url'                    => $this->conf['config']['sysurl'],
                'system_lang'                   => $this->conf['config']['language'],
                'system_email'                  => $this->conf['config']['email'],
                'system_mode'                   => $this->conf['config']['mode'],
                'system_dtmask'                 => $this->conf['config']['datetimemask'],
                'system_timezone'               => $this->conf['config']['timezone'],
                'system_cache_timeout'          => $this->conf['config']['cachetimeout'],
                'system_session_length'         => $this->conf['config']['sessionlength'],
                'comments_antispam_question'    => $this->conf['config']['captcha']['question'],
                'comments_antispam_answer'      => $this->conf['config']['captcha']['answer'],
            ];

            $config = new \fpcm\model\system\config(false, false);
            $config->setNewConfig($newconfig);
            $config->prepareDataSave();
            usleep(250000);
            
            if (!$config->update()) {
                $this->output(PHP_EOL.'Error while updating system configuration '.PHP_EOL, true);
            }
            
            $this->output(PHP_EOL.'System configuration updated successful'.PHP_EOL);
            usleep(250000);
            
            return true;
        }

        /**
         * Ersten Benutzer erzeugen
         * @return boolean
         */
        private function createUserAccount() {
            
            $this->output(PHP_EOL.'Create first user as administrator...'.PHP_EOL);
            usleep(250000);
            
            if (in_array($this->conf['user']['username'], ['admin', 'root', 'test', 'support', 'administrator', 'adm'])) {
                usleep(50000);
                $this->output(PHP_EOL.'The selected username should not be used due to security problems...'.PHP_EOL);
                if ($this->input('Do you want to proceed with the username? (y/n)') === 'n') {
                    $this->conf['user']['username'] = $this->input('Please enter a new username?');
                    return false;
                }
            }

            $user = new \fpcm\model\users\author();
            $user->setDisplayName($this->conf['user']['displayname']);
            $user->setUserName($this->conf['user']['username']);
            $user->setPassword($this->conf['user']['password']);
            $user->setEmail($this->conf['user']['email']);
            $user->setRoll(1);
            $user->setUserMeta([]);
            $user->setRegistertime(time());
            $res = $user->save();
            usleep(250000);

            if ($res !== true) {
                $this->output(PHP_EOL.'Unable to create a user with the given data, return code was '.$res.PHP_EOL, true);
            }

            $this->output(PHP_EOL.'User created successfully...'.PHP_EOL);
            
        }

        /**
         * System bereinigung
         * @return boolean
         */
        private function cleanupSystem() {
            
            $this->output(PHP_EOL.'Cleanup system...'.PHP_EOL);

            $this->output(PHP_EOL.'Disable installer...'.PHP_EOL);
            if (!\fpcm\classes\baseconfig::enableInstaller(false)) {
                $this->output(PHP_EOL.'Failed, Run fpcmcli.php config --disable installer to enable installer.'.PHP_EOL);
            }
            usleep(75000);
            
            $this->output(PHP_EOL.'Cleanup cache...'.PHP_EOL);
            $cache = new \fpcm\classes\cache();
            $cache->cleanup();
            usleep(75000);
            
            $this->output(PHP_EOL.'Remove database table structure files...'.PHP_EOL);
            if (!\fpcm\model\files\ops::deleteRecursive(\fpcm\classes\baseconfig::$dbStructPath)) {
                $this->output(PHP_EOL.'Failed, please delete folder '.\fpcm\model\files\ops::removeBaseDir(\fpcm\classes\baseconfig::$dbStructPath).' ...'.PHP_EOL);
            }
            usleep(75000);

            return true;
        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
            $lines[] = '> Installer:';
            $lines[] = '';
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php installer';
            $lines[] = '';
            $lines[] = '    - The installer has no params to set.';
            return $lines;
        }

    }
