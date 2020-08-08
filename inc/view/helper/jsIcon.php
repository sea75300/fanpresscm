<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Icon dummy helper for JavsScript use
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4-5
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
    }

    /**
     * Return text set on icon
     * @return string
     * @since FPCM 4.5
     */
    public function getText() : string
    {
        return '{{text}}';
    }

    /**
     * Returns array for object
     * @return array
     */
    public function jsonSerialize() : array
    {
        $return = [];
        
        $return['unstacked'] = $this->getString();
        
        $this->setSpinner('{{spinner}}');
        $return['unstackedSpinner'] = $this->getString();
        
        $this->setStack('{{stack}}');
        $this->setSpinner('');
        $return['stacked'] = $this->getString();
        
        $this->setStack('{{stack}}');
        $this->setSpinner('{{spinner}}');
        $return['stackedSpinner'] = $this->getString();
        
        $this->setStack('{{}stack}');
        $this->setStackTop(true);
        $this->setSpinner('');
        $return['stackedTop'] = $this->getString();
        
        $this->setStack('{{}stack}');
        $this->setStackTop(true);
        $this->setSpinner('{{spinner}}');
        $return['stackedTopSpinner'] = $this->getString();
        
        return $return;
    }

    

}

?>