<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * ItemArray Abstract
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL4.0
 */
abstract class itemArray extends item implements \ArrayAccess
{
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * 
     * @param mixed $offset
     * @return mixed
     * #[\ReturnTypeWillChange]
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        return;
    }

    public function offsetUnset($offset): void
    {
        return;
    }

}
