<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * View component interface
 *
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface viewComponent {

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    public function getJsFiles() : array;

    /**
     * Returns JavaScript files for uploader for late loading
     * @return array
     */
    public function getJsFilesLate() : array;

    /**
     * Returns JavaScript variables for uploader
     * @return array
     */
    public function getJsVars() : array;

    /**
     * Returns JavaScript language variables for uploader
     * @return array
     */
    public function getJsLangVars() : array;

    /**
     * Returns CSS files for uploader
     * @return array
     */
    public function getCssFiles() : array;

    /**
     * Returns list of JavaScript files
     * @return array
     */
    public function getJsModuleFiles(): array;

    /**
     * Returns View variables for uploader
     * @return array
     */
    public function getViewVars() : array;

}
