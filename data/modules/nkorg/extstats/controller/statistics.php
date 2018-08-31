<?php

namespace fpcm\modules\nkorg\extstats\controller;

final class statistics extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\modules\tools;

    protected function getViewPath(): string
    {
        return 'index';
    }

    public function process()
    {
        $key = \fpcm\module\module::getKeyFromPath(__FILE__);

        $chartTypes = [
            $this->addLangVarPrefix('TYPEBAR') => 'bar',
            $this->addLangVarPrefix('TYPELINE') => 'line',
            $this->addLangVarPrefix('TYPEPIE') => 'pie',
            $this->addLangVarPrefix('TYPEDOUGHNUT') => 'doughnut',
            $this->addLangVarPrefix('TYPEPOLAR') => 'polarArea',
        ];

        $chartModes = [
            $this->addLangVarPrefix('BYYEAR') => \fpcm\modules\nkorg\extstats\models\counter::MODE_YEAR,
            $this->addLangVarPrefix('BYMONTH') => \fpcm\modules\nkorg\extstats\models\counter::MODE_MONTH,
            $this->addLangVarPrefix('BYDAY') => \fpcm\modules\nkorg\extstats\models\counter::MODE_DAY
        ];

        $dataSource = [
            $this->addLangVarPrefix('FROMARTICLES') => \fpcm\modules\nkorg\extstats\models\counter::SRC_ARTICLES,
            $this->addLangVarPrefix('FROMSHARES') => \fpcm\modules\nkorg\extstats\models\counter::SRC_SHARES,
            $this->addLangVarPrefix('FROMCOMMENTS') => \fpcm\modules\nkorg\extstats\models\counter::SRC_COMMENTS,
            $this->addLangVarPrefix('FROMFILES') => \fpcm\modules\nkorg\extstats\models\counter::SRC_FILES
        ];

        $source = \fpcm\classes\http::postOnly('source');
        if (!trim($source)) {
            $source = \fpcm\modules\nkorg\extstats\models\counter::SRC_ARTICLES;
        }

        $chartType = \fpcm\classes\http::postOnly('chartType');
        if (!trim($chartType)) {
            $chartType = 'bar';
        }

        $chartMode = \fpcm\classes\http::postOnly('chartMode', [\fpcm\classes\http::FILTER_CASTINT]);
        if (!trim($chartMode)) {
            $chartMode = \fpcm\modules\nkorg\extstats\models\counter::MODE_MONTH;
        }

        $modeStr = $chartMode === \fpcm\modules\nkorg\extstats\models\counter::MODE_YEAR ? 'YEAR' : ($chartMode === \fpcm\modules\nkorg\extstats\models\counter::MODE_DAY ? 'DAY' : 'MONTH' );

        $start = \fpcm\classes\http::postOnly('dateFrom');
        $stop = \fpcm\classes\http::postOnly('dateTo');

        $this->view->assign('modeStr', $source !== \fpcm\modules\nkorg\extstats\models\counter::SRC_SHARES ? strtoupper($modeStr) : '');
        $this->view->assign('sourceStr', array_search($source, $dataSource));
        $this->view->assign('start', trim($start) ? $start : '');
        $this->view->assign('stop', trim($stop) ? $stop : '');

        $this->view->addButtons([
            (new \fpcm\view\helper\select('source'))
                ->setClass('fpcm-ui-input-select-articleactions')
                ->setOptions($dataSource)->setSelected($source)
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),

            (new \fpcm\view\helper\select('chartMode'))
                ->setClass('fpcm-ui-input-select-articleactions ')
                ->setOptions($chartModes)->setSelected($chartMode)
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),

            (new \fpcm\view\helper\select('chartType'))
                ->setClass('fpcm-ui-input-select-articleactions')
                ->setOptions($chartTypes)->setSelected($chartType)
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),

            (new \fpcm\view\helper\submitButton('setdatespan'))
                ->setText('GLOBAL_OK')
        ]);

        $counter = new \fpcm\modules\nkorg\extstats\models\counter();
        $articleList = new \fpcm\model\articles\articlelist();
        $minMax = $articleList->getMinMaxDate();

        $fn = 'fetch' . ucfirst($source);
        if (!method_exists($counter, $fn)) {
            $this->view->render();
            return true;
        }

        $this->view->addJsVars([
            'extStats' => [
                'chartValues' => call_user_func([$counter, $fn], $start, $stop, $chartMode),
                'chartType' => trim($chartType) ? $chartType : 'bar',
                'minDate' => date('Y-m-d', $minMax['minDate']),
                'showMode' => $source === \fpcm\modules\nkorg\extstats\models\counter::SRC_SHARES ? false : true
            ]
        ]);

        $this->view->addJsFiles([
            \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $key . '/js/chart.min.js'),
            \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $key . '/js/module.js')
        ]);

        $this->view->setFormAction('extstats/statistics');
        $this->view->render();
        return true;
    }

}
