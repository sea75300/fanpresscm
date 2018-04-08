<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Set cron execution interval
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class croninterval extends \fpcm\controller\abstracts\ajaxController {

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'crons'];
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $cronjobId = $this->getRequestVar('cjId');
        $interval = $this->getRequestVar('interval');

        if (!$cronjobId || $interval === null) {
            return false;
        }

        $cjClassName = \fpcm\model\abstracts\cron::getCronNamespace($cronjobId);

        /* @var $cronjob \fpcm\model\abstracts\cron */
        $cronjob = new $cjClassName($cronjobId);

        if (!is_a($cronjob, '\fpcm\model\abstracts\cron')) {
            trigger_error("Cronjob class {$cronjobId} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }

        $cronjob->setExecinterval($interval);
        $cronjob->update();

        return true;
    }

}

?>