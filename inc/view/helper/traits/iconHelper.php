<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * View helper with Icon
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait iconHelper {

    /**
     * Icon class
     * @var string
     */
    protected $icon = '';

    /**
     * Show icon only
     * @var bool
     */
    protected $iconOnly = false;

    /**
     * Button text
     * @var string
     */
    protected $iconStack = '';

    /**
     * Move stack icon to top
     * @var bool
     */
    protected $stackTop = false;

    /**
     * Button text
     * @var string
     */
    protected $size = '';

    /**
     * Spinner class for icon
     * @var string
     * @since 4.2
     */
    protected $spinner = '';

    /**
     * Set icon
     * @param string $icon Icon CSS classes
     * @param string $prefix Icon prefix
     * @param bool $useFa Auto-add FontAwesome Icon classes
     * @return $this
     */
    public function setIcon($icon, $prefix = 'fa', $useFa = true)
    {
        $this->icon = ($useFa ? $prefix.' fa-fw fa-' . $icon : $icon);
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
     * @param bool $useFa Auto-add FontAwesome Icon classes
     * @return $this
     */
    public function setStack($iconStack, $useFa = true)
    {
        $this->iconStack = ($useFa ? 'fa-' . $iconStack : $iconStack);
        return $this;
    }
    
    /**
     * Set Icon size
     * @param string $size
     * @param bool $useFa Auto-add FontAwesome Icon classes
     * @return $this
     */
    public function setSize($size, $useFa = true)
    {
        $this->size = ($useFa ? 'fa-' . $size : $size);
        return $this;
    }

    /**
     * Move stack icon to top
     * @param bool $stackTop
     * @return $this
     */
    public function setStackTop($stackTop)
    {
        $this->stackTop = (bool) $stackTop;
        return $this;
    }

    /**
     * Set flag icon has spinner
     * @param string $spinner
     * @param bool $useFa
     * @return $this
     */
    public function setSpinner($spinner, bool $useFa = true)
    {
        $this->spinner = ($useFa ? 'fa-' . $spinner : $spinner);
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

        return "<span class=\"fpcm-ui-icon {$this->icon} {$this->size} {$this->spinner}\"></span> ";
    }

}

?>