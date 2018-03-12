<?php

/**
 * Cronjob manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class crons extends \fpcm\controller\abstracts\controller {

    protected function getPermissions()
    {
        return ['system' => 'crons'];
    }

    protected function getViewPath()
    {
        return 'system/cronjobs';
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $cronlist = new \fpcm\model\crons\cronlist();

        $dataView = new \fpcm\components\dataView\dataView('cronlist');
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'CRONJOB_LIST_NAME'))->setSize(3),
            (new \fpcm\components\dataView\column('interval', 'CRONJOB_LIST_INTERVAL'))->setSize(3),
            (new \fpcm\components\dataView\column('lastexec', 'CRONJOB_LIST_LASTEXEC'))->setAlign('center'),
            (new \fpcm\components\dataView\column('nextecec', 'CRONJOB_LIST_NEXTEXEC'))->setAlign('center'),
        ]);

        $intervals = $this->lang->translate('SYSTEM_OPTIONS_CRONINTERVALS');
        $currentTime = time();

        /* @var $cronjob \fpcm\model\abstracts\cron */
        foreach ($cronlist->getCronsData() as $cronjob) {
            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\button($cronjob->getCronName(), $cronjob->getCronName() ) )->setText('CRONJOB_LIST_EXECDEMAND')->setClass('fpcm-cronjoblist-exec')->setIcon('play-circle')->setIconOnly(true), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('name', $this->lang->translate($cronjob->getCronNameLangVar()) ),
                    new \fpcm\components\dataView\rowCol('interval', (new \fpcm\view\helper\select('intervals_'.$cronjob->getCronName()))->setOptions($intervals)->setSelected($cronjob->getIntervalTime())->setClass('fpcm-cronjoblist-intervals') ),
                    new \fpcm\components\dataView\rowCol('lastexec', new \fpcm\view\helper\dateText($cronjob->getLastExecTime()) ),
                    new \fpcm\components\dataView\rowCol('nextecec', new \fpcm\view\helper\dateText($cronjob->getNextExecTime()) )
                ],
                $currentTime > ($cronjob->getNextExecTime() - 60) ? 'fpcm-ui-important-text' : ''
            ));

        }

        $this->view->addDataView($dataView);
        $this->view->addJsFiles(['crons.js']);
        $this->view->render();
    }

}

?>
