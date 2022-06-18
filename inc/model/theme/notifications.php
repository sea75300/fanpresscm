<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP notification list
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 * @since 3.6
 */
class notifications implements \Countable {

    /**
     * List of notificatio objects
     * @var array
     */
    private $notifications = [];

    /**
     * Counter of notifications
     * @var int
     */
    private $ctr = null;

    /**
     * Notification hinzufügen
     * @param \fpcm\model\theme\notificationItem $notification
     */
    public function addNotification(notificationItem $notification)
    {
        $this->notifications[] = $notification;
        $this->ctr = null;
    }

    /**
     * Array mit Notifications zurückgeben
     * @return array
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Array mit Notifications zurückgeben
     * @return array
     */
    public function count() : int
    {
        if ($this->ctr === null) {
            $this->ctr = count($this->notifications);
        }

        return $this->ctr;
    }

    /**
     * Returns notification string
     * @return string
     */
    public function __toString() : string
    {
        if (!count($this->notifications)) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('ban'))->setText('GLOBAL_NOTFOUND2'),
                '',
                '',
                'disabled'
            ));
        }

        $notificationStrings = array_map([$this, 'asString'], $this->notifications);
        return implode(PHP_EOL, $notificationStrings) . PHP_EOL;
    }

    /**
     * returns item as string
     * @param \fpcm\model\theme\notificationItem $item
     * @return string
     */
    private function asString(notificationItem $item)
    {
        return (string) $item;
    }

}
