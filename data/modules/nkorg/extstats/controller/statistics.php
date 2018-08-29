<?php

namespace fpcm\modules\nkorg\extstats\controller;

final class statistics extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\modules\tools;
    
    protected function getViewPath(): string
    {
        return 'index';
    }

    public function request() : bool
    {
        return true;
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
            $this->addLangVarPrefix('BYDAY')   => \fpcm\modules\nkorg\extstats\models\counter::MODE_DAY
        ];

        $start     = \fpcm\classes\http::postOnly('dateFrom');
        $stop      = \fpcm\classes\http::postOnly('dateTo');
        $chartType = \fpcm\classes\http::postOnly('chartType');
        $chartMode = \fpcm\classes\http::postOnly('chartMode', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $modeStr =  $chartMode === \fpcm\modules\nkorg\extstats\models\counter::MODE_YEAR ? 'YEAR'
                 : ($chartMode === \fpcm\modules\nkorg\extstats\models\counter::MODE_DAY
                 ? 'DAY' : 'MONTH' );

        $this->view->assign('modeStr', $modeStr);
        $this->view->assign('start', trim($start) ? $start : '');
        $this->view->assign('stop', trim($stop) ? $stop : '');

        if (!trim($chartType)) {
            $chartType = 'bar';
        }

        if (!trim($chartMode)) {
            $chartMode = \fpcm\modules\nkorg\extstats\models\counter::MODE_MONTH;
        }
        
        $this->view->addButtons([
            (new \fpcm\view\helper\select('chartMode'))->setClass('fpcm-ui-input-select-articleactions')->setOptions($chartModes)->setSelected($chartMode),
            (new \fpcm\view\helper\select('chartType'))->setClass('fpcm-ui-input-select-articleactions')->setOptions($chartTypes)->setSelected($chartType),
            (new \fpcm\view\helper\submitButton('setdatespan'))->setText('GLOBAL_OK')
        ]);
        
        $counter = new \fpcm\modules\nkorg\extstats\models\counter();
        $articleList = new \fpcm\model\articles\articlelist();
        $minMax      = $articleList->getMinMaxDate();

        $this->view->addJsVars([
            'extStats' => [
                'chartValues' => $counter->fetchArticles($start, $stop, $chartMode),
                'chartType' => trim($chartType) ? $chartType : 'bar',
                'minDate' => date('Y-m-d', $minMax['minDate'])
            ]
        ]);

        $this->view->addJsFiles([
            \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $key.'/js/chart.min.js'),
            \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $key.'/js/module.js')
        ]);
        
        $this->view->setFormAction('extstats/statistics');
        $this->view->render();
        return true;
    }

}