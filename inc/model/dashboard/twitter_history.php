<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Welcome dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class twitter_history extends \fpcm\model\abstracts\dashcontainer implements \fpcm\model\interfaces\isAccessible {

    /**
     * Twitetr object
     * @var \fpcm\model\system\twitter
     */
    private $twitter;

    /**
     * Init internal objects
     * return void
     */
    protected function initObjects()
    {
        $this->twitter = new \fpcm\model\system\twitter();
    }

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'welcome';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        return '<p class="px-2">' . $this->language->translate('RECENT_TWEETS') . '</p>';
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'RECENT_TWEETS';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 9;
    }

    /**
     * Container is visible/accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return defined('FPCM_DEBUD') && FPCM_DEBUG && $this->twitter->checkConnection();
    }

}
