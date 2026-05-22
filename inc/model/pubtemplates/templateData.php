<?php

/**
 * Public article template file object
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Template data object
 *
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 5.3
 */
final class templateData implements \ArrayAccess
{
    /**
     * Constructor
     * @param string $file
     * @param string $content
     */
    public function __construct(string $file, string $content) {
        $this->file = $file;
        $this->content = $content;
    }

    /**
     * File path
     * @var string
     */
    public string $file;

    /**
     * File content
     * @var string
     */
    public string $content;

    /**
     * 
     * @param mixed $offset
     * @return bool
     * @ignore
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * 
     * @param mixed $offset
     * @return mixed
     * @ignore
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    /**
     * 
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @ignore
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * 
     * @param mixed $offset
     * @return void
     * @ignore
     */
    public function offsetUnset(mixed $offset): void
    {

    }

}
