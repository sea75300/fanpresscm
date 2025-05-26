<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: getOAuthProvider
 *
 * Event is executes during email object samtp init
 * Params: none
 * Return value: \PHPMailer\PHPMailer\OAuthTokenProvider
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.2-5
 * @see \fpcm\classes\email::initSmtpSettings
 */
final class getOAuthProvider extends \fpcm\events\abstracts\event {

    public function run()
    {
        $class = $this->getEventClasses()[0] ?? false;
        if ($class === false) {
            return (new \fpcm\module\eventResult)->setSuccessed(true)->setContinue(true);
        }

        if (!class_exists($class) || !$this->is_a($class)) {
            return (new \fpcm\module\eventResult)->setSuccessed(false)->setContinue(false);
        }

        return (new $class)->run();
    }

}
