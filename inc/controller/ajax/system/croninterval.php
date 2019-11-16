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
        $cronName = $this->getRequestVar('cjId');
        $interval = $this->getRequestVar('interval');
        $module = $this->getRequestVar('cjmod');

        if (!$cronName || $interval === null) {
            return false;
        }

        if (trim($module)) {
            
            if (!(new \fpcm\module\module($module))->isActive()) {
                trigger_error("Undefined cronjon {$cronName} called");
                return false;
            }

            $cronName = \fpcm\module\module::getCronNamespace($module, $cronName);
        }
        else {
            $cronName = \fpcm\model\abstracts\cron::getCronNamespace($cronName);
        }

        /* @var $cronjob \fpcm\model\abstracts\cron */
        $cronjob = new $cronName();

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