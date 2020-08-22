<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 4.4
 */
final class responseData implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Response code
     * @var mixed
     */
    private $code;

    /**
     * Response data
     * @var mixed 
     */
    private $data;

    /**
     * Object constructor
     * @param mixed $code
     * @param mixed $data
     */
    public function __construct($code, $data = null)
    {
        $this->code = $code;
        $this->data = $data;
    }

}
