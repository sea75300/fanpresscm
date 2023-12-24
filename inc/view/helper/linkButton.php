<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Link button view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class linkButton extends button {
    
    use traits\urlHelper;
    
    const TARGET_NEW = '_blank';

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
        $this->class = sprintf('btn btn-%s shadow-sm fpcm-ui-button fpcm-ui-button-link', $this->getColorMode());
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
        $this->text = $this->language->translate($this->text);
        
        if ($this->primary) {
            $this->overrideButtonType('primary');
        }

        $icon = trim($this->getIconString());
        
        if ($this->readonly) {
            
            $this->class .= ' disabled';
            return implode(' ', [
                "<a href=\"#\"",
                "id=\"{$this->id}\"",
                $this->getClassString(),
                ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$icon}{$this->getDescriptionTextString()}"),
                '</a>'
            ]);
        }

        return implode(' ', [
            "<a href=\"{$this->url}\"",
            $this->getTargetString(),
            "id=\"{$this->id}\"",
            $this->getClassString(),
            $this->getRelString(),
            $this->getDataString(),
            ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$icon}{$this->getDescriptionTextString()}"),
            '</a>'
        ]);
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

    /**
     * Override bs button type
     * @param string $rel
     * @return $this
     * @since 5.0.0-b3
     */
    public function overrideButtonType($type)
    {
        if ($type === 'link') {
            $this->class = str_replace('shadow-sm', 'shadow-none', $this->class);
        }
        
        return parent::overrideButtonType($type);
    }


}