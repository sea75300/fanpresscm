<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * View helper with Icon
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait iconHelper {

    /**
     * Input icon
     * @var string
     */
    protected $icon = '';

    /**
     * Button text
     * @var string
     */
    protected $iconOnly = false;

    /**
     * Button text
     * @var string
     */
    protected $iconStack = '';

    /**
     * Button text
     * @var string
     */
    protected $size = '';

    /**
     * Set icon
     * @param string $icon Icon CSS classes
     * @param bool $useFa Auto-add FontAwesome Icon classes
     * @return $this
     */
    public function setIcon($icon, $useFa = true)
    {
        $this->icon = ($useFa ? 'fa fa-fw fa-' . $icon : $icon);
        return $this;
    }

    /**
     * Set button to display icon only
     * @param string $iconOnly
     * @return $this
     */
    public function setIconOnly($iconOnly)
    {
        $this->iconOnly = (bool) $iconOnly;
        return $this;
    }

    /**
     * Set if icon is stacked
     * @param string $iconStack
     * @return $this
     */
    public function setStack($iconStack, $useFa = true)
    {
        $this->iconStack = ($useFa ? 'fa-' . $iconStack : $iconStack);
        return $this;
    }

    /**
     * 
     * @param string $size
     * @return $this
     */
    
    /**
     * Set Icon size
     * @param string $size
     * @param bool $useFa
     * @return $this
     */
    public function setSize($size, $useFa = true)
    {
        $this->size = ($size ? 'fa-' . $size : $size);
        return $this;
    }

    /**
     * Return full icon string
     * @return string
     */
    protected function getIconString()
    {
        if (!trim($this->icon)) {
            return '';
        }

        return "<span class=\"fpcm-ui-icon {$this->icon} {$this->size}\"></span> ";
        ;
    }

}

?>