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
class resultItem
{
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
     * Return result item text
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * Return result item link
     * @return string
     */
    public function getLink(): string {
        return $this->link;
    }

    /**
     * Set result item text
     * @param string $text
     * @return $this
     */
    public function setText(string $text) {
        $this->text = $text;
        return $this;
    }

    /**
     * Set result item link
     * @param string $link
     * @return $this
     */
    public function setLink(string $link) {
        $this->link = $link;
        return $this;
    }

}
