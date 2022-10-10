<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components;

/**
 * Mass edit field component
 * 
 * @package fpcm\components\charts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2019-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class masseditField implements \Stringable {

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
    public function __construct($field, ?string $class = '')
    {
        if (! $field instanceof \fpcm\view\helper\helper) {
            trigger_error('$field must be an object of instance \fpcm\view\helper\helper');
            return;
        }

        $this->field = $field;
        
        if ($class === null) {
            return;
        }

        $this->field->setClass('fpcm-ui-input-massedit ' . $class);
    }

    /**
     * 
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return sprintf('<div class="row"><div class="col">%s</div></div>', $this->field);
    }

}
