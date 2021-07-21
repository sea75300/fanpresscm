<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components;

/**
 * Mass edit field
 * 
 * @package fpcm\components
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class masseditField {

    /**
     * field object
     * @var \fpcm\view\helper\helper
     */
    protected $field    = null;

    /**
     * Constructor
     * @param \fpcm\view\helper\helper $field
     * @param string $class
     * @return void
     */
    public function __construct($field, string $class = '')
    {
        if (! $field instanceof \fpcm\view\helper\helper) {
            trigger_error('$field must be an object of instance \fpcm\view\helper\helper');
            return;
        }

        $this->field = $field;
        $this->field->setClass('fpcm-ui-input-massedit ' . $class);
    }

    /**
     * 
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return implode('', [
            "<div class=\"row\">",
            $this->field,
            "</div>"
        ]);
    }

}
