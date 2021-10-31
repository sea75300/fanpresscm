<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\fileupload;

/**
 * Uppy file upload object
 * 
 * @package fpcm\components
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0.0-a3
 */
final class uppy extends uploader {

    const FILETYPES_IMG = '/\.(gif|jpe?g|png)$/i';

    const FILETYPES_DRAFTS = '/\.(htm|html|txt)$/i';

    const FILETYPES_MODULES = '/\.(zip)$/i';

    const FILETYPES_CSV = '/\.(csv)$/i';
    
    /**
     * Returns CSS files for uploader
     * @return array
     */
    public function getCssFiles(): array
    {
        return [
            'https://releases.transloadit.com/uppy/v2.2.1/uppy.min.css',
        ];
    }

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    public function getJsFiles(): array
    {
        return [
            'https://releases.transloadit.com/uppy/v2.2.1/uppy.min.js',
            'https://releases.transloadit.com/uppy/locales/v2.0.3/de_DE.min.js',
            'files/uppy.js'
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
        return ['SAVE_FAILED_UPLOAD_GEN'];
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
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'filemanager/forms/uppy.php');
    }

    /**
     * Returns View variables for uploader
     * @return array
     */
    public function getViewVars(): array
    {
        
        return [
            'uploadTemplatePath' => $this->getTemplate(),
            'uploadMultiple' => true
        ];
    }

}
