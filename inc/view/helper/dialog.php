<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Dialog item
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.4-b3
 */
class dialog implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Dialog name
     * @var string
     */
    private string $name;

    /**
     * Dialog name
     * @var array
     */
    private array $fields;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set dialog fields
     * @param array[fpcm\view\helper\interfaces\jsDialogHelper] $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $this->checkElements($fields);
        return $this;
    }

    /**
     * Get dialog name
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Checks if element implements \fpcm\view\helper\interfaces\jsDialogHelper
     * @param \fpcm\view\helper\interfaces\jsDialogHelper $o
     * @return bool
     */
    private function checkElement($o) : bool
    {
        if (!is_object($o) || !$o instanceof interfaces\jsDialogHelper) {
            trigger_error(sprintf('Element of class %s must be an instance of "fpcm\view\helper\interfaces\jsDialogHelper"!', $o::class));
            return false;
        }

        return true;
    }

    /**
     * 
     * @param array $fields
     * @return array
     */
    private function checkElements(array $fields) : array
    {
        return array_filter($fields, function($o) {

            if (is_array($o)) {
                return $this->checkElements($o);
            }

            return $this->checkElement($o);
        });
    }
}
