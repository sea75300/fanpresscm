<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\crons;

/**
 * Set cron execution interval
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class release extends \fpcm\controller\abstracts\ajaxController
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
        
        if ( $this->request->fromPOST('unlock')) {
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return true;
        }

        $cronName = $this->request->fromPOST('cjId');
        $module = $this->request->fromPOST('cjmod');

        if (!$cronName) {
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
            trigger_error("Cronjob class {$cronName} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }
        
        if (!$cronjob->forceCancelation()) {
            return false;
        }

        $cronjob->setFinished();
        return true;
    }

}
