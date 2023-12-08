<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Result object for extendTabs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class extendTabsResult {

    /**
     * Tabs id
     * @var array
     */
    public $tabsId = '';

    /**
     * Tabs class for styles
     * @var array
     */
    public $tabsClass = '';

    /**
     * Active tab index for 0 to n-1
     * @var int
     */
    public $activeTab;

    /**
     * Tabs list
     * @var array
     */
    public $tabs = [];

}
