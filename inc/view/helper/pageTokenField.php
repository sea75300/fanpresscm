<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Pagetoken input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class pageTokenField extends hiddenInput {

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $pageTokens = new \fpcm\classes\pageTokens();

        return "<input type=\"hidden\" name=\"token\" value=\"{$pageTokens->refresh()}\">";
    }

}

?>