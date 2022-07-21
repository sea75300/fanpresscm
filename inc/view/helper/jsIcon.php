<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Icon dummy helper for JavsScript use
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4-5
 */
class jsIcon extends icon implements \JsonSerializable {

    /**
     * Konstruktor
     * @param string $icon
     * @param string $prefix
     * @param bool $useFa
     */
    final public function __construct($icon, $prefix = 'fa', $useFa = true)
    {
        parent::__construct('{{icon}}', '{{prefix}}', true);
        $this->setClass('{{class}}');
    }

    /**
     * Return text set on icon
     * @return string
     * @since 4.5
     */
    public function getText() : string
    {
        return '{{text}}';
    }

    /**
     * Returns array for object
     * @return array
     */
    public function jsonSerialize() : mixed
    {
        $this->returned = true;

        $return = [];
        $this->setSpinner('{{spinner}}');
        $this->setSize('{{size}}');
        
        $return['unstacked'] = $this->getString();

        $this->setStack('{{stack}}');
        $return['stacked'] = $this->getString();

        $this->setStackTop(true);
        $return['stackedTop'] = $this->getString();

        $return['defaultPrefix'] = 'fa';
        return $return;
    }

    

}

?>