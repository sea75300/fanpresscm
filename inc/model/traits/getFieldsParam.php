<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Gte fields from save params trait
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a1
 */
trait getFieldsParam {

    /**
     * Retrieve field names from save üarams
     * @param array $param
     * @param int $offset
     * @return array
     * @since 5.3
     */
    protected function getFieldFromSaveParams(array $params, int $offset = -1) : array
    {
        return array_slice(array_keys($params), 0, $offset);
    }

}
