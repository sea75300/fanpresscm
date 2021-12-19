<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components;

/**
 * Field group component for checkboxers and radio button
 * 
 * @package fpcm\components
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 5.0-dev
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class fieldGroup {

    /**
     * Fields object
     * @var array(\fpcm\view\helper\helper)
     */
    protected $fields = null;

    /**
     * 
     * @var string
     */
    protected $descr;

    /**
     * 
     * @var \fpcm\view\helper\icon
     */
    protected $icon;

    /**
     * Constructor
     * @param \fpcm\view\helper\helper $field
     * @param string $class
     * @return void
     */
    public function __construct(array $fields, string $descr, ?\fpcm\view\helper\icon $icon = null)
    {
        $this->fields = array_filter($fields, function($field) {
            return $field instanceof \fpcm\view\helper\radiocheck;
        });

        $this->descr = $descr;
        $this->icon = $icon;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getDescr(): string
    {
        return $this->descr;
    }

    public function getIcon(): ?\fpcm\view\helper\icon
    {
        return $this->icon;
    }


}
