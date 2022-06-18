<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system\conf;

/**
 * System config item for Twitter settings
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5.0-rc2
 */
class twitterSettings extends \fpcm\model\abstracts\configObj
{
    /**
     * API consumer key
     * @var string
     */
    public $consumer_key = '';

    /**
     * API consumer secret
     * @var string
     */
    public $consumer_secret = '';

    /**
     * API user token
     * @var string
     */
    public $user_token = '';

    /**
     * API user secret
     * @var string
     */
    public $user_secret = '';

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
        $this->consumer_key = '';
        $this->consumer_secret = '';
        $this->user_secret = '';
        $this->user_token = '';
        return true;
    }

    /**
     * Check if twitter settings are configured
     * @return bool
     */
    final public function isConfigured(): bool
    {
        return $this->consumer_key && $this->consumer_secret && $this->user_secret && $this->user_token;
    } 

}
