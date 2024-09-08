<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * JavaScript ECMA module files interface
 *
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface JsModuleFiles {

    /**
     * Returns list of JavaScript files
     * @return array
     */
    public function getJsModuleFiles(): array;

}
