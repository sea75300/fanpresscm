<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP message reponse result object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
final class responseMessage implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Response callback
     * @var mixed
     */
    private string $callback = '';
    
    /**
     * Response message
     * @var \fpcm\view\message|null
     */
    private ?\fpcm\view\message $message;

    /**
     * Auto-process message object
     * @var bool
     */
    private bool $_autoProcess = true;

    /**
     * Constructor
     * @param \fpcm\view\message $message
     * @param string $callback
     */
    public function __construct(
        \fpcm\view\message $message,
        string $callback = ''
    ) {
        $this->message = $message;
        $this->callback = $callback;
    }

}
