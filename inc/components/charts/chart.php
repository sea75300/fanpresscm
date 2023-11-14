<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\charts;

/**
 * Chart.js component
 * 
 * @package fpcm\components\charts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2019-2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
 */
class chart
implements \JsonSerializable, \Stringable, \fpcm\model\interfaces\JsModuleFiles {
    
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';
    const TYPE_PIE = 'pie';
    const TYPE_DOUGHNUT = 'doughnut';
    const TYPE_POLAR = 'polarArea';
    
    /* @since 5.0.0-a1 */
    const TYPE_BUBBLE = 'bubble';
    
    /* @since 5.0.0-a1 */
    const TYPE_SCATTER = 'scatter';

    /* @since 5.0.0-a1 */
    const TYPE_RADAR = 'radar';

    /**
     * Chart id
     * @var string
     */
    protected $id = '';

    /**
     * Charts typ
     * @var string
     */
    protected $type = '';

    /**
     * Chart data
     * @var array
     */
    protected $data = [];

    /**
     * Chart data
     * @var array
     */
    protected $options = [];

    /**
     * Chart scales
     * @var array
     * @since 5.0.0-a1
     */
    protected $scales = [
        'x' => [
            'beginAtZero' => true
        ],
        'y' => [
            'beginAtZero' => true
        ],
    ];
    
    /**
     * Constructor
     * @param string $type Chart.js chart type
     * @param string $id Chart id
     */
    public function __construct(string $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
        
        $this->setLegend([
            'display' => !in_array($type, [self::TYPE_BAR, self::TYPE_LINE]),
            'position' => 'right',
            'labels' => [
                'boxWidth' => 25,
                'fontSize' => 12
            ]
        ]);

        $this->addOptions('elements', [
            'line' => [
                'borderWidth' => 5
            ],
            'bar' => [
                'borderWidth' => 0
            ],
            'arc' => [
                'borderWidth' => 0
            ]
        ]);
    }

    /**
     * Returns list of JS files
     * @return array
     */
    public function getJsFiles() : array
    {
        return [
            \fpcm\classes\dirs::getLibUrl('chart-js/chart.umd.js'),
            'ui/chart.js'
        ];
    }

    /**
     * Returns list of JS files
     * @return array
     */
    public function getJsModuleFiles() : array
    {
        return [];
    }

    /**
     * Returns list of CSS files
     * @return array
     */
    public function getCssFiles() : array
    {
        return [];
    }

    /**
     * Adds label to data
     * @param array $labels
     * @return $this
     */
    public function setLabels(array $labels)
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
    public function setValues(chartItem $item, int $index = 0)
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
    public function addOptions(string $var, $value)
    {
        $this->options[$var] = $value;
        return $this;
    }
    
    /**
     * Set chart legend config
     * @param string $var
     * @param mixed $value
     * @return $this
     * @see https://www.chartjs.org/docs/latest/configuration/
     * @since 5.0.0-a1
     */
    final public function setLegend(array $value)
    {
        $this->options['plugins']['legend'] = $value;
        return $this;
    }

    /**
     * Set charts cales
     * @param array $scales
     * @return $this
     * @see https://www.chartjs.org/docs/latest/configuration/
     * @since 5.0.0-a1
     */
    public function setScales(array $scales)
    {
        $this->scales = $scales;
        return $this;
    }

    /**
     * Returns Data for json_encode
     * @return array
     * @ignore
     */
    final public function jsonSerialize(): mixed
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
