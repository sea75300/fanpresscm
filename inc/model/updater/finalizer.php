<?php

/**
 * System update finalizer object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * Initialisiert System Update
     * @param int $init
     */
    public function __construct()
    {
        parent::__construct();

        $this->dbconcon = new \fpcm\classes\database();
        $this->config = new \fpcm\model\system\config(false, false);
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
                $this->optimizeTables();

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
     * @return boolean
     */
    private function updatePermissions()
    {
        $rolls = (new \fpcm\model\users\userRollList())->getUserRolls();

        $default = null;

        foreach ($rolls as $group) {

            $permissionObj = new \fpcm\model\system\permissions($group->getId());
            
            if ($default === null) {
                $default = $permissionObj->getPermissionSet();
            }
            
            $data = $permissionObj->getPermissionData();
            
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
        $tableFiles = $this->dbconcon->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        $dropTables = [];
        
        $isCli = \fpcm\classes\baseconfig::isCli();

        foreach ($tableFiles as $tableFile) {

            $tab = new \fpcm\model\system\yatdl($tableFile);

            $success = $tab->parse();
            if ($success !== true) {
                trigger_error('Unable to parse table definition for ' . $tableFile . ', ERROR CODE: ' . $success);
                return false;
            }

            $tableName = $tab->getArray()['name'];

            if ($isCli) {
                print " >> Alter table {$tableName}, add and remove columns...".PHP_EOL;
            }
            
            $struct = $this->dbcon->getTableStructure($tableName);
            $tabExists = count($struct) ? true : false;

            if ($tabExists && !$this->dbcon->addTableCols($tab) || !$this->dbcon->removeTableCols($tab)) {
                trigger_error('Failed to alter table ' . $tableName . ' during update.');
                return false;
            }

            if (in_array($tableName, $dropTables)) {
                
                if ($isCli) {
                    print "     >> Drop table {$tableName}...".PHP_EOL;
                }

                $successDrop = false;
                if (!$tabExists) {
                    print "     >> Table not found, skipping...".PHP_EOL;
                }
                elseif ($successDrop = !$this->dbcon->drop($tableName)) {
                    trigger_error('Unable to drop table ' . $tableName . ' during update');
                    return false;
                }

                if ($successDrop) {
                    $tabExists = false;
                }
                
                print "     -- FINISHED".PHP_EOL;
            }

            if (!$tabExists) {
                
                if ($isCli) {
                    print "     >> Add table {$tableName}...".PHP_EOL;
                }

                if (!$this->dbcon->execYaTdl($tableFile)) {
                    trigger_error('Unable to create table ' . $tableName . ' during update');
                    return false;
                }

                if ($isCli) {
                    print "     -- FINISHED".PHP_EOL;
                }
            }

        }
        
        return true;
    }

    /**
     * Führt Optimierung der Datenbank-Tabellen durch
     * @since FPCM 3.3
     * @return boolean
     */
    private function optimizeTables()
    {
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
            $this->dbconcon->optimize($table);
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

}

?>