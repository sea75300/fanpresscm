<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * Auto increment item
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL4.4
 * 
 * @property string $name item name
 * @property string $primarykey primary key column
 * @property string $engine engine (MySQL/MariaDB only)
 * @property string $charset charset (MySQL/MariaDB only)
 * 
 * @property bool $isview flag if item is view
 * @property string $query query string, only for views
 * 
 * @property array $autoincrement auto incremen setting
 * @property array $cols table column
 * @property array $indices table indices
 * 
 */
class tableItem extends item implements \ArrayAccess
{
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        return false;
    }

    public function offsetUnset($offset): void
    {
        return false;
    }

}
