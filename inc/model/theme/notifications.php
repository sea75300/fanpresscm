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
     * Prepends system notifications
     * @return void
     * @since 5.1-dev
     */
    final public function prependSystemNotifications(): void
    {
        $cgf = \fpcm\model\system\config::getInstance();

        if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('history'))->setText('SYSTEM_OPTIONS_CRONJOBS')
            ));
        }

        if (defined('FPCM_DEBUG') && FPCM_DEBUG) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('terminal'))->setText('DEBUG_MODE'),
                '', '', 'text-danger'
            ));
        }

        if (defined('FPCM_VIEW_JS_USE_MINIFIED') && FPCM_VIEW_JS_USE_MINIFIED) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('js', 'fab'))->setText('NOTIFICATION_EXPERIMENTAL_MINJS')
            ));
        }

        if (str_contains(FPCM_CACHE_BACKEND, 'memcacheBackend')) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('flask'))->setText('memcache cache backend is enabled!')
            ));
        }

        if ($cgf->system_maintenance) {
            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('lightbulb'))->setText('SYSTEM_OPTIONS_MAINTENANCE'),
                '', '', 'text-danger'
            ));
        }

        if ($cgf->system_comments_enabled && $ctr = (new \fpcm\model\comments\commentList)->getNewCommentCount()) {

            $this->addNotification(new \fpcm\model\theme\notificationItem(
                (new \fpcm\view\helper\icon('comments'))->setText('COMMENTS_NOTIFICATION_NEW_COUNT', [$ctr], true),
                '',
                (new \fpcm\view\helper\linkButton('new-comments'))
                    ->setUrl(\fpcm\classes\tools::getControllerLink('comments/list'))
                    ->setText('HL_COMMENTS_MNG')
                    ->setIcon('square-arrow-up-right')
                    ->overrideButtonType('warning')
            ));

        }

        /* @var $perm \fpcm\model\permissions\permissions */
        $perm = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');
        if ($perm?->system?->options && $perm?->system?->update) {

            $updates = new \fpcm\model\updater\system();

            if (!$updates->updateAvailable()) {

                $this->addNotification(new \fpcm\model\theme\notificationItem(
                    (new \fpcm\view\helper\icon('cloud-download-alt'))->setText('UPDATE_VERSIONCHECK_NEW', [
                        '{{btn}}' => '',
                        '{{version}}' => $updates->version
                    ]),
                    '',
                    (new \fpcm\view\helper\linkButton('new-comments'))
                        ->setUrl(\fpcm\classes\tools::getControllerLink('package/sysupdate'))
                        ->setText('HL_PACKAGEMGR_SYSUPDATES')
                        ->setIcon('arrows-spin')
                        ->overrideButtonType('warning')
                ));

            }

        }
    }

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

        $notificationStrings = array_map(function (notificationItem $item) {
            return (string) $item;
        }, $this->notifications);

        return implode(PHP_EOL, $notificationStrings) . PHP_EOL;
    }

}
