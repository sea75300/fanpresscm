<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
namespace fpcm\view\helper\interfaces;

/**
 * Inline button interface
 * 
 * @package fpcm\view\helper\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a1
 */
interface inlineButton
{
    /**
     * Render link button as inline list group item
     * @param string $class
     * @return string
     */
    public function asInline(string $size = '', string $class = '') : string;
}
