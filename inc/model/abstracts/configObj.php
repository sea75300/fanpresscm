<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Abstract system config item for SMTP settings
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5-rc2
 */
abstract class configObj implements \ArrayAccess, \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;
    
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
        return;
    }

    public function offsetUnset($offset): void
    {
        return;
    }

    public function __serialize(): array
    {
        return get_object_vars($this);
    }

    public function __unserialize($data): void
    {
        $this->init($data);
        return;
    }

    final protected function init(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

}
