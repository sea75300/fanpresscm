<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * System permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
class system extends base {

    /**
     * Categories management
     * @var bool
     */
    public $categories;

    /**
     * options management
     * @var bool
     */
    public $options;

    /**
     * Users management
     * @var bool
     */
    public $users;

    /**
     * Rolls management
     * @var bool
     */
    public $rolls;

    /**
     * Permissions management
     * @var bool
     */
    public $permissions;

    /**
     * Templates management
     * @var bool
     */
    public $templates;

    /**
     * Draft management
     * @var bool
     */
    public $drafts;

    /**
     * Smileys management
     * @var bool
     */
    public $smileys;

    /**
     * Update processing
     * @var bool
     */
    public $update;

    /**
     * Logs management
     * @var bool
     */
    public $logs;

    /**
     * Cron management
     * @var bool
     */
    public $crons;

    /**
     * Backup management
     * @var bool
     */
    public $backups;

    /**
     * Word ban management
     * @var bool
     */
    public $wordban;

    /**
     * IP address management
     * @var bool
     */
    public $ipaddr;

    /**
     * Can change profile
     * @var bool
     */
    public $profile;

}
