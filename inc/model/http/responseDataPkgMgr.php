<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object for package manager requests
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 4.5
 */
final class responseDataPkgMgr implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Dataview vars
     * @var string
     */
    private $code;

    /**
     * Dataview name
     * @var array
     */
    private $pkgdata;

    /**
     * Dataview message
     * @var \fpcm\view\message
     */
    private $message;

    /**
     * Constructor
     * @param int|bool|string $code
     * @param array $pkgdata
     * @param \fpcm\view\message|null $message
     */
    function __construct($code, array $pkgdata = [], $message = null)
    {
        $this->code = $code;
        $this->pkgdata = $pkgdata;
        $this->message = $message;
    }


}
