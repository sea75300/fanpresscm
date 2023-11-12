<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\fileupload;

/**
 * Legacy HTML object
 * 
 * @package fpcm\components\fileupload
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
final class htmlupload extends uploader {

    /**
     * Returns CSS files for uploader
     * @return array
     */
    public function getCssFiles(): array
    {
        return [];
    }

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    public function getJsFiles(): array
    {
        return ['files/htmlupload.js'];
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
     * Returns View template for uploader
     * @return array
     */
    public function getTemplate(): string
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'filemanager/forms/phpupload.php');
    }

    /**
     * Returns View variables for uploader
     * @return array
     */
    public function getViewVars(): array
    {
        return [
            'maxFilesInfo' => $this->language->translate('FILE_LIST_PHPMAXINFO', [
                '{{filecount}}' => ini_get("max_file_uploads"),
                '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
            ])
        ];
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
