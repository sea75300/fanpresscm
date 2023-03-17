<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object fpr system ajax refresh
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 5.1-dev
 */
final class responseDataRefresh implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Cronjobs
     * @var bool
     */
    public $crons = false;

    /**
     * Session check code
     * @var int
     */
    public $sessionCode = -1;

    /**
     * article editing code
     * @var int
     */
    public $articleCode = 0;

    /**
     * article editing username
     * @var string
     */
    public $username = '';

    /**
     * notifications list
     * @var string
     */
    public $notifications = '';

    /**
     * notifications list
     * @var int
     */
    public $notificationCount = 0;

}
