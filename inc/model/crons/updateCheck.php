<?php

/**
 * FanPress CM Update Check Cronjob
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
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
            return false;
        }

        $updater = new \fpcm\model\updater\system();

        /* @var $config \fpcm\model\system\config */
        $config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');

        /* @var $session \fpcm\model\system\session */
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        
        $res = $updater->updateAvailable();
        if ($res && $config->system_updates_emailnotify && !$session->exists()) {

            $language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
            $email = new \fpcm\classes\email($config->system_email, $language->translate('CRONJOB_UPDATES_NEWVERSION'), $language->translate('CRONJOB_UPDATES_NEWVERSION_TEXT', [
                '{{version}}' => $updater->version,
                '{{acplink}}' => \fpcm\classes\dirs::getRootUrl()
            ]));

            $res2 = $email->submit();
        }

        $this->updateLastExecTime();

        return true;
    }

}
