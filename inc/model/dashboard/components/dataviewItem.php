<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\components;

/**
 * Dashboard dataview item object
 *
 * @package fpcm\model\traits\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a1
 */
class dataviewItem {

    const TYPE_LINK = 'link';

    const TYPE_TEXT = 'text';

    const TYPE_ICONS = 'icons';

    const TYPE_BOOLICON = 'BoolIcon';

    /**
     * Item value
     * @var mixed
     */
    private mixed $value = '';

    /**
     * Item type
     * @var string1
     */
    private string $type = '';

    /**
     * Item value align
     * @var string
     */
    private string $align = '';

    /**
     * Item value align
     * @var string
     */
    private string $class = '';

    /**
     * Item col size
     * @var string|int
     */
    private string|int $size = '';

    /**
     * Constructor
     * @param mixed $value
     * @param string $type
     * @param string $align
     * @param string|int $size
     */
    public function __construct(mixed $value, string $type, string $align = '', string|int $size = '', string $class = '')
    {
        $this->value = $value;
        $this->type = $type;
        $this->align = $align;
        $this->size = $size;
        $this->class = $class;
    }

    /**
     * Returns value
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Returns type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns align
     * @return string
     */
    public function getAlign(): string
    {
        return $this->align ? sprintf('text-lg-%s', $this->align) : '';
    }

    /**
     * Returns size
     * @return string|int
     */
    public function getSize(): string|int
    {
        if ($this->type === self::TYPE_LINK) {
            return 'w-' . ($this->size ? $this->size : 'auto');
        }
        
        return $this->size ? sprintf('col-12 col-lg-%s', $this->size) : 'col-12 col-lg';
    }

    /**
     * Get css class
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}
