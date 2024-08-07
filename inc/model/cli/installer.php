<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli installer module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5.1
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
    public function process()
    {
        $this->output('FanPress CM cli installer (BETA)');

        $this->checkPreconditions();
        $this->loadConfig();
        $this->runSystemCheck();
        $this->initDatabase();
        $this->createTables();
        $this->initSystemConfig();
        $this->createUserAccount();
        $this->cleanupSystem();

        $this->output('FanPress CM has been installed successfuly.');
    }

    /**
     * Prüfung, ob Installer aktiv ist
     * @return bool
     */
    private function checkPreconditions()
    {
        $this->output('Check preconditions...');
        
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
     * @return bool
     */
    private function loadConfig()
    {
        $this->output('Load installer config file...');

        $configFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG, 'installer.yml');
        if (!file_exists($configFile)) {
            $this->output('No configuration file found in ' . \fpcm\model\files\ops::removeBaseDir(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG), true), true);
        }

        $this->conf = \Spyc::YAMLLoad($configFile);

        $this->language = new \fpcm\classes\language($this->conf['config']['language']);
        usleep(250000);

        return true;
    }

    /**
     * System Check ausführen
     * @return bool
     */
    private function runSystemCheck()
    {
        $this->output('Executing system check...');

        $rows = $this->getCheckOptionsSystem();

        $checkFailed = false;

        $lines = [PHP_EOL];
        foreach ($rows as $descr => $data) {

            print '.';
            $lines[] = $data->asString(strip_tags($descr));

            usleep(50000);
            if ($data->getOptional() || $data->getResult()) {
                continue;
            }

            $checkFailed = true;
        }
        
        print PHP_EOL . PHP_EOL;

        $this->output('System check executed, results are:');
        usleep(250000);

        $this->output($lines);

        if ($checkFailed) {
            $this->output('System check failed, FanPress CM cannot be installed.' . PHP_EOL, true);
        }

        $this->input(PHP_EOL . 'Press any key to proceed...');

        return true;
    }

    /**
     * Konfiguration für DB-Verbindung erzeugen
     * @return bool
     */
    private function initDatabase()
    {
        $this->output('Init database connection...');

        $databaseInfo = array_change_key_case($this->conf['database'], CASE_UPPER);
        usleep(250000);

        try {
            $db = new \fpcm\classes\database($databaseInfo);
        } catch (\PDOException $exc) {
            $this->debug($databaseInfo);
            $this->output(PHP_EOL . $exc->getMessage() . PHP_EOL, true);
        }

        if (!$db->checkDbVersion()) {
            $this->output('Unsupported database system detected. Installed version is ' . $db->getDbVersion() . ', FanPress CM requires version ' . $db->getRecommendVersion() . PHP_EOL, true);
        }

        $db->createDbConfigFile($databaseInfo);
        usleep(250000);

        $this->output('Init system encryption...');
        usleep(250000);

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $crypt->initCrypt();

        $this->output('Init system security data...');
        usleep(250000);
        
        \fpcm\classes\security::initSecurityConfig();

        usleep(250000);
        $this->input(PHP_EOL . 'Press any key to proceed...');

        return true;
    }

    /**
     * Datenbank-Tabellen erzeugen
     * @return bool
     */
    private function createTables()
    {
        \fpcm\classes\loader::getObject('\fpcm\classes\database', null, false);
        
        $this->output('Create tables...' . PHP_EOL);
        $files = \fpcm\classes\database::getTableFiles();

        $i = 0;
        $progress = new progress(count($files), $i);
        
        foreach ($files as $file) {

            $i++;
            $tabName = substr(basename($file, '.yml'), 2);

            $progress->setCurrentValue($i)->setOutputText($tabName)->output();            

            $res = \fpcm\classes\loader::getObject('\fpcm\classes\database')->execYaTdl($file);
            usleep(50000);

            if (!$res) {
                $this->output('Failed to create table table ' . $tabName . PHP_EOL, true);
            }

            usleep(50000);
        }

        print PHP_EOL;

        return true;
    }

    /**
     * System-Konfiguration erzeugen
     * @return bool
     */
    private function initSystemConfig()
    {
        $this->output('Initialize system configuration...' . PHP_EOL);
        usleep(250000);

        $newconfig = [
            'system_version' => \fpcm\classes\baseconfig::getVersionFromFile(),
            'system_url' => $this->conf['config']['sysurl'],
            'system_lang' => $this->conf['config']['language'],
            'system_email' => $this->conf['config']['email'],
            'system_mode' => $this->conf['config']['mode'],
            'system_dtmask' => $this->conf['config']['datetimemask'],
            'system_timezone' => $this->conf['config']['timezone'],
            'system_cache_timeout' => $this->conf['config']['cachetimeout'],
            'comments_antispam_question' => $this->conf['config']['captcha']['question'],
            'comments_antispam_answer' => $this->conf['config']['captcha']['answer'],
        ];

        $config = new \fpcm\model\system\config();
        $config->setNewConfig($newconfig);
        $config->prepareDataSave();
        usleep(250000);

        if (!$config->update()) {
            $this->output('Error while updating system configuration ' . PHP_EOL, true);
        }

        $this->output('System configuration updated successful' . PHP_EOL);
        usleep(250000);

        return true;
    }

    /**
     * Ersten Benutzer erzeugen
     * @return bool
     */
    private function createUserAccount()
    {
        $this->output('Create first user as administrator...' . PHP_EOL);
        usleep(250000);

        if (in_array($this->conf['user']['username'], FPCM_INSECURE_USERNAMES)) {
            usleep(50000);
            $this->output('The selected username should not be used due to security problems...' . PHP_EOL);
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
            $this->output('Unable to create a user with the given data, return code was ' . $res . PHP_EOL, true);
        }

        $this->output('User created successfuly...' . PHP_EOL);
    }

    /**
     * System bereinigung
     * @return bool
     */
    private function cleanupSystem()
    {
        $this->output('Cleanup system...' . PHP_EOL);

        $this->output('Disable installer...' . PHP_EOL);
        if (!\fpcm\classes\baseconfig::enableInstaller(false)) {
            $this->output('Failed, Run fpcmcli.php config --disable installer to enable installer.' . PHP_EOL);
        }
        usleep(75000);

        $this->output('Cleanup cache...' . PHP_EOL);
        $cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $cache->cleanup();
        usleep(75000);

        return true;
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Installer:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php installer';
        $lines[] = '';
        $lines[] = '    - The installer has no params to set.';
        return $lines;
    }

}
