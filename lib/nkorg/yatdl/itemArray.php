<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Library\n
 * ItemArray Abstract
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version 4.0
 * @deprecated 5.0
 */
abstract class itemArray extends item implements \ArrayAccess
{
    public function offsetExists($offset): bool
    {
        trigger_error('\nkorg\yatdl\itemArray is deprecatesd as of YaTDL 5.0, use objects instead', E_USER_DEPRECATED);
        return array_key_exists($offset, $this->data);
    }

    /**
     * 
     * @param mixed $offset
     * @return mixed
     * #[\ReturnTypeWillChange]
     */
    public function offsetGet($offset): mixed
    {
        trigger_error('\nkorg\yatdl\itemArray is deprecatesd as of YaTDL 5.0, use objects instead', E_USER_DEPRECATED);
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        trigger_error('\nkorg\yatdl\itemArray is deprecatesd as of YaTDL 5.0, use objects instead', E_USER_DEPRECATED);
        return;
    }

    public function offsetUnset($offset): void
    {
        trigger_error('\nkorg\yatdl\itemArray is deprecatesd as of YaTDL 5.0, use objects instead', E_USER_DEPRECATED);
        return;
    }

}
