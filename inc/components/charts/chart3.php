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
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0.0-a1
 */
class chart3 extends chart {

    const TYPE_BUBBLE = 'bubble';

    const TYPE_SCATTER = 'scatter';

    const TYPE_RADAR = 'radar';

    /**
     * Chart scales
     * @var array
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
        parent::__construct($type, $id);
        
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
            \fpcm\classes\dirs::getLibUrl('chart-js3/chart.min.js'),
            'ui/chart3.js'
        ];
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
     * Set chart legend config
     * @param string $var
     * @param mixed $value
     * @return $this
     * @see https://www.chartjs.org/docs/latest/configuration/
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
     */
    public function setScales(array $scales)
    {
        $this->scales = $scales;
        return $this;
    }

}
