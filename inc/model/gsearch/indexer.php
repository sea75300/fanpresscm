<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\gsearch;

/**
 * Global search indexer, performs search
 * 
 * @package fpcm\model\gsearch
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class indexer extends \fpcm\model\abstracts\tablelist
{
    /**
     * List of model classes to search on
     * @var array
     */
    private $models = [
        '\fpcm\model\articles\articlelist',
        '\fpcm\model\comments\commentList',
        '\fpcm\model\files\imagelist'
    ];

    /**
     * 
     * @var resultSet
     */
    private $result;

    /**
     * 
     * @param type $param
     */
    public function getItems(conditions $param)
    {

        $result = $this->events->trigger('searchall/list', $this->models);
        if (!$result->getSuccessed() || $result->getContinue()) {
            return new resultSet([, 0]);
        }

        $this->models = array_filter($result->getData(), function ($item) {
            return is_a($item, '\fpcm\model\interfaces\gsearchIndex');
        });

        if (!count($this->models)) {
            return new resultSet([, 0]);
        }        
        
        $countQueries = [];
        $searchQueries = [];
        
        foreach ($this->models as $class) {
            
            /* @var $obj \fpcm\model\interfaces\gsearchIndex */
            $obj = new $class();
            $countQueries[] = $obj->getCountQuery();
            $searchQueries[] = $obj->getSearchQuery();

        }
        
        $this->dbcon->select($obj->getTableName(), '*', $obj->getSearchQuery());
        
        
    }

    
}
