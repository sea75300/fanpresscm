<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Cronjob manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class crons extends \fpcm\controller\abstracts\controller
{

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
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->crons;
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_CRONJOBS';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->items = (new \fpcm\model\crons\cronlist())->getAllCrons();

        $this->intervals = $this->language->translate('SYSTEM_OPTIONS_CRONINTERVALS');
        $this->currentTime = time();

        $this->initDataView();

        $this->view->addJsFiles(['system/crons.js']);
        $this->view->addJsLangVars(['CRONJOB_ECEDUTING']);
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
            (new \fpcm\components\dataView\column('interval', 'CRONJOB_LIST_INTERVAL'))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'CRONJOB_LIST_NAME'))->setSize(4),
            (new \fpcm\components\dataView\column('lastexec', 'CRONJOB_LIST_LASTEXEC'))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('nextecec', 'CRONJOB_LIST_NEXTEXEC'))->setSize(2)->setAlign('center'),
        ];
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setText('HL_CRONJOBS')
                ->setFile('components/dataview__inline.php')
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
        $modules = (new \fpcm\module\modules)->getEnabledDatabase();

        if ($cronjob->isRunning()) {
            $processingIcon = 'spinner fa-spin-pulse text-danger';
            $playClass = '';
            $rowClass = '';
            $btnReadonly = true;
        }
        elseif ( $cronjob->getModuleKey() && !in_array($cronjob->getModuleKey(), $modules) ) {
            $btnReadonly = true;
            $processingIcon = 'play-circle';
            $rowClass = 'text-secondary';
            $playClass = '';
        }
        else {
            $processingIcon = 'play-circle';
            $playClass = 'fpcm-cronjoblist-exec';
            $rowClass = '';
            $btnReadonly = false;
        }
        
        $nextExecTs = $cronjob->getNextExecTime();
        
        return new \fpcm\components\dataView\row([
            
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\button($cronjob->getCronName()))->setText('CRONJOB_LIST_EXECDEMAND')->setClass($playClass)->setIcon($processingIcon)->setIconOnly()->setReadonly($btnReadonly)->setData([
                'cjid' => $cronjob->getCronName(),
                'cjdescr' => $this->language->translate($cronjob->getCronNameLangVar()),
                'cjmod' => $cronjob->getModuleKey()
            ]), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('interval',
                (new \fpcm\view\helper\select('intervals_' . $cronjob->getCronName()))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setOptions($this->intervals)
                    ->setReadonly($btnReadonly)
                    ->setSelected($cronjob->getIntervalTime())
                    ->setClass('fpcm-cronjoblist-intervals')
                    ->setData([
                        'cjmod' => $cronjob->getModuleKey()
                    ])
            ),
            new \fpcm\components\dataView\rowCol('name', $this->language->translate($cronjob->getCronNameLangVar())),
            new \fpcm\components\dataView\rowCol('lastexec', new \fpcm\view\helper\dateText($cronjob->getLastExecTime())),
            new \fpcm\components\dataView\rowCol('nextecec', $nextExecTs ? new \fpcm\view\helper\dateText( $nextExecTs ) : '-')
        ], $rowClass);
    }

}
