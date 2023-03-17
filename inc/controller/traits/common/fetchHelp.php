<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Fetch help data traot
 * 
 * @package fpcm\controller\traits\common\timezone
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait fetchHelp {

    public function getChapter(string $ref)
    {
        $xml = simplexml_load_string($this->language->getHelp());
        return $xml->xpath("/chapters/chapter[@ref=\"{$ref}\"]");
    }

}
