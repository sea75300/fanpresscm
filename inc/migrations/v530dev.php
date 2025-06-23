<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.5-a1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.5-a1
 * @see migration
 */
class v530dev extends migration {

    protected function alterTablesAfter(): bool {

        fpcmLogSystem('Update files index...');

        $obj = new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableFiles );
        $obj->setWhere("filehash = '' AND mimetype = '' AND width = 0 AND height = 0");
        $obj->setItem('id, filename');
        $obj->setFetchStyle(\PDO::FETCH_KEY_PAIR);
        $obj->setFetchAll(true);

        $files = $this->getDB()->selectFetch($obj);
        
        fpcmLogSystem($this->getDB()->getLastQueryString());
        fpcmLogSystem($files);
        
        if (!is_array($files)) {
            trigger_error('Unable to fetch data from files index!');
            return false;
        }

        if (!count($files)) {
            fpcmLogSystem('No files to update, skip process...');
            return true;
        }

        $results = [];

        foreach ($files as $id => $filename) {
            $img = new \fpcm\model\files\image($filename);
            $results[$filename] = (int) $img->update();
        }
        
        $err = array_keys($results, 0);
        if (count($err)) {
            fpcmLogSystem(sprintf("The following files could not be updates!\n\r%s", implode('\n\r', $err)));
        }
        
        fpcmLogSystem('Update files index finished...');
        return true;
    }

        /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.3.0-dev';
    }

}
