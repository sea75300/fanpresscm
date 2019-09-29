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
 * @since FPCM 4.2.2
 */
trait labelFieldSize {

    /**
     * Label size string
     * @var string
     */
    protected $labelSize = '';

    /**
     * Input field size
     * @var string
     */
    protected $fieldSize = '';

    /**
     * Size map
     * @var array
     */
    private $sizeMap = [
        0 => 'col-',
        1 => 'col-sm-',
        2 => 'col-md-',
        3 => 'col-lg-',
        4 => 'col-xl-',
        'xs' => 'col-',
        'sm' => 'col-sm-',
        'md' => 'col-md-',
        'lg' => 'col-lg-',
        'xl' => 'col-xl-',
    ];

    public function getLabelSize()
    {
        return $this->labelSize;
    }

    public function getFieldSize()
    {
        return $this->fieldSize;
    }

    public function setDisplaySizesDefault()
    {
        return $this->setDisplaySizes(
            ['xs' => 12, 'sm' => 6, 'md' => 5],
            ['xs' => 12, 'sm' => 6, 'md' => 7]
        );
    }

    public function setDisplaySizes(array $label, array $field)
    {
        $this->setLabelSize($label);
        $this->setFieldSize($field);
        return $this;
    }

    public function setLabelSize(array $labelSizes)
    {
        array_walk($labelSizes, [$this, 'mapSizes']);
        $this->labelSize = ' '.implode(' ', $labelSizes);
        return $this;
    }

    public function setFieldSize(array $fieldSizes)
    {
        array_walk($fieldSizes, [$this, 'mapSizes']);
        $this->fieldSize = ' '.implode(' ', $fieldSizes);
        return $this;
    }

    private function mapSizes(&$size, $index)
    {
        if (!isset($this->sizeMap[$index])) {
            $size = '';
            return false;
        }

        $size = $this->sizeMap[$index].$size;
        return true;
    }

}

?>