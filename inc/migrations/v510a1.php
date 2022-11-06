<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.1.0-a1
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.1.0-a1
 * @see migration
 */
class v510a1 extends migration {

    protected function alterTablesAfter(): bool {
        
        $obj = new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableArticles);
        $obj->setItem('id, categories');
        $obj->setWhere('1=1 '.$this->getDB()->orderBy(['id ASC']));
        $obj->setFetchAll(true);
        $obj->setFetchStyle(\PDO::FETCH_KEY_PAIR);
        
        
        $datasets = $this->getDB()->selectFetch($obj);
        if (!is_array($datasets)) {
            fpcmLogSql('Error while converting categories!');
            return true;
        }        
        
        foreach ($datasets as $articleId => $categories) {

            fpcmLogSystem(sprintf('Converting article %s categories %s', $articleId, $categories));
            
            $categories = json_decode($categories, true);
            if (!is_array($categories)) {
                fpcmLogSql('Error while converting categories!');
                continue;
            }
            
            foreach ($categories as $categoryId) {
                
                $ac = new \fpcm\model\articles\articleCategory((int) $articleId, (int) $categoryId);
                if ($ac->save()) {
                    continue;
                }

                fpcmLogSql(sprintf('Error while converting article %s category %s', $articleId, $categoryId));
            }
 
        }

        return true;
    }

    /**
     * return preview version string
     * @return string
     * @since 4.5.1-b1
     */
    protected function getPreviewsVersion() : string
    {
        return '5.0.2';
    }

}