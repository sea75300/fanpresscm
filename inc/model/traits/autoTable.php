<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Auto-detected database table name
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
trait autoTable {

    /**
     * Returns table name based on class name
     * @return string
     * @since 4.5
     */
    final public function getTableName() : string
    {
        if (trim($this->table)) {
            return $this->table;
        }

        list($object, $item) = array_slice(explode('\\', static::class), -2);

        $this->table = in_array($item, ['item', 'items']) ? $object : $item;
        if (substr($this->table, -1) === 's') {
            return $this->table;
        }
        
        $this->table .= 's';
        return $this->table;
        
    }
}

?>