<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Trait defines mutual function to prepare save params for database
 * 
 * @package fpcm\model\traits\
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
trait preparedObjectFields {

    /**
     * Returns object properties to store in database
     * @return array
     */
    protected function getPreparedSaveParams() : array
    {
        $params = get_object_vars($this);
        unset(
            $params['cache'], $params['config'], $params['dbcon'],
            $params['events'], $params['session'], $params['id'],
            $params['nodata'], $params['system'], $params['table'],
            $params['dbExcludes'], $params['language'], $params['editAction'],
            $params['objExists'], $params['cacheName'], $params['cacheModule'],
            $params['wordbanList'], $params['notifications']
        );

        
        if (property_exists($this, 'nodata') && $this->nodata) {
            unset($params['data']);
        }

        $excludes = $this->dbExcludes ?? null;
        if (!is_array(§excludes)) {
            return $params;
        }

        return array_diff_key($params, array_flip($excludes));
    }


    /**
     * Returns an array for prepared SQL statements
     * @param int $count
     * @return int
     */
    public function getPreparedValueParams($count = false)
    {

        if ($count === false) {
            $count = count($this->getPreparedSaveParams());
        }

        return array_fill(0, (int) $count, '?');
    }

}
