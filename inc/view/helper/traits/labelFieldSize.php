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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
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

    /**
     * Fetch label size classes
     * @return string
     */
    public function getLabelSize()
    {
        return $this->labelSize;
    }

    /**
     * Fetch field size classes
     * @return string
     */
    public function getFieldSize()
    {
        return $this->fieldSize;
    }

    /**
     * Sets default label and field sizes values,
     * Label: xs: 12, sm: 6, md: 5
     * Fields: xs: 12, sm: 6, md: 7
     * @return $this
     */
    public function setDisplaySizesDefault()
    {
        return $this->setDisplaySizes(
            ['xs' => 12, 'sm' => 6, 'md' => 3],
            ['xs' => 12, 'sm' => 6, 'md' => 9]
        );
    }

    /**
     * Sets label and field sizes
     * @param array $label
     * @param array $field
     * @return $this
     */
    public function setDisplaySizes(array $label, array $field)
    {
        $this->setLabelSize($label);
        $this->setFieldSize($field);
        return $this;
    }

    /**
     * Sets label sizes only
     * @param array $labelSizes
     * @return $this
     */
    public function setLabelSize(array $labelSizes)
    {
        array_walk($labelSizes, [$this, 'mapSizes']);
        $this->labelSize = ' '.implode(' ', $labelSizes);
        return $this;
    }

    /**
     * Sets field sizes only
     * @param array $fieldSizes
     * @return $this
     */
    public function setFieldSize(array $fieldSizes)
    {
        array_walk($fieldSizes, [$this, 'mapSizes']);
        $this->fieldSize = ' '.implode(' ', $fieldSizes);
        return $this;
    }

    /**
     * Maps indices to sizeMap
     * @param string|int $size
     * @param string|int $index
     * @return bool
     */
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