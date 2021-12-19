<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\fileupload;

/**
 * jqUpload object
 * 
 * @package fpcm\components\fileupload
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
final class jqupload extends uploader {

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
            \fpcm\classes\dirs::getLibUrl('jqupload/css/jquery.fileupload.css'),
            \fpcm\classes\dirs::getLibUrl('cropper_js/cropper.min.css'),
        ];
    }

    /**
     * Returns JavaScript files for uploader
     * @return array
     */
    public function getJsFiles(): array
    {
        return ['files/jqupload.js', 'files/imageEditor.js', \fpcm\classes\dirs::getLibUrl('cropper_js/cropper.min.js')];
    }

    /**
     * Returns JavaScript files for uploader for late loading
     * @return array
     */
    public function getJsFilesLate(): array
    {
        return [
            \fpcm\classes\dirs::getLibUrl('jqupload/js/vendor/jquery.ui.widget.js'),
            \fpcm\classes\dirs::getLibUrl('jqupload/js/jquery.iframe-transport.js'),
            \fpcm\classes\dirs::getLibUrl('jqupload/js/jquery.fileupload.js'),
            \fpcm\classes\dirs::getLibUrl('jqupload/js/jquery.fileupload-process.js'),
            \fpcm\classes\dirs::getLibUrl('jqupload/js/jquery.fileupload-validate.js'),
            \fpcm\classes\dirs::getLibUrl('jqupload/js/jquery.fileupload-ui.js'),
        ];
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
        return [
            'uploadListButtons' => [
                'start' => (string) (new \fpcm\view\helper\button('startlist', 'startlist_{{id}}'))->setClass('start')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload')->setIconOnly(true),
                'cancel' => (string) (new \fpcm\view\helper\button('cancellist', 'cancellist_{{id}}'))->setClass('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban')->setIconOnly(true)
            ]
        ];
    }

    /**
     * Returns View template for uploader
     * @return array
     */
    public function getTemplate(): string
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'filemanager/forms/jqupload.php');
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
