<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view;

/**
 * View message object
 * 
 * @package fpcm\view
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
class message implements \JsonSerializable {
    
    const TYPE_ERROR = 'error';
    const TYPE_NOTICE = 'notice';
    const TYPE_NEUTRAL = 'neutral';

    const ICON_ERROR = 'exclamation-triangle';
    const ICON_NOTICE = 'check';
    const ICON_NEUTRAL = 'info-circle';

    /**
     * Message text
     * @var string
     */
    private $txt = '';

    /**
     * Message type
     * @var string
     */
    private $type = '';

    /**
     * Message id
     * @var string
     */
    private $id = '';

    /**
     * Message icon
     * @var string
     */
    private $icon = '';

    /**
     * Constructor
     * @param string $txt
     * @param string $type
     * @param string $icon
     * @param string $id
     */
    public function __construct(string $txt, string $type, string $icon = '', string $id = '')
    {
        $this->txt = $txt;
        $this->type = $type;
        $this->icon = $icon;
        $this->id = $id;

        if (!trim($this->id)) {
            $this->id = md5($this->type . $this->txt);
        }
    }

    /**
     * Return message text
     * @return string
     */
    public function getTxt(): string {
        return $this->txt;
    }

    /**
     * Returns message type
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Return message id
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Return message icon
     * @return string
     */
    public function getIcon(): string {
        return $this->icon;
    }

    /**
     * Returns Data for json_encode
     * @return array
     * @ignore
     */
    public function jsonSerialize()
    {        
        return get_object_vars($this);
    }

}

?>