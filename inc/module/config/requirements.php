<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module\config;

/**
 * Module config requiremeonts
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module\config
 * @since 5.3.0
 */
class requirements implements \JsonSerializable, \ArrayAccess {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * PHP minimum version
     * @var string
     */
    public string $php = '';

    /**
     * FPCM minimum version
     * @var string
     */
    public string $system = '';

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        return;
    }

}
