<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * FanPress CM event list model
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class events {

    /**
     * Run event $eventName with params $dataParams
     * @param string $eventName
     * @param mixed $dataParams
     * @return mixed
     * @deprecated since version number
     */
    public function runEvent($eventName, $dataParams = null)
    {
        trigger_error('Event calling via "runEvent" is deprecated, use "trigger" instead! Event: '.$eventName);
        return $this->trigger($eventName, $dataParams);
    }

    /**
     * Run event $eventName with params $dataParams
     * @param string $eventName
     * @param mixed $dataParams
     * @return mixed
     */
    public function trigger($eventName, $dataParams = null)
    {
        if (!\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled()) {
            return $dataParams;
        }

        if (!file_exists($this->getFullPath($eventName . '.php'))) {
            trigger_error('ERROR: Undefined event called: ' . $eventName);
            return $dataParams;
        }

        fpcmLogEvents(['Event: '.$eventName, 'Params: ', $dataParams]);

        /**
         * @var \fpcm\events\event
         */
        $eventClassName = "\\fpcm\\events\\" . $eventName;
        $event = new $eventClassName($dataParams);

        if (!$event->checkPermissions()) {
            return $dataParams;
        }

        return $event->run();
    }

    /**
     * Gibt Liste mit Events des Systems zurück
     * @return array
     */
    public function getSystemEventList()
    {
        $list = [];
        foreach (glob($this->getFullPath('*.php')) as $file) {
            if ($file == __FILE__)
                continue;
            $list[] = basename($file, '.php');
        }

        return $list;
    }

    /**
     * Vollständigen Pfad zurückgegeben
     * @param string $path
     * @return string
     */
    private function getFullPath($path)
    {
        return \fpcm\classes\dirs::getIncDirPath(implode(DIRECTORY_SEPARATOR, [
            'events',
            str_replace('\\', DIRECTORY_SEPARATOR, $path)
        ]));
    }

}
