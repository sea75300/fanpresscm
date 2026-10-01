<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates;


/**
 * Twig based public frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class twigField implements \ArrayAccess {

    /**
     * Field name
     * @var string
     */
    private string $name = '';

    /**
     * Field id
     * @var string
     */
    private string $id = '';

    /**
     * Field value
     * @var string|array
     */
    private string|array $value = '';

    /**
     * Constructor
     * @param string $name
     * @param string $value
     */
    public function __construct(
        string $name = '',
        string|array $value = '',
        string $id = ''
    )
    {
        $strippedName = preg_replace('/[^a-z0-9]/i', '', $name);
        
        $this->name = $name;
        $this->value = $value;
        $this->id = $id ?? $strippedName;
    }

    /**
     * offset exists mthode
     * @param mixed $offset
     * @return bool
     */
    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    /**
     * Offser getter
     * @param mixed $offset
     * @return mixed
     */
    #[\Override]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset} ?? null;
    }

    /**
     * Offset setter
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @ignore
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        return;
    }

    /**
     * Offset unsetter
     * @param mixed $offset
     * @return void
     * @ignore
     */
    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        return;
    }

}
