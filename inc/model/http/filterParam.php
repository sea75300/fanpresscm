<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * UI search filter params object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 5.3.0-dev
 */
final class filterParam {

    /**
     * Combination string
     * @var string
     */
    private string $combination = '';

    /**
     * Search field
     * @var string
     */
    private ?string $field = null;

    /**
     * Search value
     * @var mixed
     */
    private mixed $value = null;

    /**
     * Constructor
     * @param string $dataViewName
     * @param array $dataViewVars
     * @param \fpcm\view\message $message
     */
    public function __construct(array $values)
    {
        $fields = get_object_vars($this);
        foreach ($fields as $key => $def) {
            $this->{$key} = $values[$key] ?? $def;
        }
    }

    /**
     * Get combination string
     * @return string
     */
    public function getCombination(): string
    {
        return $this->combination;
    }

    /**
     * Get field name
     * @return string|null
     */
    public function getField(): ?string    
    {
        return $this->field;
    }

    /**
     * Get value
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Set value
     * @param mixed $value
     * @return $this
     */
    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

}
