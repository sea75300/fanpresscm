<?php

/**
 * System update finalizer object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

/**
 * System Update Finalizer Objekt
 * 
 * @package fpcm\model\updater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class finalizer extends \fpcm\model\abstracts\model {

    /**
     * Run in command line mode
     * @var bool
     * @since FPCM 4.1
     */
    private $isCli = false;

    /**
     * Initialisiert System Update
     * @param int $init
     */
    public function __construct()
    {
        parent::__construct();

        $this->dbcon = new \fpcm\classes\database();
        $this->config = new \fpcm\model\system\config(false, false);
        $this->isCli = \fpcm\classes\baseconfig::isCli();
    }

    /**
     * Führt abschließende Update-Schritte aus
     * @return bool
     */
    public function runUpdate()
    {
        $res    = true &&
                $this->alterTables() &&
                $this->removeSystemOptions() &&
                $this->addSystemOptions() &&
                $this->updateSystemOptions() &&
                $this->updatePermissions() &&
                $this->updateVersion() &&
                $this->optimizeTables() &&
                $this->createTemplates();

        $class = \fpcm\migrations\migration::getNamespace(\fpcm\classes\baseconfig::getVersionFromFile());

        if (class_exists($class)) {

            /* @var $obj migration */
            $obj = new $class;
            if ($obj->isRequired()) {
                $res = $res && $obj->process();
            }

        }

        if (\fpcm\classes\baseconfig::canConnect()) {
            (new \fpcm\model\crons\updateCheck())->run();
        }

        return $res;
    }

    /**
     * aktualisiert Versionsinfos in Datenbank
     * @return bool
     */
    private function updateVersion()
    {
        $this->config->setNewConfig([
            'system_version' => \fpcm\classes\baseconfig::getVersionFromFile()
        ]);

        return $this->config->update();
    }

    /**
     * aktualisiert Berechtigungen
     * @return bool
     */
    private function updatePermissions()
    {
        $this->cliOutput(" >> Update system permissions");
        
        $rolls = (new \fpcm\model\users\userRollList())->getUserRolls();

        $default = null;

        foreach ($rolls as $group) {

            $permissionObj = new \fpcm\model\permissions\permissions($group->getId());
            
            if ($default === null) {
                $default = $permissionObj->getPermissionSet();
            }
            
            $data = $permissionObj->getPermissionData();
            
            $default['comment']['lockip'] = 1;
            $default['system']['profile'] = 1;
            
            $newData = $data;
            foreach ($default as $key => $value) {
                $newData[$key] = array_merge(array_intersect_key($data[$key], $value), array_diff_key($value, $data[$key]));
            }
            
            if (\fpcm\classes\tools::getHash(json_encode($data)) === \fpcm\classes\tools::getHash(json_encode($newData))) {
                continue;
            }

            $permissionObj->setPermissionData($newData);
            if (!$permissionObj->update()) {
                return false;
            }

        }

        return true;
    }

    /**
     * neue System-Optionen bei Update erzeugen
     * @return bool
     */
    private function addSystemOptions()
    {
        $this->cliOutput(" >> Add new system config...");
        
        $yatdl = new \fpcm\model\system\yatdl(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBSTRUCT, '06config.yml'));
        $yatdl->parse();

        $data = $yatdl->getArray();
        if (!isset($data['defaultvalues']['rows']) || !is_array($data['defaultvalues']['rows']) || !count($data['defaultvalues']['rows'])) {
            return true;
        }

        $res = true;
        foreach ($data['defaultvalues']['rows'] as $option) {

            if ($option['config_name'] === 'smtp_setting') {
                continue;
            }

            $res = $res && $this->config->add($option['config_name'], trim($option['config_value']));
        }

        if ($this->dbcon->count(\fpcm\classes\database::tableCronjobs, '*', 'cjname = ?', ['cleanupTrash']) == 0) {
            $id = $this->dbcon->insert(\fpcm\classes\database::tableCronjobs, [
                'cjname' => 'cleanupTrash',
                'lastexec' => 0,
                'execinterval' => 86400
            ]);

            $res = $id ? $res && true : false;
        }

        return $res;
    }

    /**
     * System-Optionen bei Update aktualisieren
     * @return bool
     */
    private function updateSystemOptions()
    {
        $this->cliOutput(" >> Update existing system config...");
        
        $newConfig = [];

        if (is_numeric($this->config->system_editor)) {

            $editors = [
                0 => '\fpcm\components\editor\tinymceEditor',
                1 => '\fpcm\components\editor\htmlEditor'
            ];

            $newConfig['system_editor'] = isset($editors[$this->config->system_editor]) ? $editors[$this->config->system_editor] : $editors[0];
        }

        if (!count($newConfig)) {
            return true;
        }

        $this->config->setNewConfig($newConfig);
        return $this->config->update();
    }

    /**
     * System-Optionen bei Update aktualisieren
     * @return bool
     */
    private function removeSystemOptions()
    {
        $this->cliOutput(" >> Cleanup system config...");
        
        $res = true;
        if ($this->config->articles_trash) {
            $res = $res && $this->config->remove('articles_trash');
        }

        if ($this->config->file_fiew) {
            $res = $res && $this->config->remove('file_fiew');
        }
        
        return $res;
    }

    /**
     * Änderungen an Tabellen-Struktur vornehmen
     * @return bool
     */
    private function alterTables()
    {
        $tableFiles = $this->dbcon->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        $dropTables = [];
        if (version_compare($this->config->system_version, '4.0.0-b11', '<')) {
            $dropTables[] = \fpcm\classes\database::tableModules;
        }
        
        $addIndeices = method_exists($this->dbcon, 'addTableIndices');

        foreach ($tableFiles as $tableFile) {

            $tab = new \fpcm\model\system\yatdl($tableFile);

            $success = $tab->parse();
            if ($success !== true) {
                trigger_error('Unable to parse table definition for ' . $tableFile . ', ERROR CODE: ' . $success);
                return false;
            }

            $tableName = $tab->getArray()['name'];
            $this->cliOutput(" >> Alter table structure {$tableName}...");

            $struct = $this->dbcon->getTableStructure($tableName);
            $tabExists = count($struct) ? true : false;

            if ($tabExists && !$this->dbcon->addTableCols($tab) || !$this->dbcon->removeTableCols($tab)) {
                trigger_error('Failed to alter table ' . $tableName . ' during update.');
                return false;
            }

            if (in_array($tableName, $dropTables)) {

                fpcmLogSql("Drop table {$tableName}...");
                $this->cliOutput("     >> Drop table {$tableName}...");

                $successDrop = false;
                if (!$tabExists) {
                    $this->cliOutput("     >> Table not found, skipping...");
                }
                elseif (!$this->dbcon->drop($tableName)) {
                    $this->cliOutput("     -- FAILED");
                    trigger_error('Unable to drop table ' . $tableName . ' during update');
                    return false;
                }
                else {
                    $successDrop = true;
                }

                if ($successDrop) {
                    $tabExists = false;
                }

            }

            if (!$tabExists) {
                
                fpcmLogSql("Add table {$tableName}...");
                $this->cliOutput("     >> Add table {$tableName}...");

                if (!$this->dbcon->execYaTdl($tableFile)) {
                    $this->cliOutput("     -- FAILED");
                    trigger_error('Unable to create table ' . $tableName . ' during update');
                    return false;
                }

            }
            
            if ($tabExists && $addIndeices) {
                $this->dbcon->addTableIndices($tab);
            }

            $this->cliOutput("     -- FINISHED");
        }

        if (!$addIndeices) {
            $this->cliOutput("     ++ Important!! Table indices could not be added during database update. Please run \"fpcmcli.php pkg " . \fpcm\model\abstracts\cli::PARAM_UPGRADE_DB. " system\" after auto-update was finished.");
        }

        return true;
    }

    /**
     * Führt Optimierung der Datenbank-Tabellen durch
     * @since FPCM 3.3
     * @return bool
     */
    private function optimizeTables()
    {
        $this->cliOutput(" >> Optimize tables...");
        
        $tables = $this->events->trigger('updaterAddOptimizeTables', [
            \fpcm\classes\database::tableArticles,
            \fpcm\classes\database::tableAuthors,
            \fpcm\classes\database::tableCategories,
            \fpcm\classes\database::tableComments,
            \fpcm\classes\database::tableConfig,
            \fpcm\classes\database::tableCronjobs,
            \fpcm\classes\database::tableFiles,
            \fpcm\classes\database::tableIpAdresses,
            \fpcm\classes\database::tableModules,
            \fpcm\classes\database::tablePermissions,
            \fpcm\classes\database::tableRoll,
            \fpcm\classes\database::tableSessions,
            \fpcm\classes\database::tableSmileys,
            \fpcm\classes\database::tableTexts,
            \fpcm\classes\database::tableRevisions
        ]);

        foreach ($tables as $table) {
            $this->dbcon->optimize($table);
        }

        return true;
    }

    /**
     * Prüft System-Version auf bestimmten Wert
     * @param string $version
     * @param string $option
     * @return bool
     * @since FPCM 3.2
     */
    private function checkVersion($version, $option = '<')
    {
        return version_compare($this->config->system_version, $version, $option);
    }

    /**
     * Creates missing templates druing update if not template not exists
     * @return bool
     */
    private function createTemplates()
    {
        $tpl = new \fpcm\model\pubtemplates\sharebuttons();
        if ($tpl->exists()) {
            fpcmLogSystem('Skip creation of new template '.$tpl->getFilename());
            return true;
        }

        $res = file_put_contents($tpl->getFullpath(), implode(PHP_EOL, [
            '<ul class="fpcm-pub-sharebuttons">',
            '    <li>{{likeButton}}</li>',
            '    <li>{{facebook}}</li>',
            '    <li>{{twitter}}</li>',
            '    <li>{{tumblr}}</li>',
            '    <li>{{pinterest}}</li>',
            '    <li>{{reddit}}</li>',
            '    <li>{{whatsapp}}</li>',
            '    <li>{{email}}</li>',
            '</ul>',
            '{{credits}}'
        ]));

        return $res ? true : false;
    }

    /**
     * Print text in command line mode
     * @param string $str
     * @since FPCM 4.1
     */
    private function cliOutput(string $str)
    {
        if (!$this->isCli) {
            return;
        }
        
        \fpcm\model\cli\io::output($str);
    }

}

?>
