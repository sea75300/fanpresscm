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
     *
     * @var string
     */
    protected $icon     = '';

    /**
     *
     * @var string
     */
    protected $descr    = '';

    /**
     *
     * @var \fpcm\view\helper\helper
     */
    protected $field    = null;

    /**
     *
     * @var string
     */
    protected $class    = '';

    /**
     * Konstruktor
     * @param string $icon
     * @param string $descr
     * @param \fpcm\view\helper\helper $field
     * @param type $class
     * @return boolean
     */
    public function __construct($icon, $descr, $field, $class = 'col-sm-12 col-md-8')
    {
        $this->icon = (string) (new \fpcm\view\helper\icon($icon))->setSize('lg');
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
     */
    public function __toString()
    {
        return implode('', [
            "<div class=\"row fpcm-ui-padding-md-tb\">",
            "<div class=\"col-1 fpcm-ui-padding-none-lr align-self-center\">{$this->icon}</div>",
            "<div class=\"col-3 fpcm-ui-padding-none-lr align-self-center\">{$this->descr}</div>",
            "<div class=\"{$this->class} align-self-center\">{$this->field}</div>",
            "</div>"
        ]);
    }

}
