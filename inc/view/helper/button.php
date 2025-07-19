<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Button view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class button extends helper
implements interfaces\jsDialogHelper {

    use traits\iconHelper,
        traits\typeHelper,
        traits\setClickHelper;

    /*  @since 4.4.0 */
    const NAME_PREFIX = 'btn';

    /**
     * Is primary button
     * @var bool
     * @since 5.0-dev
     */
    protected $primary = false;

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if ($this->primary) {
            $this->overrideButtonType('primary');
        }
        
        $icon = trim($this->getIconString());
        
        return implode(' ', [
            "<button type=\"{$this->type}\" ",
            $this->getDataString(),
            $this->getAriaString(),
            $this->getReadonlyString(),
            $this->getNameIdString() . ' ' . $this->getClassString(),
            ($this->iconOnly ? "title=\"{$this->text}\">{$icon}" : ">{$icon}{$this->getDescriptionTextString()}"),
            "</button>"
        ]);
    }

    /**
     * Return class string
     * @return string
     */
    protected function getReadonlyString()
    {
        return $this->readonly ? "disabled" : '';
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->prefix = self::NAME_PREFIX;
        $this->class = sprintf('btn btn-%s shadow-sm fpcm ui-button', $this->getColorMode());
        $this->type = 'button';
    }
    
    /**
     * Set button to primary
     * @param bool $primary
     * @return $this
     * @since 5.0-dev
     */
    public function setPrimary(bool $primary = true)
    {
        $this->primary = $primary;
        return $this;
    }

    /**
     * Override bs button type
     * @param string $type
     * @return $this
     * @since 5.0.0-b3
     */
    public function overrideButtonType(string $type)
    {
        $this->class = preg_replace('/(btn-)(\w+\s{1})(.*)/i', '$1'.$type.' $3', $this->class);
        return $this;
    }
    
    /**
     * Set data attribute for ui confirm dialog
     * @return $this
     * @since 5.2.2-dev
     */
    final public function setClickConfirm()
    {
        $this->data['ui-confirm'] = true;
        return $this;
    }

}
