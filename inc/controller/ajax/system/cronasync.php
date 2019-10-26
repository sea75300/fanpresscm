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
        $cronName = $this->getRequestVar('cjId');
        $module = $this->getRequestVar('cjmod');
        if (!$cronName) {
            return true;
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

        if (!class_exists($cronName)) {
            trigger_error("Undefined cronjon {$cronName} called");
            return false;
        }

        /* @var $cron \fpcm\model\abstracts\cron */
        $cron = new $cronName();
        if (!($cron instanceof \fpcm\model\abstracts\cron)) {
            trigger_error("Cronjob class {$cronName} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }

        return (new \fpcm\model\crons\cronlist())->registerCronAjax($cron);
    }

}

?>