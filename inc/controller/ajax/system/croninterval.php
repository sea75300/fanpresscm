<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Set cron execution interval
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class croninterval extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->crons;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $cronName = $this->request->fromPOST('cjId');
        $interval = $this->request->fromPOST('interval', [\fpcm\model\http\request::FILTER_CASTINT ]);
        $module = $this->request->fromPOST('cjmod');

        if (!$cronName || $interval === null) {
            return false;
        }

        if (trim($module)) {
            
            if (!\fpcm\module\module::validateKey($module) || !(new \fpcm\module\module($module))->isActive()) {
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
        if (!$cronjob instanceof \fpcm\model\abstracts\cron) {
            trigger_error("Cronjob class {$cronjobId} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }

        $cronjob->setExecinterval($interval);
        $cronjob->update();

        return true;
    }

}

?>