<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\charts;

/**
 * Chart.js component
 * 
 * @package fpcm\components\charts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
 */
class chart implements \JsonSerializable {
    
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';
    const TYPE_PIE = 'pie';
    const TYPE_DOUGHNUT = 'doughnut';
    const TYPE_POLAR = 'polarArea';

    /**
     * Chart id
     * @var string
     */
    private $id = '';

    /**
     * Charts typ
     * @var string
     */
    private $type = '';

    /**
     * Chart data
     * @var array
     */
    private $data = [];

    /**
     * Chart data
     * @var array
     */
    private $options = [];
    
    /**
     * Constructor
     * @param string $type Chart.js chart type
     * @param string $id Chart id
     */
    public function __construct(string $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Returns list of JS files
     * @return array
     */
    final public function getJsFiles() : array
    {
        return [
            \fpcm\classes\dirs::getLibUrl('chart-js/chart.min.js'),
            'ui/chart.js'
        ];
    }

    /**
     * Returns list of CSS files
     * @return array
     */
    final public function getCssFiles() : array
    {
        return [\fpcm\classes\dirs::getLibUrl('chart-js/Chart.min.css')];
    }

    /**
     * Adds label to data
     * @param array $labels
     * @return $this
     */
    final public function setLabels(array $labels)
    {
        $this->data['labels'] = $labels;
        return $this;
    }
    
    /**
     * Sets chart values
     * @param \fpcm\components\charts\chartItem $item
     * @param int $index
     * @return $this
     */
    final public function setValues(chartItem $item, int $index = 0)
    {
        $item->assignData($this->data, $index);
        return $this;
    }

    
    /**
     * Add chart config option
     * @param string $var
     * @param mixed $value
     * @return $this
     * @see https://www.chartjs.org/docs/latest/configuration/
     */
    final public function addOptions(string $var, $value)
    {
        $this->options[$var] = $value;
        return $this;
    }

    /**
     * Returns Data for json_encode
     * @return array
     * @ignore
     */
    final public function jsonSerialize()
    {
        if (!isset($this->options['responsive'])) {
            $this->options['responsive'] = true;
        }

        return get_object_vars($this);
    }

    /**
     * Magic __toString
     * @return string
     * @ignore
     */
    final public function __toString() : string
    {
        return "<canvas id=\"{$this->id}\"></canvas>";
    }

}
