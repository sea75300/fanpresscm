<?php
    /**
     * FanPress CM 3.x
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
     * @since FPCM 3.6
     */ 
    class notifications {

        /**
         *
         * @var array
         */
        private $notifications = [];

        /**
         * Notification hinzufügen
         * @param \fpcm\model\theme\notificationItem $notification
         */
        public function addNotification(notificationItem $notification) {
            $this->notifications[] = $notification;            
        }

        /**
         * Array mit Notifications zurückgeben
         * @return array
         */
        public function getNotifications() {
            return $this->notifications;
        }

        /**
         * Notifications anzeigen
         * @return array
         */
        public function getNotificationsString() {

            if (!count($this->notifications)) {
                return '';
            }

            $notificationStrings = array_map([$this, 'asString'], $this->notifications);
            return implode(PHP_EOL, $notificationStrings).PHP_EOL;
        }

        /**
         * 
         * @param \fpcm\model\theme\notificationItem $item
         * @return string
         */
        private function asString(notificationItem $item) {
            return (string) $item;
        }

    }
