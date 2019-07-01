<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components;

/**
 * Mass edit field
 * 
 * @package fpcm\drivers\mysql
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class masseditField {

    /**
     * Icon class string
     * @var string
     */
    protected $icon     = '';

    /**
     * Desciption string
     * @var string
     */
    protected $descr    = '';

    /**
     * field object
     * @var \fpcm\view\helper\helper
     */
    protected $field    = null;

    /**
     * Additional CSS class string
     * @var string
     */
    protected $class    = '';

    /**
     * Konstruktor
     * @param string $icon
     * @param string $descr
     * @param \fpcm\view\helper\helper $field
     * @param type $class
     * @return bool
     */
    public function __construct($icon, $descr, $field, $class = 'col-sm-12 col-md-8')
    {
        $iconClass = is_array($icon) && isset($icon['icon']) ? $icon['icon'] : $icon;
        $useFa = is_array($icon) && isset($icon['usefa']) ? $icon['usefa'] : true;
        $prefix = is_array($icon) && isset($icon['prefix']) ? $icon['prefix'] : 'fas';
        
        $this->icon = (string) (new \fpcm\view\helper\icon($iconClass, $prefix, $useFa))->setSize('lg');
        $this->descr = \fpcm\classes\loader::getObject('fpcm\classes\language')->translate($descr);
        $this->class = $class;
        
        if (! $field instanceof \fpcm\view\helper\helper) {
            trigger_error('$field must be an object of instance \fpcm\view\helper\helper');
            return false;
        }

        $this->field = (string) $field;
    }

    /**
     * 
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return implode('', [
            "<div class=\"row fpcm-ui-padding-md-tb\">",
            "<label class=\"col-12 col-md-4 fpcm-ui-field-label-general\">{$this->icon} {$this->descr}</label>",
            "<div class=\"col-12 col-sm-auto fpcm-ui-padding-none-lr align-self-center {$this->class}\">{$this->field}</div>",
            "</div>"
        ]);
    }

}
