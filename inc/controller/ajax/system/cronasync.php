<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Cron on demand execution controller
 * 
 * @package fpcm\controller\ajax\system\cronasync
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class cronasync extends \fpcm\controller\abstracts\ajaxController {

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
        $cjId = $this->getRequestVar('cjId');
        if (!$cjId) {
            return true;
        }

        $cjClassName = \fpcm\model\abstracts\cron::getCronNamespace($cjId);

        /* @var $cronjob \fpcm\model\abstracts\cron */
        $cronjob = new $cjClassName($cjId);

        if (!is_a($cronjob, '\fpcm\model\abstracts\cron')) {
            trigger_error("Cronjob class {$cjId} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }

        $cronjob->run();
        $cronjob->updateLastExecTime();
        return true;
    }

}

?>