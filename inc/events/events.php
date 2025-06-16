<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * FanPress CM event list model
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class events implements \fpcm\model\interfaces\isObjectInstancable {

    /**
     * Run event $eventName with params $dataParams
     * @param string $eventName
     * @param mixed $dataParams
     * @return mixed|\fpcm\module\eventResult
     */
    public function trigger($eventName, $dataParams = null) : \fpcm\module\eventResult
    {
        if (!\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled()) {
            return (new \fpcm\module\eventResult())->setData($dataParams);
        }

        if (!file_exists($this->getFullPath($eventName . '.php'))) {
            trigger_error('ERROR: Undefined event called: ' . $eventName);
            return (new \fpcm\module\eventResult())->setData($dataParams);
        }

        fpcmLogEvents('Event: '.$eventName);
        if ($dataParams !== null) {
            fpcmLogEvents($dataParams);
        }

        try {

            /* @var $event abstracts\event */
            $eventClassName = "\\fpcm\\events\\" . $eventName;
            $event = new $eventClassName($dataParams);

            if (!$event->isExecutable()) {
                return (new \fpcm\module\eventResult())->setData($dataParams);
            }

            return $event->run();

        } catch (\Throwable $e) {

            $msg = sprintf("Unable to trigger event \"%s\" in \"%s\".\n- - - - -\nError-Code: %s\n- - - - -\n", $eventName, $eventClassName, $e);

            trigger_error($msg, E_USER_ERROR);
            if (\fpcm\classes\baseconfig::isCli()) {
                exit($msg);
            }

            \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications')->addNotification(
                new \fpcm\model\theme\notificationItem(
                    (new \fpcm\view\helper\icon('bomb'))->setText('NOTIFICATION_ERROR_EVENTS', ['eventName' => $eventName])
                )
            );

            return (new \fpcm\module\eventResult())->setData($dataParams);
        }

        return (new \fpcm\module\eventResult())->setData($dataParams);
    }

    /**
     * Gibt Liste mit Events des Systems zurück
     * @return array
     */
    public function getSystemEventList()
    {
        $list = [];
        foreach (glob($this->getFullPath('*.php')) as $file) {
            if ($file == __FILE__) {
                continue;
            }

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

    /**
     * Returns events class instance
     * @return events
     * @since 5.2.5-a1
     */
    public static function getInstance()
    {
        $iClass = static::class;

        if (!isset($GLOBALS['fpcm']['objects'][$iClass])) {
            $GLOBALS['fpcm']['objects'][$iClass] = new $iClass();
        }

        return $GLOBALS['fpcm']['objects'][$iClass];

    }

}
