<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object für HTML data
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 5.0.0-a4
 */
final class responseDataHtml implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Response data
     * @var string 
     */
    private $html;

    /**
     * Response data
     * @var array
     */
    private $data;
    
    /**
     * Object constructor
     * @param string|null $html
     * @param array $data
     */
    public function __construct(?string $html = null, array $data = [])
    {
        $this->html = $html;
        $this->data = $data;
    }

}
