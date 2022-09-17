<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\gsearch;

/**
 * Global search indexer result set item
 * 
 * @package fpcm\model\gsearch
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class resultItem implements \JsonSerializable
{
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Result item text
     * @var string
     */
    private string $text;

    /**
     * Result item link
     * @var string
     */
    private string $link;

    /**
     * Element icon
     * @var string
     */
    private string $icon;

    /**
     * Constructor
     * @param string $text
     * @param string $link
     * @param string $icon
     */
    public function __construct(string $text, string $link, \fpcm\view\helper\icon $icon)
    {
        $this->text = strip_tags($text, ['<br>', '<span>']);
        $this->link = $link;
        $this->icon = (string) $icon;
    }

}
