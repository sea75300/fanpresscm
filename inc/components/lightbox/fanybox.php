<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\lightbox;

/**
 * Fancybox component
 * 
 * @package fpcm\components\lightbox
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.-0-a1
 */
final class fanybox implements \fpcm\model\interfaces\viewComponent {

    /**
     * Returns CSS files for uploader
     * @return array
     */
    public function getCssFiles(): array
    {
        return [
            \fpcm\view\view::ROOTURL_LIB.'fancybox/jquery.fancybox.min.css',
        ];
    }

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    public function getJsFiles(): array
    {
        return [
            \fpcm\view\view::ROOTURL_LIB.'fancybox/jquery.fancybox.min.js',
        ];
    }

    /**
     * Returns JavaScript files for uploader for late loading
     * @return array
     */
    public function getJsFilesLate(): array
    {
        return [];
    }

    /**
     * Returns JavaScript language variables for uploader
     * @return array
     */
    public function getJsLangVars(): array
    {
        return [];
    }

    /**
     * Returns JavaScript variables for uploader
     * @return array
     */
    public function getJsVars(): array
    {
        return [];
    }

    /**
     * Returns View variables for uploader
     * @return array
     */
    public function getViewVars(): array
    {
        return [];
    }

    /**
     * Returns list of JavaScript ECMA module files
     * @return array
     * @since 5.2
     */
    public function getJsModuleFiles(): array
    {
        return [];
    }

}
