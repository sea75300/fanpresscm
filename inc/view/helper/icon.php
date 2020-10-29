<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Icon view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class icon extends helper {

    use traits\iconHelper;

    /**
     * Konstruktor
     * @param string $icon
     * @param string $prefix
     * @param bool $useFa
     */
    public function __construct($icon, ?string $prefix = null, ?bool $useFa = null)
    {
        if ($prefix === null) {
            $prefix = 'fa';
        }

        if ($useFa === null) {
            $useFa = true;
        }
        
        $this->setIcon($icon, $prefix, $useFa);
        parent::__construct(uniqid());
    }

    /**
     * Return text set on icon
     * @return string
     * @since 4.5
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if ($this->iconStack) {
            
            $stack = "<span class=\"fa {$this->iconStack} fa-stack-2x\"></span>";
            
            return implode(PHP_EOL, [
                "<span class=\"{$this->class} fa-stack {$this->size}\"" . ($this->text ? " title=\"{$this->text}\"" : '') . " {$this->getDataString()}>",
                !$this->stackTop ? $stack : '',
                "<span class=\"fpcm-ui-icon {$this->icon} {$this->spinner} fa-stack-1x\"></span>",
                $this->stackTop ? $stack : '',
                "</span>",
            ]);
        }

        return "<span class=\"fpcm-ui-icon {$this->class} {$this->icon} {$this->size} {$this->spinner}\"" . ($this->text ? " title=\"{$this->text}\"" : '') . "{$this->getDataString()}></span> ";
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-icon-single';
    }

    /**
     * Parse language variable icon
     * @param string $langvar
     * @return bool1
     * @since 4.5
     */
    final public static function parseLangvarIcon(&$langvar) : bool
    {
        if (!is_string($langvar)) {
            return false;
        }
        
        $regEx = '/\{{2}(icon\=\"[\w\-]+\"){1}\ ?(spinner\=\"[a-z0-9]*\")?\ ?(prefix\=\"[\w\-]*\")?\ ?(fa\=\"(true|false)\")?\}{2}/i';
        if (preg_match($regEx, $langvar, $matches) !== 1) {
            return false;
        }

        $iconStr = array_shift($matches);
        parse_str(implode('&', str_replace('"', '', $matches)), $arr);

        $icon = new static (
            $arr['icon'],
            $arr['prefix'] ?? null,
            (isset($arr['fa']) ? (bool) $arr['fa'] : null)
        );

        if (isset($arr['spinner'])) {
            $icon->setSpinner($arr['spinner']);
        }

        $langvar = str_replace($iconStr, (string) $icon, $langvar);
        
        return true;
    }

}

?>