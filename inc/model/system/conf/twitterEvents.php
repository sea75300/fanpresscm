<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system\conf;

/**
 * System config item for Twitter event settings
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5.0-rc2
 */
class twitterEvents extends \fpcm\model\abstracts\configObj
{
    /**
     * Tweet on create/ first save
     * @var bool
     */
    public $create = 0;

    /**
     * Tweet on update/ additional saves
     * @var boll
     */
    public $update = 0;

    /**
     * Constructor
     * @param array|string $data
     * @param bool $isEnabled
     * @return boolean
     */
    public function __construct($data, $oldConf = null)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data) || !count($data)) {
            return;
        }

        $this->init($data);
    }

    /**
     * Reset twitter connection settings
     * @return bool
     */
    final public function reset(): bool
    {
        $this->create = 0;
        $this->update = 0;
        return true;
    }

    /**
     * Check if tweet creation is enabled
     * @return bool
     */
    final public function isConfigured(): bool
    {
        return $this->create || $this->update;
    } 

}
