<?php

/**
 * Cronjob manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class crons extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\dataView;

    /**
     *
     * @var int
     */
    private $currentTime = 0;

    /**
     *
     * @var array
     */
    private $intervals = [];

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'crons'];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return 'components/dataview';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_options';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->items = (new \fpcm\model\crons\cronlist())->getCronsData();

        $this->intervals = $this->lang->translate('SYSTEM_OPTIONS_CRONINTERVALS');
        $this->currentTime = time();

        $this->initDataView();
        
        $this->view->assign('headline', 'HL_CRONJOBS');
        $this->view->addJsFiles(['crons.js']);
        $this->view->render();
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'CRONJOB_LIST_NAME'))->setSize(3),
            (new \fpcm\components\dataView\column('interval', 'CRONJOB_LIST_INTERVAL'))->setSize(3),
            (new \fpcm\components\dataView\column('lastexec', 'CRONJOB_LIST_LASTEXEC'))->setAlign('center'),
            (new \fpcm\components\dataView\column('nextecec', 'CRONJOB_LIST_NEXTEXEC'))->setAlign('center'),
        ];
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'cronlist';
    }

    /**
     * 
     * @param \fpcm\model\abstracts\cron $cronjob
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($cronjob)
    {
        return new \fpcm\components\dataView\row([
                new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\button($cronjob->getCronName(), $cronjob->getCronName() ) )->setText('CRONJOB_LIST_EXECDEMAND')->setClass('fpcm-cronjoblist-exec')->setIcon('play-circle')->setIconOnly(true), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('name', $this->lang->translate($cronjob->getCronNameLangVar()) ),
                new \fpcm\components\dataView\rowCol('interval', (new \fpcm\view\helper\select('intervals_'.$cronjob->getCronName()))->setOptions($this->intervals)->setSelected($cronjob->getIntervalTime())->setClass('fpcm-cronjoblist-intervals') ),
                new \fpcm\components\dataView\rowCol('lastexec', new \fpcm\view\helper\dateText($cronjob->getLastExecTime()) ),
                new \fpcm\components\dataView\rowCol('nextecec', new \fpcm\view\helper\dateText($cronjob->getNextExecTime()) )
            ],
            $this->currentTime > ($cronjob->getNextExecTime() - 60) ? 'fpcm-ui-important-text' : ''
        );
    }

    
}

?>
