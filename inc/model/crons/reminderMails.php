<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Check and submit e-mail for reminders cronjob
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\crons
 * @since 5.3.0-b3
 */
class reminderMails extends \fpcm\model\abstracts\cron {

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {

        $instance = \fpcm\model\reminders\reminders::getInstance();

        $reminders = $instance->getRemindersForDatasets(
            type: '',
            start: time() - 60,
            uid: -1
        );

        if (!count($reminders)) {
            $this->updateLastExecTime();
            return true;
        }

        $sessions = \fpcm\model\system\session::getInstance()->getActiveSessions();

        $lang = new \fpcm\classes\language(\fpcm\model\system\config::getInstance()->system_lang);

        $hlLang = $lang->translate('HL_REMINDER');

        foreach ($reminders as $reminder) {

            if (in_array($reminder->getUserID(), $sessions)) {
                continue;
            }

            $user = new \fpcm\model\users\author($reminder->getUserID());

            $email = new \fpcm\classes\email($user->getEmail(), $hlLang);

            $text = sprintf(
                '%s: %s',
                new \fpcm\view\helper\dateText($reminder->getTime()),
                $reminder->getComment() ?? $hlLang
            );

            $email->setText($text);

            $res = $email->submit();
            if (!$res) {
                trigger_error(sprintf('Failed to submit reminder "%s" to e-mail address "%s"', $text, $user->getEmail()));
                continue;
            }
            
            fpcmLogSystem(sprintf('Submited reminder "%s" to e-mail address "%s"', $text, $user->getEmail()));

        }

        $this->updateLastExecTime();
        return true;
    }

}
