<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\modules;

/**
 * Module eEvent: api
 *
 * Event is executes during email object samtp init
 * Params: none
 * Return value: \PHPMailer\PHPMailer\OAuthTokenProvider
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.3.0-a1
 * @see \fpcm\classes\email::initSmtpSettings
 */
class api extends \fpcm\events\abstracts\event {

    /**
     * Executes a certain event
     * @return array
     */
    public function run() : \fpcm\module\eventResult
    {
        $class = \fpcm\module\module::getEventNamespace($this->data, 'api');
        if (!class_exists($class)) {
            return (new \fpcm\module\eventResult)->setSuccessed(false);
        }

        $obj = new $class();
        if (!$obj instanceof \fpcm\module\api) {
            return (new \fpcm\module\eventResult)->setSuccessed(false);
        }

        return (new \fpcm\module\eventResult())->setData($obj);
    }

}
