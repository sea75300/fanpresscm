<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system\conf;

/**
 * System config item for SMTP settings
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5.0-rc2
 */
class smtpSettings extends \fpcm\model\abstracts\configObj
{
    /**
     * 
     * @var string
     */
    public $addr;

    /**
     * 
     * @var string
     */
    public $srvurl;

    /**
     * 
     * @var int
     */
    public $port;

    /**
     * 
     * @var string
     */
    public $user;

    /**
     * 
     * @var string
     */
    public $pass;

    /**
     * 
     * @var string
     */
    public $encr;

    /**
     * 
     * @param array|string $data
     * @param bool $isEnabled
     * @return boolean
     */
    public function __construct($data, $oldConf = null, bool $isEnabled = false)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data) || !count($data)) {
            return;
        }

        $this->init($data);

        if (!$oldConf instanceof smtpSettings) {
            return;
        }

        $this->srvurl = filter_var($this->srvurl, FILTER_SANITIZE_URL);
        $this->addr = filter_var($this->addr, FILTER_SANITIZE_EMAIL);

        $old = $oldConf->pass ?? '';
        if ($isEnabled && trim($old) && !trim($this->pass)) {
            $this->pass = $old;
        }

    }

}
