<?php

/**
 * FanPress CM Update Check Cronjob
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Cronjob update check
 * 
 * @package fpcm\model\crons
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class updateCheck extends \fpcm\model\abstracts\cron {

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {
        $repo = new \fpcm\model\packages\repository();
        if (!$repo->fetchRemoteData()) {
            trigger_error('Unable to fetch data from package server.');
            return false;
        }
        
        $updater = new \fpcm\model\updater\system();

        /* @var $config \fpcm\model\system\config */
        $config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');

        $res = $updater->updateAvailable();
        if ($res && $this->getAsyncCurrent() && $config->system_updates_emailnotify) {

            $replacements = array(
                '{{version}}' => $updater->getRemoteData('version'),
                '{{acplink}}' => \fpcm\classes\dirs::getRootUrl()
            );

            $language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
            $email = new \fpcm\classes\email($config->system_email, $language->translate('CRONJOB_UPDATES_NEWVERSION'), $language->translate('CRONJOB_UPDATES_NEWVERSION_TEXT', $replacements));
            $email->submit();
        }

        $this->updateLastExecTime();

        return true;
    }

}
