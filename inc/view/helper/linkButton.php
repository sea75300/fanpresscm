<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Link button view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class linkButton extends button {
    
    use traits\urlHelper;

    /**
     * Link URL target
     * @var string
     */
    protected $target = '';

    /**
     * rel-Attribute
     * @var string
     * @since 4.1
     */
    protected $rel = '';

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'btn btn-light fpcm-ui-button fpcm-ui-button-link';
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     */
    protected function getNameIdString()
    {
        return "id=\"{$this->id}\" ";
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     * @since 4.1
     */
    protected function getRelString()
    {
        return trim($this->rel) ? "rel=\"{$this->rel}\" ": '';
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if ($this->readonly) {
            
            $this->class .= ' fpcm-ui-readonly';
            
            $this->class = str_replace('fpcm-loader', '', $this->class);
            return implode(' ', [
                "<a href=\"#\"",
                "id=\"{$this->id}\"",
                $this->getClassString(),
                ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$this->getIconString()}{$this->getDescriptionTextString()}"),
                '</a>'
            ]);
        }

        return implode(' ', [
            "<a href=\"{$this->url}\"",
            $this->target ? "target=\"{$this->target}\"" : '',
            "id=\"{$this->id}\"",
            $this->getClassString(),
            $this->getRelString(),
            $this->getDataString(),
            ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$this->getIconString()}{$this->getDescriptionTextString()}"),
            '</a>'
        ]);
    }

    /**
     * Set link target
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Set "rel" attribute value
     * @param string $rel
     * @return $this
     * @since 4.1
     */
    public function setRel(string $rel) {
        $this->rel = $rel;
        return $this;
    }

}