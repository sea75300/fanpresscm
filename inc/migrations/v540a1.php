<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.4.0-a1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.4.0-a1
 * @see migration
 */
class v540a1 extends migration {

    /**
     * Update database
     * @return bool
     */
    protected function alterTablesAfter(): bool
    {
        $dbStructFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBSTRUCT, '11smileys.yml');
        
        fpcmLogSystem(__METHOD__ . ' ' . $dbStructFile);
        
        clearstatcache();
        
        fpcmLogSystem('Remove smiley manager table...');

        if (file_exists($dbStructFile)) {        
            $res = $this->getDB()->drop('smileys');
            $res = $tes && unlink($dbStructFile);
            fpcmLogSystem(printf('Remove smiley manager table: %c', $res));        
        }
        else {
            $res = true;
            fpcmLogSystem('Remove smiley manager table: skipped, dbstruct not found');
        }
        
        fpcmLogSystem('Create module events index for the first time');
        
        
        $modules = new \fpcm\module\modules();
        $installed = $modules->getInstalledDatabase();
        
        if (!count($installed)) {
            fpcmLogSystem('No modules found, continue...');
            return true;
        }
        
        fpcmLogSystem(array_keys($installed));

        $baseClass = str_replace('fpcm\\events\\', '', \fpcm\events\abstracts\event::class);

        foreach ($installed as $key => $module) {

            $module_base = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $key . DIRECTORY_SEPARATOR .  'events' );
            
            $level1 = glob($module_base . '*.php');
            if (!is_array($level1)) {
                $level1 = [];
            }

            $level2 = glob($module_base . '*/*.php');
            if (!is_array($level2)) {
                $level2 = [];
            }            

            $classes = array_merge_recursive($level1, $level2);

            fpcmLogSystem($classes);            

            foreach ($classes as $class) {
                
                $className = \fpcm\model\files\ops::removeBaseDir($class);
                $className = substr($className, 0, -4);
                $className = substr($className, 13);


                $eventName = preg_replace('/(.*events\\/)(.*)/i', '$2', $className);
                $moduleClassName = sprintf('\\fpcm\\modules\\%s', str_replace('/', '\\', $className));
                
                if (!$eventName) {
                    trigger_error('Empty event name detected, aborting event cache buildup');
                    return false;
                }
                
                $entry = [
                    'event_name' => $eventName,
                    'module_key' => $key,
                    'class_name' => $moduleClassName
                ];                
                

                fpcmLogSystem($entry);
                
                if (!class_exists($moduleClassName)) {
                    trigger_error(sprintf('Module event class "%s" does not exist.', $moduleClassName));
                    continue;
                }
                
                
                $res = $res && $this->getDB()->insert(\fpcm\classes\database::tableEvents, $entry);
                
            }

        }            
        

        return $res;
    }

    /**
     * Updateb file system
     * @return bool
     */
    protected function updateFileSystem(): bool
    {
        fpcmLogSystem('Remove smiley manager folder...');
        
        $path = \fpcm\classes\dirs::getDataDirPath('smileys');
        if (!file_exists($path)) {
            return true;
        }
        
        return \fpcm\model\files\ops::deleteRecursive($path);
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.4.0-a1';
    }

}