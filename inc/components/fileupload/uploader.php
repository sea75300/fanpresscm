<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\fileupload;

/**
 * Abstract uploader object
 * 
 * @package fpcm\components\fileupload
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
abstract class uploader extends \fpcm\model\abstracts\staticModel {

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    abstract public function getJsFiles() : array;

    /**
     * Returns JavaScript files for uploader for late loading
     * @return array
     */
    abstract public function getJsFilesLate() : array;

    /**
     * Returns CSS files for uploader
     * @return array
     */
    abstract public function getCssFiles() : array;

    /**
     * Returns JavaScript variables for uploader
     * @return array
     */
    abstract public function getJsVars() : array;

    /**
     * Returns JavaScript language variables for uploader
     * @return array
     */
    abstract public function getJsLangVars() : array;

    /**
     * Returns View variables for uploader
     * @return array
     */
    abstract public function getViewVars() : array;

    /**
     * Returns View template for uploader
     * @return array
     */
    abstract public function getTemplate() : string;

}
