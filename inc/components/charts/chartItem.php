<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\charts;

/**
 * Chart.js dataset item
 * 
 * @package fpcm\components\charts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
 */
class chartItem {

    /**
     * Item Label
     * @var string
     */
    private $label = '';

    /**
     * Fill items
     * @var bool
     */
    private $fill = false;

    /**
     * Item data
     * @var array
     */
    private $data = [];

    /**
     * Items background colors
     * @var array
     */
    private $backgroundColor = [];

    /**
     * Items border colors
     * @var string
     */
    private $borderColor = '#000';

    /**
     * Constructor
     * @param array $data
     * @param array $backgroundColor
     */
    final public function __construct(array $data, array $backgroundColor)
    {
        $this->data = $data;
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * Set Item label
     * @param type $label
     * @return $this
     */
    final public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set fill flag
     * @param bool $fill
     * @return $this
     */
    final public function setFill(bool $fill)
    {
        $this->fill = $fill;
        return $this;
    }

    /**
     * Set border color
     * @param string $borderColor
     * @return $this
     */
    final public function setBorderColor(string $borderColor)
    {
        $this->borderColor = $borderColor;
        return $this;
    }

    /**
     * Assigns data from chart item
     * @param array $data
     * @param int $index
     * @return $this
     */
    final public function assignData(array &$data, int $index = 0)
    {
        if (!isset($data['datasets']) || !is_array($data['datasets'])) {
            $data['datasets'] = [];
        }
        
        $data['datasets'][$index] = [
            'label'             => $this->label,
            'fill'              => $this->fill,
            'data'              => $this->data,
            'backgroundColor'   => $this->backgroundColor,
            'borderColor'       => $this->borderColor
        ];

        return $this;
    }

    /**
     * Fetch random color hex code
     * @return string
     */
    final public static function getRandomColor() : string
    {
        return 'rgba('.mt_rand(0, 255).', '.mt_rand(0, 255).', '.mt_rand(0, 255).', 0.75)';
    }

}
