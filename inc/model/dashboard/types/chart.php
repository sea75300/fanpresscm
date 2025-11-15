<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\types;

/**
 * Dataview type dashboard trait
 *
 * @package fpcm\model\traits\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a1
 */
abstract class chart extends \fpcm\model\abstracts\dashcontainer {

    /**
     * Chart container
     * @var \fpcm\components\charts\chart
     */
    protected \fpcm\components\charts\chart $chart;

    /**
     * Returns chart name
     * @return string
     */
    abstract protected function getChartName() : string;

    /**
     * Returns chart type
     * @return string
     */
    abstract protected function getChartType() : string;

    /**
     * Returns container JS script file
     * @return string
     */
    protected function getContainerScript() : string
    {
        return '';
    }

    /**
     * Return rendered content
     * @return string
     */
    final public function getContent()
    {
        if (!$this->chart instanceof \fpcm\components\charts\chart) {
            $this->initChartInstance();
        }

        return sprintf(
            '<div class="row align-self-center align-content-center justify-content-center"><div class="col">%s</div></div>',
            $this->chart
        );
    }

    /**
     * Returns Javascript files
     * @return array
     */
    final public function getJavascriptFiles() : array
    {
        if (!$this->chart instanceof \fpcm\components\charts\chart) {
            $this->initChartInstance();
        }

        $files = $this->chart->getJsFiles();
        $files[1] = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_JS, $files[1]);

        $csf = $this->getContainerScript();
        if ($csf) {

            $modkey = \fpcm\module\module::getKeyFromClass(static::class);
            if ($modkey !== false) {
                $files[] = \fpcm\classes\dirs::getDataUrl(
                    \fpcm\classes\dirs::DATA_MODULES,
                    sprintf('%s/js/%s',
                        $modkey,
                        $csf
                    )
                );
            }
            else {
                $files[] = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_JS, $csf);
            }
        }

        return $files;
    }

    /**
     * Init chart object
     * @return void
     */
    final protected function initChartInstance() : void
    {
        $this->chart = new \fpcm\components\charts\chart(
            $this->getChartType(),
            $this->getChartName()
        );
    }
}
