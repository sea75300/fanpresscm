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
 * @since 5.3.4-b2
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
     * 
     * @param array[fpcm\view\helper\interfaces\jsDialogHelper] $fields
     * @return $this
     */
    public function setFields(array $fields)
    {        
        $this->fields = array_filter($fields, function($o) {

            if (!is_object($o) || !$o instanceof interfaces\jsDialogHelper) {
                trigger_error(sprintf('Element of class %s must be an instance of "fpcm\view\helper\interfaces\jsDialogHelper"!', $o::class));
                return false;
            }
            
            return true;
        });

        $this->fields = $fields;
        return $this;
    }

}
