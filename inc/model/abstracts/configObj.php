<?php

/**
 * FanPress CM 5.x
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

    /**
     * Check if offset exists
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    /**
     * Return offset value
     * @param int|string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set offset value
     * @param int|string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
        return;
    }

    /**
     * Unset offset, not in use
     * @param type $offset
     * @return void
     * @ignore
     */
    public function offsetUnset($offset): void
    {
        return;
    }

    /**
     * Magic serialize
     * @return array
     * @ignore
     */
    public function __serialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * Magic unserialize
     * @param int|string $data
     * @return void
     * @ignore
     */
    public function __unserialize($data): void
    {
        $this->init($data);
        return;
    }

    /**
     * Return JSOn string
     * @return string
     */
    final public function toJSON(): string
    {
        return json_encode($this);
    }

    /**
     * INit data
     * @param array $data
     */
    final protected function init(array $data)
    {
        foreach ($data as $key => $value) {
            
            if (!isset($this->$key)) {
                continue;
            }
            
            $this->$key = $value;
        }
    }

}
